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

abstract class BT_BaseXml
{
    /**
     * @var bool $bProductProcess : define if the product has well added
     */
    protected $bProductProcess = false;

    /**
     * @var array $aParams : array of params
     */
    protected $aParams = array();

    /**
     * @var obj $data : store currency / shipping / zone / carrier / product data into this obj as properties
     */
    protected $data = null;


    /**
     *
     * @param array $aParams
     */
    protected function __construct(array $aParams = null)
    {
        $this->aParams = $aParams;
        $this->data = new stdClass();
    }

    /**
     * load products combination
     *
     * @param int $iProductId
     * @return array
     */
    abstract public function hasCombination($iProductId);

    /**
     * build product XML tags
     *
     * @return array
     */
    abstract public function buildDetailProductXml();


    /**
     * get images of one product or one combination
     *
     * @param obj $oProduct
     * @param int $iProdAttributeId
     * @return array
     */
    abstract public function getImages(Product $oProduct, $iProdAttributeId = null);

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
    abstract public function getSupplierReference(
        $iProdId,
        $iSupplierId,
        $sSupplierRef = null,
        $sProductRef = null,
        $iProdAttributeId = null,
        $sCombiSupplierRef = null,
        $sCombiRef = null
    );

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
    abstract public function formatProductName(
        $iAdvancedProdName,
        $iAdvancedProdTitle,
        $sProdName,
        $sCatName,
        $sManufacturerName,
        $iLength,
        $iProdAttrId = null
    );


    /**
     * store into the matching object the product and combination
     *
     * @param obj $oData
     * @param obj $oProduct
     * @param array $aCombination
     * @return array
     */
    public function setProductData(&$oData, $oProduct, $aCombination)
    {
        $this->data = $oData;
        $this->data->p = $oProduct;
        $this->data->c = $aCombination;
    }

    /**
     * define if the current product has been processed or refused for some not requirements matching
     *
     * @return bool
     */
    public function hasProductProcessed()
    {
        return $this->bProductProcess;
    }

