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

class BT_XmlCombination extends BT_BaseXml
{
    /**
     *
     *
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
        return BT_GmcModuleDao::getProductCombination($this->aParams['iShopId'], $iProductId);
    }

    /**
     * build product XML tags
     *
     * @return bool
     */
    public function buildDetailProductXml()
    {
        // set the product ID
        $this->data->step->id = $this->data->p->id . GMerchantCenter::$conf['GMC_COMBO_SEPARATOR'] . $this->data->c['id_product_attribute'];
        $this->data->step->id_no_combo = $this->data->p->id;

        $product_category = new Category((int)$this->data->p->getDefaultCategory(), (int)Tools::getValue('gmc_lang_id'));
        $this->data->step->url = Context::getContext()->link->getProductLink($this->data->p, null, Tools::strtolower($product_category->link_rewrite), null, (int)Tools::getValue('gmc_lang_id'), (int)Tools::getValue('id_shop'), (int) $this->data->c['id_product_attribute'], false, false, false, array(), false);

        if (!empty(GMerchantCenter::$conf['GMC_ADD_CURRENCY'])) {
            $this->data->step->url .= (strpos($this->data->step->url, '?') !== false) ? '&SubmitCurrency=1&id_currency=' . (int)$this->data->currencyId : '?SubmitCurrency=1&id_currency=' . (int)$this->data->currencyId;
        }
        if (!empty(GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'])) {
            $this->data->step->url .= (strpos($this->data->step->url, '?') !== false) ? '&utm_campaign=' . GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'] : '?utm_campaign=' . GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'];
        }
        if (!empty(GMerchantCenter::$conf['GMC_UTM_SOURCE'])) {
            $this->data->step->url .= (strpos($this->data->step->url, '?') !== false) ? '&utm_source=' . GMerchantCenter::$conf['GMC_UTM_SOURCE'] : '?utm_source=' . GMerchantCenter::$conf['GMC_UTM_SOURCE'];
        }
        if (!empty(GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'])) {
            $this->data->step->url .= (strpos($this->data->step->url, '?') !== false) ? '&utm_medium=' . GMerchantCenter::$conf['GMC_UTM_MEDIUM'] : '?utm_medium=' . GMerchantCenter::$conf['GMC_UTM_MEDIUM'];
        }

        // get weight
        $this->data->step->weight = (float)$this->data->p->weight + (float)$this->data->c['weight'];

        // handle different prices and shipping fees
        $this->data->step->price_default_currency_no_tax = Tools::convertPrice(Product::getPriceStatic((int)$this->data->p->id, false, (int)$this->data->c['id_product_attribute']), $this->data->currency, false);

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

        $this->data->step->price_raw = Product::getPriceStatic((int)$this->data->p->id, $bUseTax, (int)$this->data->c['id_product_attribute']);
        $this->data->step->price_raw_no_discount = Product::getPriceStatic((int)$this->data->p->id, $bUseTax, (int)$this->data->c['id_product_attribute'], 6, null, false, false);
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

        $this->data->step->availabilty_date = "";
        // Available date
        if ($this->data->c['available_date'] != "0000-00-00") {
            $this->data->step->availabilty_date = $this->data->c['available_date'];
        }

        // shipping fees
        if (!isset($this->aParams['freeShipping'][$this->data->p->id])) {
            $fPrice = number_format((float)$this->getProductShippingFees((float)BT_GmcModuleTools::round($this->data->step->price_raw)), 2, '.', '');
        } else {
            if (in_array($this->data->c['id_product_attribute'], $this->aParams['freeShipping'][$this->data->p->id])) {
                $fPrice = number_format((float)0, 2, '.', '');
            } else {
                $fPrice = number_format((float)$this->getProductShippingFees((float)BT_GmcModuleTools::round($this->data->step->price_raw)), 2, '.', '');
            }
        }

        $this->data->step->shipping_fees = $fPrice . ' ' . $this->data->currency->iso_code;

        // get images
        $this->data->step->images = $this->getImages($this->data->p, $this->data->c['id_product_attribute']);

        // quantity
        // Do not export if the quantity is 0 for the combination and export out of stock setting is not On
        if (
            (int)$this->data->c['combo_quantity'] <= 0
            && (int)GMerchantCenter::$conf['GMC_EXPORT_OOS'] == 0
        ) {
            BT_GmcReporting::create()->set('_no_export_no_stock', array('productId' => $this->data->step->id_reporting));
            return false;
        }
        $this->data->step->quantity = (int)$this->data->c['combo_quantity'];

        // Manage GTIN code
        if (!empty(BT_GmcModuleTools::getGtin(GMerchantCenter::$conf['GMC_GTIN_PREF'], $this->data->c))) {
            $this->data->step->gtin = BT_GmcModuleTools::getGtin(GMerchantCenter::$conf['GMC_GTIN_PREF'], $this->data->c);
        } else {
            $this->data->step->gtin = BT_GmcModuleTools::getGtin(GMerchantCenter::$conf['GMC_GTIN_PREF'], (array)$this->data->p);
        }

        // Exclude without EAN
        if (
            GMerchantCenter::$conf['GMC_EXC_NO_EAN']
            && empty($this->data->step->gtin)
        ) {
            BT_GmcReporting::create()->set('_no_export_no_ean_upc', array('productId' => $this->data->step->id_reporting));
            return false;
        }

        // supplier reference
        $this->data->step->mpn = $this->getSupplierReference($this->data->p->id, $this->data->p->id_supplier, $this->data->p->supplier_reference, $this->data->p->reference, (int)$this->data->c['id_product_attribute'], $this->data->c['supplier_reference'], $this->data->c['reference']);

        // exclude if mpn is empty
        if (
            !empty(GMerchantCenter::$conf['GMC_EXC_NO_MREF'])
            && !GMerchantCenter::$conf['GMC_INC_ID_EXISTS']
            && empty($this->data->step->mpn)
        ) {
            BT_GmcReporting::create()->set('_no_export_no_supplier_ref', array('productId' => $this->data->step->id_reporting));
            return false;
        }

        // handle the specific price feature
        $this->data->step->specificPriceFrom = !empty($this->data->c['from']) ? $this->data->c['from'] : '0000-00-00 00:00:00';
        $this->data->step->specificPriceTo = !empty($this->data->c['to']) ? $this->data->c['to'] : '0000-00-00 00:00:00';

        $this->data->step->visibility = $this->data->p->visibility;

        // Use case for dimension
        if (!empty(GMerchantCenter::$conf['GMC_DIMENSION'])) {
            $aDataDimension = BT_GmcModuleTools::getDimension($this->data->p->width, $this->data->p->height, $this->data->p->depth);
            if (!empty($aDataDimension)) {
                $this->data->step->shipping_width =  $aDataDimension['shipping_width'];
                $this->data->step->shipping_height = $aDataDimension['shipping_height'];
                $this->data->step->shipping_length  = $aDataDimension['shipping_length'];
            }
        }

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
    public function formatProductName($iAdvancedProdName, $iAdvancedProdTitle, $sProdName, $sCatName, $sManufacturerName, $iLength, $iProdAttrId = null)
    {
        // get the combination attributes to format the product name
        $sProdName .= BT_GmcModuleTools::getProductCombinationName($iProdAttrId, $this->aParams['iLangId'], $this->aParams['iShopId']);

        // encode
        $sProdName = BT_GmcModuleTools::truncateProductTitle($iAdvancedProdName, $sProdName, $sCatName, $sManufacturerName, $iLength);
        $sProdName = BT_GmcModuleTools::formatProductTitle($sProdName, $iAdvancedProdTitle);

        return $sProdName;
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

        // get images of combination
        $aAttributeImages = $oProduct->getCombinationImages(GMerchantCenter::$iCurrentLang);

        if (!empty($aAttributeImages[$iProdAttributeId]) && is_array($aAttributeImages[$iProdAttributeId])) {
            $aImage = array('id_image' => $aAttributeImages[$iProdAttributeId][0]['id_image']);
        } else {
            $aImage = Product::getCover($oProduct->id);
        }

        // Additional images
        unset($aAttributeImages['id_image']);

        if (!empty($aAttributeImages[$iProdAttributeId]) && is_array($aAttributeImages)) {
            foreach ($aAttributeImages[$iProdAttributeId] as $sImg) {
                if ($iCounter <= _GMC_IMG_LIMIT) {
                    $aResultImages[] = array('id_image' => $sImg['id_image']);
                    $iCounter++;
                }
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
        // set  vars
        $sReturnRef = '';

        if (empty(GMerchantCenter::$bCompare1770)) {
            // detect the MPN type
            $sReturnRef = BT_GmcModuleDao::getProductSupplierReference($iProdId, $iSupplierId, $iProdAttributeId);
        } else {
            $oCombination = new Combination($iProdAttributeId);

            if (!empty($oCombination->mpn)) {
                $sReturnRef = $oCombination->mpn;
            } else {
                $oProduct = new Product($iProdId);
                $sReturnRef = $oProduct->mpn;
            }
        }

        return $sReturnRef;
    }
}
