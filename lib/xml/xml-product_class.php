<?php
/**
 * Google Merchant Center
 *
 * @author    BusinessTech.fr - https://www.businesstech.fr
 * @copyright Business Tech - https://www.businesstech.fr
 * @license   Commercial
 *
 *           ____    _______
 *          |  _ \  |__   __|
 *          | |_) |    | |
 *          |  _ <     | |
 *          | |_) |    | |
 *          |____/     |_|
 */

class BT_XmlProduct extends BT_BaseXml
{
    /**
     * @param array $aParams
     */
    public function __construct(array $aParams = null)
    {
        parent::__construct($aParams);
    }

    /**
     * load products combination
     *
     * @param int $iProductId
     * @return array
     */
    public function hasCombination($iProductId)
    {
        return array($iProductId);
    }

    /**
     * build product XML tags
     *
     * @return bool
     */
    public function buildDetailProductXml()
    {

        // get weight
        $this->data->step->weight = (float)$this->data->p->weight;

        // handle different prices and shipping fees
        $this->data->step->price_default_currency_no_tax = Tools::convertPrice(Product::getPriceStatic((int)$this->data->p->id, false, null), $this->data->currency, false);

        // Exclude based on min price
        if (
            !empty(GMerchantCenter::$conf['GMC_MIN_PRICE'])
            && ((float)$this->data->step->price_default_currency_no_tax < (float)GMerchantCenter::$conf['GMC_MIN_PRICE'])
        ) {
            BT_GmcReporting::create()->set('_no_export_min_price', array('productId' => $this->data->step->id_reporting));
            return false;
        }

        if (isset($this->aParams['bUseTax'])) {
            $bUseTax = !empty($this->aParams['bUseTax']) ? true : false;
        } else {
            $bUseTax = true;
        }

        $this->data->step->price_raw = Product::getPriceStatic((int)$this->data->p->id, $bUseTax, null, 6);
        $this->data->step->price_raw_no_discount = Product::getPriceStatic((int)$this->data->p->id, $bUseTax, null, 6, null, false, false);
        $this->data->step->price = number_format(BT_GmcModuleTools::round($this->data->step->price_raw), 2, '.', '') . ' ' . $this->data->currency->iso_code;
        $this->data->step->price_no_discount = number_format(BT_GmcModuleTools::round($this->data->step->price_raw_no_discount), 2, '.', '') . ' ' . $this->data->currency->iso_code;

        //  Use case to override the price with the pack price of Advanced pack
        if (GMerchantCenter::$bAdvancedPack && AdvancedPack::isValidPack($this->data->p->id)) {
            $oPack = new AdvancedPack($this->data->p->id);
            $this->data->step->price_raw = AdvancedPack::getPackPrice($oPack->id, $bUseTax, true, true, 2);
            $this->data->step->price_raw_no_discount = AdvancedPack::getPackPrice($oPack->id, $bUseTax, false, false, 2);
            $this->data->step->price = number_format(BT_GmcModuleTools::round($this->data->step->price_raw), 2, '.', '') . ' ' . $this->data->currency->iso_code;
            $this->data->step->price_no_discount = number_format(BT_GmcModuleTools::round($this->data->step->price_raw_no_discount), 2, '.', '') . ' ' . $this->data->currency->iso_code;
        }

                // Available date
            $this->data->step->availabilty_date = "";
            if ($this->data->p->available_date != "0000-00-00") {
                $this->data->step->availabilty_date = $this->data->p->available_date;
            }
            
        // shipping fees
        if (
            !empty(GMerchantCenter::$conf['GMC_SHIPPING_USE'])
            && empty($this->aParams['freeShipping'][$this->data->p->id])
        ) {
            $fPrice = number_format((float)$this->getProductShippingFees((float)BT_GmcModuleTools::round($this->data->step->price_raw)), 2, '.', '');
        } else {
            $fPrice = number_format((float)0, 2, '.', '');
        }
        $this->data->step->shipping_fees = $fPrice . ' ' . $this->data->currency->iso_code;


        // Use case for dimenstion
        if (!empty(GMerchantCenter::$conf['GMC_DIMENSION'])) {
            $aDataDimension = BT_GmcModuleTools::getDimension($this->data->p->width, $this->data->p->height, $this->data->p->depth);
            if (!empty($aDataDimension)) {
                $this->data->step->shipping_width =  $aDataDimension['shipping_width'];
                $this->data->step->shipping_height = $aDataDimension['shipping_height'];
                $this->data->step->shipping_length  = $aDataDimension['shipping_length'];
            }
        }

        // get images
        $this->data->step->images = $this->getImages($this->data->p);

        // quantity
        // Do not export if the quantity is 0 for the combination and export out of stock setting is not On
        if (
            (int)$this->data->p->quantity < 1
            && (int)GMerchantCenter::$conf['GMC_EXPORT_OOS'] == 0
        ) {
            BT_GmcReporting::create()->set('_no_export_no_stock', array('productId' => $this->data->step->id_reporting));
            return false;
        }

        // quantity
        $this->data->step->quantity = (int)$this->data->p->quantity;

        //Manage GTIN code
        $this->data->step->gtin = BT_GmcModuleTools::getGtin(GMerchantCenter::$conf['GMC_GTIN_PREF'], (array)$this->data->p);

        // Exclude without EAN
        if (
            GMerchantCenter::$conf['GMC_EXC_NO_EAN']
            && empty($this->data->step->gtin)
        ) {
            BT_GmcReporting::create()->set('_no_export_no_ean_upc', array('productId' => $this->data->step->id_reporting));
            return false;
        }

        // supplier reference
        $this->data->step->mpn = $this->getSupplierReference($this->data->p->id, $this->data->p->id_supplier, $this->data->p->supplier_reference, $this->data->p->reference);

        // exclude if mpn is empty
        if (
            !empty(GMerchantCenter::$conf['GMC_EXC_NO_MREF'])
            && !GMerchantCenter::$conf['GMC_INC_ID_EXISTS']
            && empty($this->data->step->mpn)
        ) {
            BT_GmcReporting::create()->set('_no_export_no_supplier_ref', array('productId' => $this->data->step->id_reporting));
            return false;
        }

        //handle the specific price feature
        if (!empty($this->data->p->specificPrice['from'])) {
            $this->data->step->specificPriceFrom = $this->data->p->specificPrice['from'];
        }
        if (!empty($this->data->p->specificPrice['to'])) {
            $this->data->step->specificPriceTo = $this->data->p->specificPrice['to'];
        }

        $this->data->step->visibility = $this->data->p->visibility;

        return true;
    }