    /**
     * build common product XML tags
     *
     * @param obj $oProduct
     * @param array $aCombination
     * @return true
     */
    public function buildProductXml()
    {
        // reset the current step data obj
        $this->data->step = new stdClass();

        // define the product Id for reporting
        $this->data->step->attrId = !empty($this->data->c['id_product_attribute']) ? $this->data->c['id_product_attribute'] : 0;
        $this->data->step->id_reporting = $this->data->p->id . '_' . (!empty($this->data->c['id_product_attribute']) ? $this->data->c['id_product_attribute'] : 0);

        // check if there is an excluded products list
        if (!empty($this->aParams['excluded'])) {
            if ((isset($this->aParams['excluded'][$this->data->p->id])
                    && $this->data->step->attrId != 0
                    && in_array($this->data->step->attrId, $this->aParams['excluded'][$this->data->p->id]))
                || (isset($this->aParams['excluded'][$this->data->p->id])
                    && $this->data->step->attrId == 0
                    && in_array($this->data->step->attrId, $this->aParams['excluded'][$this->data->p->id]))
            ) {
                BT_GmcReporting::create()->set('excluded', array('productId' => $this->data->step->id_reporting));
                return false;
            }
        }

        // check qty , export type and the product name, available for order
        if (
            !isset($this->data->p->available_for_order)
            || (isset($this->data->p->available_for_order)
                && $this->data->p->available_for_order == 1)
        ) {

            $this->data->p->name = BT_GmcModuleTools::sanitizeProductProperty(
                $this->data->p->name,
                $this->aParams['iLangId']
            );

            // detect if the name is valid
            if (!empty($this->data->p->name)) {
                $export = true;

                // use case  - out of stock and we do not export out of stock products
                if (
                    $this->data->p->quantity <= 0
                    && GMerchantCenter::$conf['GMC_EXPORT_OOS'] == 0
                ) {
                    $export = false;
                }
                // use case - out of stock product and we authorize to export but only products authorized for orders
                if (
                    $this->data->p->quantity <= 0
                    && GMerchantCenter::$conf['GMC_EXPORT_OOS'] == 1
                    && GMerchantCenter::$conf['GMC_EXPORT_PROD_OOS_ORDER'] == 1
                    && isset($this->data->p->out_of_stock)
                    && $this->data->p->out_of_stock != 1
                ) {
                    $export = false;
                }

                // only in case of the export is authorizaed according to the OUT OF STOCK management
                if ($export) {
                    // get  the product category object
                    $this->data->step->category = new Category(
                        (int)($this->data->p->id_category_default),
                        (int)$this->aParams['iLangId']
                    );

                    // set the product ID
                    $this->data->step->id = $this->data->p->id;

                    // format product name
                    $this->data->step->name = $this->formatProductName(
                        GMerchantCenter::$conf['GMC_ADV_PRODUCT_NAME'],
                        GMerchantCenter::$conf['GMC_ADV_PROD_TITLE'],
                        $this->data->p->name,
                        $this->data->step->category->name,
                        $this->data->p->manufacturer_name,
                        _GMC_FEED_TITLE_LENGTH,
                        (!empty($this->data->c['id_product_attribute']) ? $this->data->c['id_product_attribute'] : null)
                    );

                    // use case export title with brands in suffix
                    if (
                        GMerchantCenter::$conf['GMC_ADV_PRODUCT_NAME'] != 0
                        && Tools::strlen($this->data->step->name) >= _GMC_FEED_TITLE_LENGTH
                    ) {
                        BT_GmcReporting::create()->set(
                            'title_length',
                            array('productId' => $this->data->step->id_reporting)
                        );
                    }

                    // Sanitize
                    $this->data->p->description_short = BT_GmcModuleTools::sanitizeProductProperty(
                        $this->data->p->description_short,
                        $this->aParams['iLangId']
                    );
                    $this->data->p->description = BT_GmcModuleTools::sanitizeProductProperty(
                        $this->data->p->description,
                        $this->aParams['iLangId']
                    );
                    $this->data->p->meta_description = BT_GmcModuleTools::sanitizeProductProperty(
                        $this->data->p->meta_description,
                        $this->aParams['iLangId']
                    );

                    // set product description
                    $this->data->step->desc = $this->getProductDesc(
                        $this->data->p->description_short,
                        $this->data->p->description,
                        $this->data->p->meta_description
                    );

                    // use case - reporting if product has no description as the merchant selected as type option
                    if (empty($this->data->step->desc)) {
                        BT_GmcReporting::create()->set(
                            'description',
                            array('productId' => $this->data->step->id_reporting)
                        );
                        return false;
                    }

                    // set product URL
                    $this->data->step->url = BT_GmcModuleTools::getProductLink($this->data->p,$this->aParams['iLangId']);

                    // use case - reporting if product has no valid URL
                    if (empty($this->data->step->url)) {
                        BT_GmcReporting::create()->set('link', array('productId' => $this->data->step->id_reporting));
                        return false;
                    }

                    $this->data->step->url_default = $this->data->step->url;

                    // format the current URL with currency or Google campaign parameters
                    if (!empty(GMerchantCenter::$conf['GMC_ADD_CURRENCY'])) {
                        $this->data->step->url .= (strpos(
                            $this->data->step->url,
                            '?'
                        ) !== false) ? '&SubmitCurrency=1&id_currency=' . (int)$this->data->currencyId : '?SubmitCurrency=1&id_currency=' . (int)$this->data->currencyId;
                    }
                    if (!empty(GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'])) {
                        $this->data->step->url .= (strpos(
                            $this->data->step->url,
                            '?'
                        ) !== false) ? '&utm_campaign=' . GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'] : '?utm_campaign=' . GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'];
                    }
                    if (!empty(GMerchantCenter::$conf['GMC_UTM_SOURCE'])) {
                        $this->data->step->url .= (strpos(
                            $this->data->step->url,
                            '?'
                        ) !== false) ? '&utm_source=' . GMerchantCenter::$conf['GMC_UTM_SOURCE'] : '?utm_source=' . GMerchantCenter::$conf['GMC_UTM_SOURCE'];
                    }
                    if (!empty(GMerchantCenter::$conf['GMC_UTM_MEDIUM'])) {
                        $this->data->step->url .= (strpos(
                            $this->data->step->url,
                            '?'
                        ) !== false) ? '&utm_medium=' . GMerchantCenter::$conf['GMC_UTM_MEDIUM'] : '?utm_medium=' . GMerchantCenter::$conf['GMC_UTM_MEDIUM'];
                    }

                    // set the product path
                    $this->data->step->path = $this->getProductPath(
                        $this->data->p->id_category_default,
                        $this->aParams['iLangId']
                    );

                    // get the condition
                    $this->data->step->condition = BT_GmcModuleTools::getProductCondition((!empty($this->data->p->condition) ? $this->data->p->condition : null));

                    // execute the detail part
                    if ($this->buildDetailProductXml()) {
                        // get the default image
                        $this->data->step->image_link = BT_GmcModuleTools::getProductImage(
                            $this->data->p,
                            (!empty(GMerchantCenter::$conf['GMC_IMG_SIZE']) ? GMerchantCenter::$conf['GMC_IMG_SIZE'] : null),
                            $this->data->step->images['image'],
                            GMerchantCenter::$conf['GMC_LINK']
                        );

                        // use case - reporting if product has no cover image
                        if (empty($this->data->step->image_link)) {
                            BT_GmcReporting::create()->set(
                                'image_link',
                                array('productId' => $this->data->step->id_reporting)
                            );
                            return false;
                        }

                        // get additional images
                        if (!empty($this->data->step->images['others']) && is_array($this->data->step->images['others'])) {
                            $this->data->step->additional_images = array();
                            foreach ($this->data->step->images['others'] as $aImage) {
                                $sExtraImgLink = BT_GmcModuleTools::getProductImage(
                                    $this->data->p,
                                    (!empty(GMerchantCenter::$conf['GMC_IMG_SIZE']) ? GMerchantCenter::$conf['GMC_IMG_SIZE'] : null),
                                    $aImage,
                                    GMerchantCenter::$conf['GMC_LINK']
                                );
                                if (!empty($sExtraImgLink)) {
                                    $this->data->step->additional_images[] = $sExtraImgLink;
                                }
                            }
                        }
                        // get Google Categories
                        $this->data->step->google_cat = BT_GmcModuleDao::getGoogleCategories(
                            $this->aParams['iShopId'],
                            $this->data->p->id_category_default,
                            $GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES'][$this->aParams['sLangIso']][$this->aParams['sCountryIso']]['taxonomy']
                        );

                        //get all product categories
                        $aProductCategories = $this->data->p->getCategories($this->data->p->id);

                        $this->data->step->google_tags = BT_GmcModuleDao::getTagsForXml(
                            $this->data->p->id,
                            $aProductCategories,
                            $this->data->p->id_manufacturer,
                            $this->data->p->id_supplier
                        );

                        // get features by category
                        $this->data->step->features = BT_GmcModuleDao::getFeaturesByCategory(
                            $this->data->p->id_category_default,
                            GMerchantCenter::$iShopId
                        );

                        // get color options
                        $this->data->step->colors = $this->getColorOptions(
                            $this->data->p->id,
                            (int)$this->aParams['iLangId'],
                            (!empty($this->data->c['id_product_attribute']) ? $this->data->c['id_product_attribute'] : 0)
                        );

                        // get size options
                        $this->data->step->sizes = $this->getSizeOptions(
                            $this->data->p->id,
                            (int)$this->aParams['iLangId'],
                            (!empty($this->data->c['id_product_attribute']) ? $this->data->c['id_product_attribute'] : 0)
                        );

                        // get material options
                        if (
                            !empty(GMerchantCenter::$conf['GMC_INC_MATER'])
                            && !empty($this->data->step->features['material'])
                        ) {
                            $this->data->step->material = $this->getFeaturesOptions(
                                $this->data->p->id,
                                $this->data->step->features['material'],
                                (int)$this->aParams['iLangId']
                            );
                        }

                        // get pattern options
                        if (
                            !empty(GMerchantCenter::$conf['GMC_INC_PATT'])
                            && !empty($this->data->step->features['pattern'])
                        ) {
                            $this->data->step->pattern = $this->getFeaturesOptions(
                                $this->data->p->id,
                                $this->data->step->features['pattern'],
                                (int)$this->aParams['iLangId']
                            );
                        }
                        return true;
                    }
                } // use case - reporting if product was excluded due to no_stock
                else {
                    BT_GmcReporting::create()->set(
                        '_no_export_no_stock',
                        array('productId' => $this->data->step->id_reporting)
                    );
                }
            } // use case - reporting if product was excluded due to the empty name
            else {
                BT_GmcReporting::create()->set(
                    '_no_product_name',
                    array('productId' => $this->data->step->id_reporting)
                );
            }
        } // use case - reporting if product isn't available for order
        else {
            BT_GmcReporting::create()->set(
                '_no_available_for_order',
                array('productId' => $this->data->step->id_reporting)
            );
        }
        return false;
    }

    /**
     * build XML tags from the current stored data
     *
     * @return string
     */
    public function buildXmlTags()
    {
        // set vars
        $sContent = '';
        $aReporting = array();

        $this->bProductProcess = false;

        // check if data are ok - 4 data are mandatory to fill the product out
        if (
            !empty($this->data->step)
            && !empty($this->data->step->name)
            && !empty($this->data->step->desc)
            && !empty($this->data->step->url)
            && !empty($this->data->step->image_link)
            && $this->data->step->visibility != 'none'
        ) {

            $sContent .= "\t" . '<item>' . "\n";

            if (empty(GMerchantCenter::$conf['GMC_SIMPLE_PROD_ID'])) {
                $sContent .= "\t\t" . '<g:id>' . Tools::strtoupper(GMerchantCenter::$conf['GMERCHANTCENTER_ID_PREFIX']) . $this->aParams['sCountryIso'] . $this->data->step->id . '</g:id>' . "\n";
            } else {
                $sContent .= "\t\t" . '<g:id>' . $this->data->step->id . '</g:id>' . "\n";
            }

            // ****** PRODUCT NAME ******
            if (!empty($this->data->step->name)) {
                $sContent .= "\t\t" . '<title><![CDATA[' . BT_GmcModuleTools::cleanUp($this->data->step->name) . ']]></title>' . "\n";
            } else {
                $aReporting[] = 'title';
            }

            // ****** DESCRIPTION ******
            if (!empty($this->data->step->desc)) {
                $sContent .= "\t\t" . '<description><![CDATA[' . $this->data->step->desc . ']]></description>' . "\n";
            } else {
                $aReporting[] = 'description';
            }

            // ****** PRODUCT LINK ******
            if (!empty($this->data->step->url)) {
                $sContent .= "\t\t" . '<link><![CDATA[' . $this->data->step->url . ']]></link>' . "\n";
            } else {
                $aReporting[] = 'link';
            }

            // ****** ADS REDIRECT to handle utm_content={campaignid ******
            if (!empty(GMerchantCenter::$conf['GMC_UTM_CONTENT'])) {
                $sCampaignIdUrl = $this->data->step->url .= (strpos($this->data->step->url, '?') !== false) ? '&utm_content={campaignid}' : '?utm_content={campaignid}';
                $sContent .= "\t\t" . '<g:ads_redirect><![CDATA[' .  $sCampaignIdUrl . ']]></g:ads_redirect>' . "\n";
            }

            // ****** IMAGE LINK ******
            if (!empty($this->data->step->image_link)) {
                $sContent .= "\t\t" . '<g:image_link><![CDATA[' . $this->data->step->image_link . ']]></g:image_link>' . "\n";
            } else {
                $aReporting[] = 'image_link';
            }

            // ****** PRODUCT CONDITION ******
            $sContent .= "\t\t" . '<g:condition>' . $this->data->step->condition . '</g:condition>' . "\n";

            // ****** ADDITIONAL IMAGES ******
            if (!empty(GMerchantCenter::$conf['GMC_ADD_IMAGES'])) {
                if (!empty($this->data->step->additional_images)) {
                    foreach ($this->data->step->additional_images as $sImgLink) {
                        $sContent .= "\t\t" . '<g:additional_image_link><![CDATA[' . $sImgLink . ']]></g:additional_image_link>' . "\n";
                    }
                }
            }

            // ****** PRODUCT TYPE ******
            if (!empty($this->data->step->path)) {
                $sContent .= "\t\t" . '<g:product_type><![CDATA[' . $this->data->step->path . ']]></g:product_type>' . "\n";
            } else {
                $aReporting[] = 'product_type';
            }

            // ****** GOOGLE MATCHING CATEGORY ******
            if (!empty($this->data->step->google_cat['txt_taxonomy'])) {
                $sContent .= "\t\t" . '<g:google_product_category><![CDATA[' . $this->data->step->google_cat['txt_taxonomy'] . ']]></g:google_product_category>' . "\n";
            } else {
                $aReporting[] = 'google_product_category';
            }

            // ****** GOOGLE CUSTOM LABELS ******
            if (!empty($this->data->step->google_tags['custom_label'])) {
                $iCounter = 0;
                foreach ($this->data->step->google_tags['custom_label'] as $sLabel) {
                    if ($iCounter < _GMC_CUSTOM_LABEL_LIMIT) {
                        $sContent .= "\t\t" . '<g:custom_label_' . $iCounter . '><![CDATA[' . $sLabel . ']]></g:custom_label_' . $iCounter . '>' . "\n";
                        $iCounter++;
                    }
                }
            }

            // ****** PRODUCT AVAILABILITY ******
            if (GMerchantCenter::$conf['GMC_INC_STOCK'] == 2) {
                if (empty($this->data->step->availabilty_date)) {
                    if ($this->data->step->quantity > 0) {
                        $sContent .= "\t\t" . '<g:sell_on_google_quantity>' . (int) $this->data->step->quantity . '</g:sell_on_google_quantity>' . "\n";
                        $sContent .= "\t\t" . '<g:availability>in stock</g:availability>' . "\n"; 
                    } else {
                        $sContent .= "\t\t" . '<g:availability>in stock</g:availability>' . "\n"; 
                    }
                } else {
                    if ($this->data->step->quantity > 0) {
                        $sContent .= "\t\t" . '<g:sell_on_google_quantity>' . (int) $this->data->step->quantity . '</g:sell_on_google_quantity>' . "\n";
                        $sContent .= "\t\t" . '<g:availability>in stock</g:availability>' . "\n";
                    } else {
                        if ($this->data->p->out_of_stock == 0 || Configuration::get('PS_ORDER_OUT_OF_STOCK') == 0) {
                            $sContent .= "\t\t" . '<g:availability>preorder</g:availability>' . "\n";
                        } else {
                            $sContent .= "\t\t" . '<g:availability>backorder</g:availability>' . "\n";
                        }

                        $sContent .= "\t\t" . '<g:availability_date>'  . BT_GmcModuleTools::formatDateISO8601($this->data->step->availabilty_date) . '</g:availability_date>' . "\n";
                    }
                }
            } else {
                if (empty($this->data->step->availabilty_date)) {
                    if ($this->data->step->quantity > 0) {
                        $sContent .= "\t\t" . '<g:sell_on_google_quantity>' . (int) $this->data->step->quantity . '</g:sell_on_google_quantity>' . "\n";
                        $sContent .= "\t\t" . '<g:availability>in stock</g:availability>' . "\n";
                    } else {
                        $sContent .= "\t\t" . '<g:availability>out of stock</g:availability>' . "\n";
                    }
                } else {
                    if ($this->data->step->quantity > 0) {
                        $sContent .= "\t\t" . '<g:sell_on_google_quantity>' . (int) $this->data->step->quantity . '</g:sell_on_google_quantity>' . "\n";
                        $sContent .= "\t\t" . '<g:availability>in stock</g:availability>' . "\n";
                    } else {
                         if ($this->data->p->out_of_stock == 0 || Configuration::get('PS_ORDER_OUT_OF_STOCK') == 0) {
                            $sContent .= "\t\t" . '<g:availability>preorder</g:availability>' . "\n";
                        } else {
                            $sContent .= "\t\t" . '<g:availability>backorder</g:availability>' . "\n";
                        }

                        $sContent .= "\t\t" . '<g:availability_date>'  . BT_GmcModuleTools::formatDateISO8601($this->data->step->availabilty_date) . '</g:availability_date>' . "\n";
                    }
                }
            }

            // ****** PRODUCT PRICES ******
            if ($this->data->step->price_raw < $this->data->step->price_raw_no_discount) {
                $sContent .= "\t\t" . '<g:price>' . $this->data->step->price_no_discount . '</g:price>' . "\n"
                    . "\t\t" . '<g:sale_price>' . $this->data->step->price . '</g:sale_price>' . "\n";
                if (
                    $this->data->step->specificPriceFrom != '0000-00-00 00:00:00'
                    && ($this->data->step->specificPriceTo) != '0000-00-00 00:00:00'
                ) {
                    $sContent .= "\t\t" . '<g:sale_price_effective_date>' . BT_GmcModuleTools::formatDateISO8601($this->data->step->specificPriceFrom) . '/' . BT_GmcModuleTools::formatDateISO8601($this->data->step->specificPriceTo) . '</g:sale_price_effective_date>' . "\n";
                }
            } else {
                $sContent .= "\t\t" . '<g:price>' . $this->data->step->price . '</g:price>' . "\n";
            }

            // ****** UNIQUE PRODUCT IDENTIFIERS ******
            // ****** GTIN - EAN13 AND UPC ******
            if (!empty($this->data->step->gtin)) {
                $sContent .= "\t\t" . '<g:gtin>' . $this->data->step->gtin . '</g:gtin>' . "\n";
            } else {
                $aReporting[] = 'gtin';
            }

            // ****** MANUFACTURER ******
            if (!empty($this->data->p->manufacturer_name)) {
                $sContent .= "\t\t" . '<g:brand><![CDATA[' . BT_GmcModuleTools::cleanUp($this->data->p->manufacturer_name) . ']]></g:brand>' . "\n";
            } else {
                $aReporting[] = 'brand';
            }

            // ****** MPN ******
            if (!empty($this->data->step->mpn)) {
                $sContent .= "\t\t" . '<g:mpn><![CDATA[' . $this->data->step->mpn . ']]></g:mpn>' . "\n";
            } elseif (empty(GMerchantCenter::$conf['GMC_INC_ID_EXISTS'])) {
                $aReporting[] = 'mpn';
            }

            // ****** IDENTIFIER EXISTS ******
            if (
                empty($this->data->step->gtin)
                && (empty($this->data->step->mpn)
                    || empty($this->data->p->manufacturer_name) || !empty(GMerchantCenter::$conf['GMC_FORCE_IDENTIFIER']))
            ) {
                $sContent .= "\t\t" . '<g:identifier_exists>FALSE</g:identifier_exists>' . "\n";
            }

            // ****** APPAREL PRODUCTS ******
            // ****** TAG ADULT ******
            if (
                !empty($this->data->step->features['adult'])
                && !empty(GMerchantCenter::$conf['GMC_INC_TAG_ADULT'])
            ) {
                $sContent .= "\t\t" . '<g:adult><![CDATA[' . Tools::stripslashes(Tools::strtoupper($this->data->step->features['adult'])) . ']]></g:adult>' . "\n";
            }

            // ****** TAG GENDER ******
            if (
                !empty($this->data->step->features['gender'])
                && !empty(GMerchantCenter::$conf['GMC_INC_GEND'])
            ) {
                $sContent .= "\t\t" . '<g:gender><![CDATA[' . Tools::stripslashes($this->data->step->features['gender']) . ']]></g:gender>' . "\n";
            } elseif (!empty(GMerchantCenter::$conf['GMC_INC_GEND'])) {
                $aReporting[] = 'gender';
            }

            // ****** TAG AGE GROUP ******
            if (
                !empty($this->data->step->features['agegroup'])
                && !empty(GMerchantCenter::$conf['GMC_INC_AGE'])
            ) {
                $sContent .= "\t\t" . '<g:age_group><![CDATA[' . Tools::stripslashes($this->data->step->features['agegroup']) . ']]></g:age_group>' . "\n";
            } elseif (!empty(GMerchantCenter::$conf['GMC_INC_AGE'])) {
                $aReporting[] = 'age_group';
            }

            // ****** TAG SIZE TYPE ******
            if (
                !empty($this->data->step->features['sizeType'])
                && !empty(GMerchantCenter::$conf['GMC_SIZE_TYPE'])
            ) {
                $sContent .= "\t\t" . '<g:size_type><![CDATA[' . Tools::stripslashes($this->data->step->features['sizeType']) . ']]></g:size_type>' . "\n";
            } elseif (!empty(GMerchantCenter::$conf['GMC_SIZE_TYPE'])) {
                $aReporting[] = 'sizeType';
            }

            // ****** TAG SIZE TYPE ******
            if (
                !empty($this->data->step->features['sizeSystem'])
                && !empty(GMerchantCenter::$conf['GMC_SIZE_SYSTEM'])
            ) {
                $sContent .= "\t\t" . '<g:size_system><![CDATA[' . Tools::stripslashes($this->data->step->features['sizeSystem']) . ']]></g:size_system>' . "\n";
            } elseif (!empty(GMerchantCenter::$conf['GMC_SIZE_SYSTEM'])) {
                $aReporting[] = 'sizeSystem';
            }

            // ****** TAG COLOR ******
            if (
                !empty($this->data->step->colors)
                && is_array($this->data->step->colors)
            ) {
                foreach ($this->data->step->colors as $aColor) {
                    $sContent .= "\t\t" . '<g:color><![CDATA[' . Tools::stripslashes($aColor['name']) . ']]></g:color>' . "\n";
                }
            } elseif (!empty(GMerchantCenter::$conf['GMC_INC_COLOR'])) {
                $aReporting[] = 'color';
            }

            // ****** TAG SIZE ******
            if (
                !empty($this->data->step->sizes)
                && is_array($this->data->step->sizes)
            ) {
                foreach ($this->data->step->sizes as $aSize) {
                    $sContent .= "\t\t" . '<g:size><![CDATA[' . Tools::stripslashes($aSize['name']) . ']]></g:size>' . "\n";
                }
            } elseif (!empty(GMerchantCenter::$conf['GMC_INC_SIZE'])) {
                $aReporting[] = 'size';
            }

            // handle the default pack from PS
            if (
                !empty($this->data->p->cache_is_pack)
                || (GMerchantCenter::$bAdvancedPack
                    && AdvancedPack::isValidPack($this->data->p->id))
            ) {
                $sContent .= "\t\t" . '<g:is_bundle>TRUE</g:is_bundle>' . "\n";
            }

            // ****** VARIANTS PRODUCTS ******
            // ****** TAG MATERIAL ******
            if (!empty($this->data->step->material)) {
                $sContent .= "\t\t" . '<g:material><![CDATA[' . $this->data->step->material . ']]></g:material>' . "\n";
            } elseif (!empty(GMerchantCenter::$conf['GMC_INC_MATER'])) {
                $aReporting[] = 'material';
            }

            // Use case for the excluded destination value
            if (
                !empty($this->data->step->features['excluded_destination'])
                && !empty(GMerchantCenter::$conf['GMC_EXCLUDED_DEST'])
            ) {
                // Transform excluded destination to an array
                $aExcludedDest = explode(' ', $this->data->step->features['excluded_destination']);

                // Use case if is array we can handle the tag
                if (is_array($aExcludedDest)) {
                    // For each exclusion destination we set the tag
                    foreach ($aExcludedDest as $sDestination) {
                        $sContent .= "\t\t" . '<g:excluded_destination><![CDATA[' . Tools::stripslashes($GLOBALS['GMC_EXCLUDED_DEST_VALUE'][$sDestination]) . ']]></g:excluded_destination>' . "\n";
                    }
                }
            }

            //Use case for the excluded country value
            if (
                !empty($this->data->step->features['excluded_country'])
                && !empty(GMerchantCenter::$conf['GMC_EXCLUDED_COUNTRY'])
            ) {
                // Transform excluded country to an array
                $aExcludedCountry = explode(' ', $this->data->step->features['excluded_country']);

                // Use case if is array we can handle the tag
                if (is_array($aExcludedCountry)) {
                    // For each exclusion country we set the tag
                    foreach ($aExcludedCountry as $sCountry) {
                        $sContent .= "\t\t" . '<g:shopping_ads_excluded_country><![CDATA[' . Tools::stripslashes($sCountry) . ']]></g:shopping_ads_excluded_country>' . "\n";
                    }
                }
            }

            // ****** TAG PATTERN ******
            if (!empty($this->data->step->pattern)) {
                $sContent .= "\t\t" . '<g:pattern><![CDATA[' . $this->data->step->pattern . ']]></g:pattern>' . "\n";
            } elseif (!empty(GMerchantCenter::$conf['GMC_INC_PATT'])) {
                $aReporting[] = 'pattern';
            }

            // ****** ITEM GROUP ID ******
            if (!empty($this->data->step->id_no_combo)) {
                if (empty(GMerchantCenter::$conf['GMC_SIMPLE_PROD_ID'])) {
                    $sContent .= "\t\t" . '<g:item_group_id>' . Tools::strtoupper(GMerchantCenter::$conf['GMERCHANTCENTER_ID_PREFIX']) . $this->aParams['sCountryIso'] . '-' . $this->data->step->id_no_combo . '</g:item_group_id>' . "\n";
                } else {
                    $sContent .= "\t\t" . '<g:item_group_id>' . $this->data->step->id_no_combo . '</g:item_group_id>' . "\n";
                }
            }

            //ADD PRODUCT ID WITH SHIPPING COST

            $ProductShippingWeight = '0.10';
            $ProductsWithShipping = ['28027','28030','28031','28032','28033','28069','28070','28072','28073','44535','44536','44537','44538','44539','44540','44541','44542','1606487','1624717','1624860','1634120','32225','32487','32488','34753','34755','34843','34851','34854','34855','34888','34903','34906','34910','34946','34965','35138','35145','35149','35231','35296','35303','26429','26430','26431','26432','26433','26434','26435','26436','26437','26438','26440','26441','26442','26443','26444','26446','26447','26448','26449','26451','26112','26127','26128','26129','26130','26135','26151','26152','26153','26155','26156','26157','23954','23955','23956','23957','23958','23959','23960','23963','23964','23965','23966','23967','23969','23970','23971','23972','23973','23974','23975','23980','23981','23982','23983','23984','23985','23871','20704'];
            if (in_array($this->data->step->id , $ProductsWithShipping)){
                $sContent .= "\t\t" . '<g:shipping_label>' . 'Shipping' . '</g:shipping_label>' . "\n";
            }elseif  ($this->data->step->weight == $ProductShippingWeight){
                $sContent .= "\t\t" . '<g:shipping_label>' . 'Shipping ' . '</g:shipping_label>' . "\n";
            }else{
                $sContent .= "\t\t" . '<g:shipping_label>' .'Free Shipping'. '</g:shipping_label>' . "\n";

            }

            

            // ****** TAX AND SHIPPING ******
            $sWeightUnit = Configuration::get('PS_WEIGHT_UNIT');
            if (!empty($this->data->step->weight) && !empty($sWeightUnit)) {
                if (in_array(Tools::strtolower($sWeightUnit), $GLOBALS[_GMC_MODULE_NAME . '_WEIGHT_UNITS'])) {
                    $sContent .= "\t\t" . '<g:shipping_weight>' . number_format($this->data->step->weight, 2, '.', '') . ' ' . Tools::strtolower($sWeightUnit) . '</g:shipping_weight>' . "\n";
                } else {
                    $aReporting[] = 'shipping_weight';
                }
            }

            if (!empty(GMerchantCenter::$conf['GMC_DIMENSION'])) {
                if (!empty( $this->data->step->shipping_width ) && !empty( $this->data->step->shipping_height ) && !empty( $this->data->step->shipping_length )) {
                    $sContent .= "\t\t" . '<g:shipping_width><![CDATA[' . $this->data->step->shipping_width . ']]></g:shipping_width>' . "\n";
                    $sContent .= "\t\t" . '<g:shipping_height><![CDATA[' . $this->data->step->shipping_height . ']]></g:shipping_height>' . "\n";
                    $sContent .= "\t\t" . '<g:shipping_length><![CDATA[' . $this->data->step->shipping_length . ']]></g:shipping_length>' . "\n";
                }
            }

            if (!empty(GMerchantCenter::$conf['GMC_SHIPPING_USE'])) {
                // check if there is an free shipping products list
                $sContent .= "\t\t" . '<g:shipping>' . "\n"
                    . "\t\t\t" . '<g:country>' . $this->aParams['sCountryIso'] . '</g:country>' . "\n"
                    . "\t\t\t" . '<g:price>' . $this->data->step->shipping_fees . '</g:price>' . "\n"
                    . "\t\t" . '</g:shipping>' . "\n";
            }

            $sContent .= "\t" . '</item>' . "\n";

            $this->bProductProcess = true;
        } else {
            $aReporting[] = '_no_required_data';
        }

        // execute the reporting
        if (!empty($aReporting)) {
            foreach ($aReporting as $sLabel) {
                BT_GmcReporting::create()->set($sLabel, array('productId' => $this->data->step->id_reporting));
            }
        }

        return $sContent;
    }