    /**
     * format the product name
     *
     * @param int $iAdvancedProdName
     * @param int $iAdvancedProdTitle
     * @param string $sProdName
     * @param string $sCatName
     * @param string $sManufacturerName
     * @param int $iLength
     * @param int $iProdAttrId
     * @return string
     */
    public function formatProductName(
        $iAdvancedProdName,
        $iAdvancedProdTitle,
        $sProdName,
        $sCatName,
        $sManufacturerName,
        $iLength,
        $iProdAttrId = null
    ) {
        $sProdName = BT_GmcModuleTools::truncateProductTitle($iAdvancedProdName, $sProdName, $sCatName, $sManufacturerName, $iLength);

        return BT_GmcModuleTools::formatProductTitle($sProdName, $iAdvancedProdTitle);
    }

    /**
     * get images of one product or one combination
     *
     * @param obj $oProduct
     * @param int $iProdAttributeId
     * @return array
     */
    public function getImages(Product $oProduct, $iProdAttributeId = null)
    {
        // set vars
        $aResultImages = array();
        $iCounter = 1;

        // get cover
        $aImage = Product::getCover($oProduct->id);

        // Additional images
        $aOtherImages = $oProduct->getImages(GMerchantCenter::$iCurrentLang);
        foreach ($aOtherImages as $img) {
            if ((int)$img['id_image'] != (int)$aImage['id_image'] && $iCounter <= _GMC_IMG_LIMIT && $img['cover'] != 1) {
                $aResultImages[] = array('id_image' => (int)$img['id_image']);
                $iCounter++;
            }
        }

        return array('image' => $aImage, 'others' => $aResultImages);
    }

    /**
     * get supplier reference
     *
     * @param int $iProdId
     * @param int $iSupplierId
     * @param string $sSupplierRef
     * @param string $sProductRef
     * @param int $iProdAttributeId
     * @param string $sCombiSupplierRef
     * @param string $sCombiRef
     * @return string
     */
    public function getSupplierReference($iProdId, $iSupplierId, $sSupplierRef = null, $sProductRef = null, $iProdAttributeId = 0, $sCombiSupplierRef = null, $sCombiRef = null)
    {
        if (empty(GMerchantCenter::$bCompare1770)) {
            // detect the MPN type
            $sReturnRef = BT_GmcModuleDao::getProductSupplierReference($iProdId, $iSupplierId);
        } else {
            $oProduct = new Product($iProdId);
            $sReturnRef = $oProduct->mpn;
        }

        return $sReturnRef;
    }
}