    /**
     * returns the product path according to the category ID
     *
     * @param int $iProdCatId
     * @param int $iLangId
     * @return string
     */
    public function getProductPath($iProdCatId, $iLangId)
    {
        if (is_string(GMerchantCenter::$conf['GMC_HOME_CAT'])) {
            GMerchantCenter::$conf['GMC_HOME_CAT'] = unserialize(GMerchantCenter::$conf['GMC_HOME_CAT']);
        }

        if (
            $iProdCatId == GMerchantCenter::$conf['GMC_HOME_CAT_ID']
            && !empty(GMerchantCenter::$conf['GMC_HOME_CAT'][$iLangId])
        ) {
            $sPath = Tools::stripslashes(GMerchantCenter::$conf['GMC_HOME_CAT'][$iLangId]);
        } else {
            $sPath = BT_GmcModuleTools::getProductPath((int)$iProdCatId, (int)$iLangId, '', false);
        }

        return $sPath;
    }

    /**
     * load products from DAO
     *
     * @param float $fProductPrice
     * @return float
     */
    public function getProductShippingFees($fProductPrice)
    {
        // set vars
        $fShippingFees = (float)0;
        $bProcess = true;

        // Free shipping on price ?
        if (((float)$this->data->shippingConfig['PS_SHIPPING_FREE_PRICE'] > 0)
            && ((float)$fProductPrice >= (float)$this->data->shippingConfig['PS_SHIPPING_FREE_PRICE'])
        ) {
            $bProcess = false;
        }
        // Free shipping on weight ?
        if (((float)$this->data->shippingConfig['PS_SHIPPING_FREE_WEIGHT'] > 0)
            && ((float)$this->data->step->weight >= (float)$this->data->shippingConfig['PS_SHIPPING_FREE_WEIGHT'])
        ) {
            $bProcess = false;
        }

        // only in case of not free shipping weight or price
        if ($bProcess && is_a($this->data->currentCarrier, 'Carrier')) {
            // Get shipping method - Version 1.4 / 1.5
            if (method_exists('Carrier', 'getShippingMethod')) {
                $sShippingMethod = ($this->data->currentCarrier->getShippingMethod() == Carrier::SHIPPING_METHOD_WEIGHT) ? 'weight' : 'price';
            } else {
                $sShippingMethod = $this->data->shippingConfig['PS_SHIPPING_METHOD'] ? 'weight' : 'price';
            }

            // Get main shipping fee
            if ($sShippingMethod == 'weight') {
                $fShippingFees += $this->data->currentCarrier->getDeliveryPriceByWeight(
                    $this->data->step->weight,
                    $this->data->currentZone->id
                );
            } else {
                $fShippingFees += $this->data->currentCarrier->getDeliveryPriceByPrice(
                    $fProductPrice,
                    $this->data->currentZone->id
                );
            }

            // Add product specific shipping fee for 1.4 / 1.5 only
            if (empty($this->data->currentCarrier->is_free)) {
                $fShippingFees += (float)BT_GmcModuleDao::getAdditionalShippingCost(
                    $this->data->p->id,
                    $this->aParams['iShopId']
                );
            }

            // Add handling fees if applicable
            if (
                !empty($this->data->shippingConfig['PS_SHIPPING_HANDLING'])
                && !empty($this->data->currentCarrier->shipping_handling)
            ) {
                $fShippingFees += (float)$this->data->shippingConfig['PS_SHIPPING_HANDLING'];
            }

            // Apply tax
            // Get tax rate - Version 1.4 / 1.5
            if (method_exists('Tax', 'getCarrierTaxRate')) {
                $fCarrierTax = Tax::getCarrierTaxRate((int)$this->data->currentCarrier->id);
            } else {
                $fCarrierTax = BT_GmcModuleDao::getCarrierTaxRate($this->data->currentCarrier->id);
            }
            $fShippingFees *= (1 + ($fCarrierTax / 100));

            // Covert to correct currency and format
            $fShippingFees = Tools::convertPrice($fShippingFees, $this->data->currency);
            $fShippingFees = number_format((float)($fShippingFees), 2, '.', '') . $this->data->currency->iso_code;
        }

        return $fShippingFees;
    }

    /**
     * returns a cleaned desc string
     *
     * @param int $iProdCatId
     * @param int $iLangId
     * @return string
     */
    public function getProductDesc($sShortDesc, $sLongDesc, $sMetaDesc)
    {
        // set product description
        switch (GMerchantCenter::$conf['GMC_P_DESCR_TYPE']) {
            case 1:
                $sDesc = $sShortDesc;
                break;
            case 2:
                $sDesc = $sLongDesc;
                break;
            case 3:
                $sDesc = $sShortDesc . '<br />' . $sLongDesc;
                break;
            case 4:
                $sDesc = $sMetaDesc;
                break;
            default:
                $sDesc = $sLongDesc;
                break;
        }
        return function_exists('mb_substr') ? mb_substr(
            BT_GmcModuleTools::cleanUp($sDesc),
            0,
            4999
        ) : Tools::substr(BT_GmcModuleTools::cleanUp($sDesc), 0, 4999);
    }


    /**
     * returns attributes and features
     *
     * @param int $iProdId
     * @param int $iLangId
     * @param int $iProdAttrId
     * @return array
     */
    public function getColorOptions($iProdId, $iLangId, $iProdAttrId = 0)
    {
        // set
        $aColors = array();

        if (!empty(GMerchantCenter::$conf['GMC_INC_COLOR'])) {
            if (!empty(GMerchantCenter::$conf['GMC_COLOR_OPT']['attribute'])) {
                $sAttributes = implode(',', GMerchantCenter::$conf['GMC_COLOR_OPT']['attribute']);
            }
            if (!empty(GMerchantCenter::$conf['GMC_COLOR_OPT']['feature'])) {
                $iFeature = implode(',', GMerchantCenter::$conf['GMC_COLOR_OPT']['feature']);
            }
            if (!empty($sAttributes)) {
                $aColors = BT_GmcModuleDao::getProductAttribute(
                    (int)$this->data->p->id,
                    $sAttributes,
                    (int)$iLangId,
                    (int)$iProdAttrId
                );
            }

            // use case - feature selected and not empty
            if (!empty($iFeature)) {
                $sFeature = BT_GmcModuleDao::getProductFeature((int)$this->data->p->id, (int)$iFeature, (int)$iLangId);

                if (!empty($sFeature)) {
                    $aColors[] = array('name' => $sFeature);
                }
            }
        }

        return $aColors;
    }

    /**
     * returns attributes and features
     *
     * @param int $iProdId
     * @param int $iLangId
     * @param int $iProdAttrId
     * @return array
     */
    public function getSizeOptions($iProdId, $iLangId, $iProdAttrId = 0)
    {
        // set
        $aSize = array();

        if (!empty(GMerchantCenter::$conf['GMC_SIZE_OPT'])) {
            if (!empty(GMerchantCenter::$conf['GMC_SIZE_OPT']['attribute'])) {
                $sAttributes = implode(',', GMerchantCenter::$conf['GMC_SIZE_OPT']['attribute']);
            }
            if (!empty(GMerchantCenter::$conf['GMC_SIZE_OPT']['feature'])) {
                $iFeature = implode(',', GMerchantCenter::$conf['GMC_SIZE_OPT']['feature']);
            }
            if (!empty($sAttributes)) {
                $aSize = BT_GmcModuleDao::getProductAttribute(
                    (int)$this->data->p->id,
                    $sAttributes,
                    (int)$iLangId,
                    (int)$iProdAttrId
                );
            }

            // use case - feature selected and not empty
            if (!empty($iFeature)) {
                $sFeature = BT_GmcModuleDao::getProductFeature((int)$this->data->p->id, (int)$iFeature, (int)$iLangId);

                if (!empty($sFeature)) {
                    $aSize[] = array('name' => $sFeature);
                }
            }
        }

        return $aSize;
    }

    /**
     * features for material or pattern
     *
     * @param int $iProdId
     * @param int $iFeatureId
     * @param int $iLangId
     * @return string
     */
    public function getFeaturesOptions($iProdId, $iFeatureId, $iLangId)
    {
        // set
        $sFeatureVal = '';

        $aFeatureProduct = Product::getFeaturesStatic($iProdId);

        if (!empty($aFeatureProduct) && is_array($aFeatureProduct)) {
            foreach ($aFeatureProduct as $aFeature) {
                if ($aFeature['id_feature'] == $iFeatureId) {
                    $aFeatureValues = FeatureValue::getFeatureValueLang((int)$aFeature['id_feature_value']);

                    foreach ($aFeatureValues as $aFeatureVal) {
                        if ($aFeatureVal['id_lang'] == $iLangId) {

                            //Use case for ps 1.7.3.0
                            if (empty(GMerchantCenter::$bCompare1730)) {
                                $sFeatureVal = $aFeatureVal['value'];
                            } else {
                                $sFeatureVal .= $aFeatureVal['value'] . ' ';
                            }
                        }
                    }
                }
            }
        }

        return $sFeatureVal;
    }
}
