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

class BT_AdminDisplay implements BT_IAdmin
{
    /**
     * display all configured data admin tabs
     *
     * @param string $sType => define which method to execute
     * @param array $aParam
     * @return array
     */
    public function run($sType, array $aParam = null)
    {
        // set variables
        $aDisplayData = array();

        if (empty($sType)) {
            $sType = 'tabs';
        }

        switch ($sType) {
            case 'tabs': // use case - display first page with all tabs
            case 'stepPopup': // use case - update stepPopup settings
            case 'basics': // use case - display basics settings page
            case 'feed': // use case - display feed settings page
            case 'google': // use case - display google settings page
            case 'googleCategories': // use case - display google categories settings page
            case 'customLabel': // use case - display google custom label settings popup
            case 'autocomplete': // use case - display autocomplete for google categories
            case 'feedList': // use case - display feed list settings page
            case 'reporting': // use case - display reporting settings page
            case 'reportingBox': // use case - display reporting fancybox
            case 'searchProduct': // use case - handle products autocomplete
                // include
                require_once(_GMC_PATH_LIB . 'module-dao_class.php');

                // execute match function
                $aDisplayData = call_user_func_array(array($this, 'display' . ucfirst($sType)), array($aParam));
                break;
            case 'tag': // use case - display adult tag settings page
                // include
                require_once(_GMC_PATH_LIB . 'module-dao_class.php');

                // execute match function
                $aDisplayData = call_user_func_array(array($this, 'displayAdvancedTagCategory'), array($aParam));
                break;
            default:
                break;
        }
        // use case - generic assign
        if (!empty($aDisplayData)) {
            $aDisplayInfo['assign']['bMultiShop'] = BT_GmcModuleTools::checkGroupMultiShop();
            $aDisplayData['assign'] = array_merge($aDisplayData['assign'], $this->assign());
        }

        return $aDisplayData;
    }

    /**
     * assigns transverse data
     *
     * @return array
     */
    private function assign()
    {
        // set smarty variables
        $aAssign = array(
            'sURI' => BT_GmcModuleTools::truncateUri(array('&sAction')),
            'sCtrlParamName' => _GMC_PARAM_CTRL_NAME,
            'sController' => _GMC_ADMIN_CTRL,
            'aQueryParams' => $GLOBALS[_GMC_MODULE_NAME . '_REQUEST_PARAMS'],
            'sDisplay' => Tools::getValue('sDisplay'),
            'iCurrentLang' => intval(GMerchantCenter::$iCurrentLang),
            'sCurrentLang' => GMerchantCenter::$sCurrentLang,
            'sFaqLang' => (GMerchantCenter::$sCurrentLang == 'fr' ? 'fr' : 'en'),
            'sCurrentIso' => Language::getIsoById(GMerchantCenter::$iCurrentLang),
            'sTs' => time(),
            'bAjaxMode' => (GMerchantCenter::$sQueryMode == 'xhr' ? true : false),
            'bCompare16' => GMerchantCenter::$bCompare16,
            'bCompare17' => GMerchantCenter::$bCompare17,
            'bPsVersion1606' => version_compare(_PS_VERSION_, '1.6.0.6', '>='),
            'sLoadingImg' => _GMC_URL_IMG . 'admin/' . _GMC_LOADER_GIF,
            'sBigLoadingImg' => _GMC_URL_IMG . 'admin/' . _GMC_LOADER_GIF,
            'sHeaderInclude' => BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_HEADER),
            'sErrorInclude' => BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_ERROR),
            'sConfirmInclude' => BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_CONFIRM),
            'bConfigureStep1' => GMerchantCenter::$conf['GMC_CONF_STEP_1'],
            'bConfigureStep2' => GMerchantCenter::$conf['GMC_CONF_STEP_2'],
            'bConfigureStep3' => GMerchantCenter::$conf['GMC_CONF_STEP_3'],
        );

        return $aAssign;
    }

    /**
     * displays admin's first page with all tabs
     *
     * @param array $aPost
     * @return array
     */
    private function displayTabs(array $aPost = null)
    {
        $iSupportToUse = _GMC_SUPPORT_BT;

        // set smarty variables
        $aAssign = array(
            'sDocUri' => _MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/',
            'sDocName' => 'readme_' . ((GMerchantCenter::$sCurrentLang == 'fr') ? 'fr' : 'en') . '.pdf',
            'sContactUs' => 'http://www.businesstech.fr/' . ((GMerchantCenter::$sCurrentLang == 'fr') ? 'fr/contactez-nous' : 'en/contact-us'),
            'sCurrentIso' => Language::getIsoById(GMerchantCenter::$iCurrentLang),
            'sCrossSellingUrl' => !empty($iSupportToUse) ? _GMC_SUPPORT_URL . '?utm_campaign=internal-module-ad&utm_source=banniere&utm_medium=' . _GMC_MODULE_SET_NAME : _GMC_SUPPORT_URL . GMerchantCenter::$sCurrentLang . '/6_business-tech',
            'sCrossSellingImg' => (GMerchantCenter::$sCurrentLang == 'fr') ? _GMC_URL_IMG . 'admin/module_banner_cross_selling_FR.jpg' : _GMC_URL_IMG . 'admin/module_banner_cross_selling_EN.jpg',
            'sContactUs' => !empty($iSupportToUse) ? _GMC_SUPPORT_URL . ((GMerchantCenter::$sCurrentLang == 'fr') ? 'fr/contactez-nous' : 'en/contact-us') : _GMC_SUPPORT_URL . ((GMerchantCenter::$sCurrentLang == 'fr') ? 'fr/ecrire-au-developpeur?id_product=' . _GMC_SUPPORT_ID : 'en/write-to-developper?id_product=' . _GMC_SUPPORT_ID),
            'sRateUrl' => !empty($iSupportToUse) ? _GMC_SUPPORT_URL . ((GMerchantCenter::$sCurrentLang == 'fr') ? 'fr/modules-prestashop-google-et-publicite/11-google-merchant-center-module-pour-prestashop-0656272551230.html' : 'en/google-and-advertising-modules-for-prestashop/11-google-merchant-center-module-for-prestashop-0656272551230.html') : _GMC_SUPPORT_URL . ((GMerchantCenter::$sCurrentLang == 'fr') ? '/fr/ratings.php' : '/en/ratings.php'),
        );

        // check curl_init and file_get_contents to get the distant Google taxonomy file
        BT_GmcWarning::create()->run('directive', 'allow_url_fopen', array(), true);
        $bTmpStopExec = BT_GmcWarning::create()->bStopExecution;

        BT_GmcWarning::create()->bStopExecution = false;
        BT_GmcWarning::create()->run('function', 'curl_init', array(), true);

        if ($bTmpStopExec && BT_GmcWarning::create()->bStopExecution) {
            $aAssign['bCurlAndContentStopExec'] = true;
        }

        // check if multi-shop configuration
        if (
            Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')
            && strpos(Context::getContext()->cookie->shopContext, 'g-') !== false
        ) {
            $aAssign['bMultishopGroupStopExec'] = true;
        }

        // check if we hide the config
        if (
            !empty($aAssign['bFileStopExec'])
            || !empty($aAssign['bCurlAndContentStopExec'])
            || !empty($aAssign['bMultishopGroupStopExec'])
        ) {
            $aAssign['bHideConfiguration'] = true;
        }

        $aAssign['autocmp_js'] = __PS_BASE_URI__ . 'js/jquery/plugins/autocomplete/jquery.autocomplete.js';
        $aAssign['autocmp_css'] = __PS_BASE_URI__ . 'js/jquery/plugins/autocomplete/jquery.autocomplete.css';

        // use case - get display prerequisites
        $aData = $this->displayPrerequisites($aPost);

        $aAssign = array_merge($aAssign, $aData['assign']);

        // use case - get display data of basics settings
        $aData = $this->displayBasics($aPost);

        $aAssign = array_merge($aAssign, $aData['assign']);

        // use case - get display data of feed data settings
        $aData = $this->displayFeed($aPost);

        $aAssign = array_merge($aAssign, $aData['assign']);

        // use case - get display data of google settings
        $aData = $this->displayGoogle($aPost);

        $aAssign = array_merge($aAssign, $aData['assign']);

        // use case - get display data of feed list settings
        $aData = $this->displayFeedList($aPost);

        $aAssign = array_merge($aAssign, $aData['assign']);

        // use case - get display data of feed list settings
        $aData = $this->displayReporting($aPost);

        $aAssign = array_merge($aAssign, $aData['assign']);

        // assign all included templates files
        $aAssign['sWelcome'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_WELCOME);
        $aAssign['sPrerequisitesInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_PREREQUISITES);
        $aAssign['sBasicsInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_BASICS);
        $aAssign['sFeedInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_SETTINGS);
        $aAssign['sGoogleInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_SETTINGS);
        $aAssign['sFeedListInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_LIST);
        $aAssign['sReportingInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_REPORTING);
        $aAssign['sTopBar'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_TOP);
        $aAssign['sModuleVersion'] = GMerchantCenter::$oModule->version;

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_BODY,
            'assign' => $aAssign,
        );
    }

    /**
     * displays prerequisites
     *
     * @param array $aPost
     * @return array
     */
    private function displayPrerequisites(array $aPost = null)
    {
        $aAssign = array();

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_PREREQUISITES,
            'assign' => $aAssign,
        );
    }


    /**
     *  method displays advice form
     *
     * @param array $aPost
     * @return array
     */
    private function displayStepPopup(array $aPost = null)
    {

        $aAssign = array();

        // clean headers
        @ob_end_clean();

        // force xhr mode activated
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_STEP_POPUP,
            'assign' => $aAssign,
        );
    }


    /**
     * displays basic settings
     *
     * @param array $aPost
     * @return array
     */
    private function displayBasics(array $aPost = null)
    {
        $aAssign = array(
            'sDocUri' => _MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/',
            'sDocName' => 'readme_' . ((GMerchantCenter::$sCurrentLang == 'fr') ? 'fr' : 'en') . '.pdf',
            'sLink' => (!empty(GMerchantCenter::$conf['GMC_LINK']) ? GMerchantCenter::$conf['GMC_LINK'] : GMerchantCenter::$sHost),
            'sPrefixId' => GMerchantCenter::$conf['GMERCHANTCENTER_ID_PREFIX'],
            'iProductPerCycle' => GMerchantCenter::$conf['GMC_AJAX_CYCLE'],
            'sImgSize' => GMerchantCenter::$conf['GMC_IMG_SIZE'],
            'aHomeCatLanguages' => GMerchantCenter::$conf['GMC_HOME_CAT'],
            'iHomeCatId' => GMerchantCenter::$conf['GMC_HOME_CAT_ID'],
            'bAddCurrency' => GMerchantCenter::$conf['GMC_ADD_CURRENCY'],
            'iAdvancedProductName' => GMerchantCenter::$conf['GMC_ADV_PRODUCT_NAME'],
            'iAdvancedProductTitle' => GMerchantCenter::$conf['GMC_ADV_PROD_TITLE'],
            'sFeedToken' => GMerchantCenter::$conf['GMC_FEED_TOKEN'],
            'aImageTypes' => ImageType::getImagesTypes('products'),
            'sCondition' => GMerchantCenter::$conf['GMC_COND'],
            'aAvailableCondition' => BT_GmcModuleTools::getConditionType(),
            'bSimpleId' => GMerchantCenter::$conf['GMC_SIMPLE_PROD_ID'],
            'bAddImages' => GMerchantCenter::$conf['GMC_ADD_IMAGES'],
            'bIdentifierExist' => GMerchantCenter::$conf['GMC_FORCE_IDENTIFIER'],
        );

        $aCategories = Category::getCategories(intval(GMerchantCenter::$iCurrentLang), false);
        $aAssign['aHomeCat'] = BT_GmcModuleTools::recursiveCategoryTree($aCategories, array(), current(current($aCategories)), 1);

        // get all active languages in order to loop on field form which need to manage translation
        $aAssign['aLangs'] = Language::getLanguages();

        // use case - detect if home category name has been filled
        $aAssign['aHomeCatLanguages'] = $this->getDefaultTranslations('GMC_HOME_CAT', 'HOME_CAT_NAME');

        foreach ($aAssign['aLangs'] as $aLang) {
            if (!isset($aAssign['aHomeCatLanguages'][$aLang['id_lang']])) {
                $aAssign['aHomeCatLanguages'][$aLang['id_lang']] = $GLOBALS[_GMC_MODULE_NAME . '_HOME_CAT_NAME']['en'];
            }
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_BASICS,
            'assign' => $aAssign,
        );
    }

    /**
     * displays feeds settings
     *
     * @param array $aPost
     * @return array
     */
    private function displayFeed(array $aPost = null)
    {
        if (GMerchantCenter::$sQueryMode == 'xhr') {
            // clean headers
            @ob_end_clean();
        }

        $aAssign = array(
            'bExportMode' => GMerchantCenter::$conf['GMC_EXPORT_MODE'],
            'bExportOOS' => GMerchantCenter::$conf['GMC_EXPORT_OOS'],
            'bProductOosOrder' => GMerchantCenter::$conf['GMC_EXPORT_PROD_OOS_ORDER'],
            'bExcludeNoEan' => GMerchantCenter::$conf['GMC_EXC_NO_EAN'],
            'bExcludeNoMref' => GMerchantCenter::$conf['GMC_EXC_NO_MREF'],
            'iMinPrice' => GMerchantCenter::$conf['GMC_MIN_PRICE'],
            'bProductCombos' => GMerchantCenter::$conf['GMERCHANTCENTER_P_COMBOS'],
            'iDescType' => GMerchantCenter::$conf['GMC_P_DESCR_TYPE'],
            'aDescriptionType' => BT_GmcModuleTools::getDescriptionType(),
            'iIncludeStock' => GMerchantCenter::$conf['GMC_INC_STOCK'],
            'bIncludeTagAdult' => GMerchantCenter::$conf['GMC_INC_TAG_ADULT'],
            'sIncludeSize' => GMerchantCenter::$conf['GMC_INC_SIZE'],
            'aAttributeGroups' => AttributeGroup::getAttributesGroups((int) GMerchantCenter::$oCookie->id_lang),
            'aFeatures' => Feature::getFeatures((int) GMerchantCenter::$oCookie->id_lang),
            'sIncludeColor' => GMerchantCenter::$conf['GMC_INC_COLOR'],
            'aExcludedProducts' => GMerchantCenter::$conf['GMC_PROD_EXCL'],
            'aFreeShippingProducts' => GMerchantCenter::$conf['GMC_FREE_SHIP_PROD'],
            'bIncludeMaterial' => GMerchantCenter::$conf['GMC_INC_MATER'],
            'bIncludePattern' => GMerchantCenter::$conf['GMC_INC_PATT'],
            'bIncludeGender' => GMerchantCenter::$conf['GMC_INC_GEND'],
            'bIncludeAge' => GMerchantCenter::$conf['GMC_INC_AGE'],
            'bSizeType' => GMerchantCenter::$conf['GMC_SIZE_TYPE'],
            'bSizeSystem' => GMerchantCenter::$conf['GMC_SIZE_SYSTEM'],
            'bShippingUse' => GMerchantCenter::$conf['GMC_SHIPPING_USE'],
            'bDimensionUse' => GMerchantCenter::$conf['GMC_DIMENSION'],
            'sGtinPreference' => GMerchantCenter::$conf['GMC_GTIN_PREF'],
            'aShippingCarriers' => array(),
            'bRewriteNumAttrValues' => GMerchantCenter::$conf['GMC_URL_NUM_ATTR_REWRITE'],
            'bUrlInclAttrId' => GMerchantCenter::$conf['GMC_URL_ATTR_ID_INCL'],
            'sComboSeparator' => GMerchantCenter::$conf['GMC_COMBO_SEPARATOR'],
            'bPS16013' => GMerchantCenter::$bCompare16013,
            'bExcludedDest' => GMerchantCenter::$conf['GMC_EXCLUDED_DEST'],
            'bExcludedCountry' => GMerchantCenter::$conf['GMC_EXCLUDED_COUNTRY'],
        );

        // handle product IDs and Names list to format them for the autocomplete feature
        if (!empty($aAssign['aExcludedProducts'])) {
            $sProdIds = '';
            $sProdNames = '';

            foreach ($aAssign['aExcludedProducts'] as $iKey => $sProdId) {
                $aProdIds = explode('¤', $sProdId);
                $oProduct = new Product($aProdIds[0], false, GMerchantCenter::$iCurrentLang);

                // check if we export with combinations
                if (!empty($aProdIds[1])) {
                    $oProduct->name .= BT_GmcModuleTools::getProductCombinationName(
                        $aProdIds[1],
                        GMerchantCenter::$iCurrentLang,
                        GMerchantCenter::$iShopId
                    );
                }

                $sProdIds .= $sProdId . '-';
                $sProdNames .= $oProduct->name . '||';

                $aAssign['aProducts'][] = array(
                    'id' => $sProdId,
                    'name' => $oProduct->name,
                    'attrId' => $aProdIds[1],
                    'stringIds' => $sProdId
                );
            }
            $aAssign['sProductIds'] = $sProdIds;
            $aAssign['sProductNames'] = str_replace('"', '', $sProdNames);
        }


        // handle product IDs and Names list for export product free shipping
        if (!empty($aAssign['aFreeShippingProducts'])) {
            $sProdIds = '';
            $sProdNames = '';

            foreach ($aAssign['aFreeShippingProducts'] as $iKey => $sProdId) {
                $aProdIds = explode('¤', $sProdId);
                $oProduct = new Product($aProdIds[0], false, GMerchantCenter::$iCurrentLang);

                // check if we export with combinations
                if (!empty($aProdIds[1])) {
                    $oProduct->name .= BT_GmcModuleTools::getProductCombinationName($aProdIds[1], GMerchantCenter::$iCurrentLang, GMerchantCenter::$iShopId);
                }

                $sProdIds .= $sProdId . '-';
                $sProdNames .= $oProduct->name . '||';

                $aAssign['aProductsFreeShipping'][] = array(
                    'id' => $sProdId,
                    'name' => $oProduct->name,
                    'attrId' => $aProdIds[1],
                    'stringIds' => $sProdId
                );
            }
            $aAssign['sProductFreeShippingIds'] = $sProdIds;
            $aAssign['sProductFreeShippingNames'] = str_replace('"', '', $sProdNames);
        }

        // define color and size options
        $aAssign['aColorOptions']['attribute'] = !empty(GMerchantCenter::$conf['GMC_COLOR_OPT']['attribute']) ? GMerchantCenter::$conf['GMC_COLOR_OPT']['attribute'] : array(0);
        $aAssign['aColorOptions']['feature'] = !empty(GMerchantCenter::$conf['GMC_COLOR_OPT']['feature']) ? GMerchantCenter::$conf['GMC_COLOR_OPT']['feature'] : array(0);
        $aAssign['aSizeOptions']['attribute'] = !empty(GMerchantCenter::$conf['GMC_SIZE_OPT']['attribute']) ? GMerchantCenter::$conf['GMC_SIZE_OPT']['attribute'] : array(0);
        $aAssign['aSizeOptions']['feature'] = !empty(GMerchantCenter::$conf['GMC_SIZE_OPT']['feature']) ? GMerchantCenter::$conf['GMC_SIZE_OPT']['feature'] : array(0);

        // get available categories and manufacturers
        $aCategories = Category::getCategories(intval(GMerchantCenter::$iCurrentLang), false);
        $aBrands = Manufacturer::getManufacturers();

        $aStartCategories = current($aCategories);
        $aFirst = current($aStartCategories);
        $iStart = (int) Configuration::get('PS_ROOT_CATEGORY');

        // get registered categories and brands
        $aIndexedCategories = array();
        $aIndexedBrands = array();

        // use case - get categories or brands according to the export mode
        if (GMerchantCenter::$conf['GMC_EXPORT_MODE'] == 1) {
            $aIndexedBrands = BT_GmcModuleDao::getGmcBrands(GMerchantCenter::$iShopId);
        } else {
            $aIndexedCategories = BT_GmcModuleDao::getGmcCategories(GMerchantCenter::$iShopId);
        }

        // format categories and brands
        $aAssign['aFormatCat'] = BT_GmcModuleTools::recursiveCategoryTree(
            $aCategories,
            $aIndexedCategories,
            $aFirst,
            $iStart,
            null,
            true
        );
        $aAssign['aFormatBrands'] = BT_GmcModuleTools::recursiveBrandTree($aBrands, $aIndexedBrands, $aFirst, $iStart);
        $aAssign['iShopCatCount'] = count($aAssign['aFormatCat']);
        $aAssign['iMaxPostVars'] = ini_get('max_input_vars');
        $aAssign['aFeedTax'] = array();

        if (!empty(GMerchantCenter::$aAvailableLangCurrencyCountry)) {
            foreach (GMerchantCenter::$aAvailableLangCurrencyCountry as $sKey => $aData) {
                $aAssign['aFeedTax'][] = array(
                    'tax' => BT_GmcModuleTools::isTax($aData['langIso'], $aData['countryIso']),
                    'country' => $aData['countryIso'],
                    'lang' => $aData['langIso'],
                    'langId' => $aData['langId'],
                    'tax' => BT_GmcModuleTools::isTax($aData['langIso'], $aData['countryIso'], $aData['currencyIso']),
                    'currency' => $aData['currencyIso'],
                );
            }
        }

        // handle tax and shipping fees
        foreach ($GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES'] as $sLang => $aCountries) {
            if (BT_GmcModuleDao::checkActiveLanguage($sLang)) {
                foreach ($aCountries as $sCountry => $aLocaleData) {
                    $iCountryId = Country::getByIso($sCountry);
                    if (!empty($iCountryId)) {
                        $iCountryZone = Country::getIdZone($iCountryId);
                        if (!empty($iCountryZone)) {
                            $aCarriers = BT_GmcModuleDao::getAvailableCarriers((int) $iCountryZone);
                            foreach ($aLocaleData['currency'] as $sCurrency) {
                                if (Currency::getIdByIsoCode($sCurrency)) {
                                    if (!empty($aCarriers) && Currency::getIdByIsoCode($sCurrency)) {
                                        if (!array_key_exists($sCountry, $aAssign['aShippingCarriers'])) {
                                            $aAssign['aShippingCarriers'][$sCountry] = array(
                                                'name' => $sCountry,
                                                'carriers' => $aCarriers,
                                                'shippingCarrierId' => (!empty(GMerchantCenter::$conf['GMC_SHIP_CARRIERS'][$sCountry]) ? GMerchantCenter::$conf['GMC_SHIP_CARRIERS'][$sCountry] : 0),
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_SETTINGS,
            'assign' => $aAssign,
        );
    }


    /**
     * displays Google settings
     *
     * @param array $aPost
     * @return array
     */
    private function displayGoogle(array $aPost = null)
    {
        if (GMerchantCenter::$sQueryMode == 'xhr') {
            // clean headers
            @ob_end_clean();
        }

        $aAssign = array(
            'aCountryTaxonomies' => BT_GmcModuleDao::getAvailableTaxonomyCountries($GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES']),
            'sGoogleCatListInclude' => BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_CATEGORY_LIST),
            'aTags' => BT_GmcModuleDao::getGmcTags(GMerchantCenter::$iShopId),
            'sUtmCampaign' => GMerchantCenter::$conf['GMC_UTM_CAMPAIGN'],
            'sUtmSource' => GMerchantCenter::$conf['GMC_UTM_SOURCE'],
            'sUtmMedium' => GMerchantCenter::$conf['GMC_UTM_MEDIUM'],
            'bUtmContent' => GMerchantCenter::$conf['GMC_UTM_CONTENT'],
        );

        foreach ($aAssign['aCountryTaxonomies'] as $sIsoCode => &$aTaxonomy) {
            $aTaxonomy['countryList'] = implode(', ', $aTaxonomy['countries']);
            $aTaxonomy['updated'] = BT_GmcModuleDao::checkTaxonomyUpdate($sIsoCode);
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_SETTINGS,
            'assign' => $aAssign,
        );
    }

    /**
     * displays Fancybox Google categories
     *
     * @param array $aPost
     * @return array
     */
    private function displayGoogleCategories(array $aPost = null)
    {
        // clean headers
        @ob_end_clean();

        $aAssign = array(
            'iLangId' => Tools::getValue('iLangId'),
            'sLangIso' => Tools::getValue('sLangIso'),
            'sCurrentIso' => Language::getIsoById(GMerchantCenter::$iCurrentLang),
        );
        // get shop categories
        $aShopCategories = BT_GmcModuleDao::getShopCategories(
            GMerchantCenter::$iShopId,
            $aAssign['iLangId'],
            GMerchantCenter::$conf['GMC_HOME_CAT_ID']
        );

        foreach ($aShopCategories as &$aCategory) {
            // get google taxonomy
            $aGoogleCat = BT_GmcModuleDao::getGoogleCategories(
                GMerchantCenter::$iShopId,
                $aCategory['id_category'],
                $aAssign['sLangIso']
            );
            // assign the current taxonomy
            $aCategory['google_category_name'] = is_array($aGoogleCat) && isset($aGoogleCat['txt_taxonomy']) ? $aGoogleCat['txt_taxonomy'] : '';
        }

        $aAssign['aShopCategories'] = $aShopCategories;
        $aAssign['iShopCatCount'] = count($aShopCategories);
        $aAssign['iMaxPostVars'] = ini_get('max_input_vars');

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_CATEGORY_POPUP,
            'assign' => $aAssign,
        );
    }


    /**
     * displays autocomplete google categories
     *
     * @param array $aPost
     * @return array
     */
    private function displayAutocomplete(array $aPost = null)
    {
        // clean headers
        @ob_end_clean();

        // set
        $sOutput = '';

        $sLangIso = Tools::getValue('sLangIso');
        $sQuery = Tools::getValue('q');

        // explode query string
        $aWords = explode(' ', $sQuery);

        // get matching query
        $aItems = BT_GmcModuleDao::autocompleteSearch($sLangIso, $aWords);

        if (
            !empty($aItems)
            && is_array($aItems)
        ) {
            foreach ($aItems as $aItem) {
                $sOutput .= trim($aItem['value']) . "\n";
            }
        }
        echo $sOutput;
        exit(0);
    }

    /**
     * displays custom labels
     *
     * @param array $aPost
     * @return array
     */
    private function displayCustomLabel(array $aPost = null)
    {
        // clean headers
        @ob_end_clean();

        $aAssign = array();

        // get available categories and manufacturers
        $aCategories = Category::getCategories(intval(GMerchantCenter::$iCurrentLang), false);
        $aBrands = Manufacturer::getManufacturers();
        $aSuppliers = Supplier::getSuppliers();

        $aStartCategories = current($aCategories);
        $aFirst = current($aStartCategories);
        $iStart = (int) Configuration::get('PS_ROOT_CATEGORY');

        // get registered categories and brands and suppliers
        $aIndexedCategories = array();
        $aIndexedBrands = array();
        $aIndexedSuppliers = array();

        // use case - get categories or brands or suppliers according to the id tag
        $iTagId = Tools::getValue('iTagId');
        $aTag = array();
        if (!empty($iTagId)) {
            $aTag = BT_GmcModuleDao::getGmcTags(GMerchantCenter::$iShopId, $iTagId);
            $aIndexedCategories = BT_GmcModuleDao::getGmcTags(null, $iTagId, 'cats', 'category');
            $aIndexedBrands = BT_GmcModuleDao::getGmcTags(null, $iTagId, 'brands', 'brand');
            $aIndexedSuppliers = BT_GmcModuleDao::getGmcTags(null, $iTagId, 'suppliers', 'supplier');
        }

        // format categories and brands and suppliers
        $aAssign['aTag'] = (count($aTag) == 1 && isset($aTag[0])) ? $aTag[0] : $aTag;
        $aAssign['aFormatCat'] = BT_GmcModuleTools::recursiveCategoryTree(
            $aCategories,
            $aIndexedCategories,
            $aFirst,
            $iStart
        );
        $aAssign['aFormatBrands'] = BT_GmcModuleTools::recursiveBrandTree($aBrands, $aIndexedBrands, $aFirst, $iStart);
        $aAssign['aFormatSuppliers'] = BT_GmcModuleTools::recursiveSupplierTree(
            $aSuppliers,
            $aIndexedSuppliers,
            $aFirst,
            $iStart
        );
        $aAssign['iShopCatCount'] = count($aAssign['aFormatCat']);
        $aAssign['iMaxPostVars'] = ini_get('max_input_vars');

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_CUSTOM_LABEL,
            'assign' => $aAssign,
        );
    }


    /**
     * displays feed list
     *
     * @param array $aPost
     * @return array
     */
    private function displayFeedList(array $aPost = null)
    {
        if (GMerchantCenter::$sQueryMode == 'xhr') {
            // clean headers
            @ob_end_clean();
        }

        $aAssign = array(
            'iShopId' => GMerchantCenter::$iShopId,
            'sGmcLink' => GMerchantCenter::$conf['GMC_LINK'],
            'bReporting' => GMerchantCenter::$conf['GMC_REPORTING'],
            'iTotalProductToExport' => BT_GmcModuleDao::getProductIds(GMerchantCenter::$iShopId, (int) GMerchantCenter::$conf['GMC_EXPORT_MODE'], true),
            'iTotalProduct' => BT_GmcModuleDao::countProducts(GMerchantCenter::$iShopId, (int) GMerchantCenter::$conf['GMERCHANTCENTER_P_COMBOS']),
            'aFeedFileList' => array(),
            'aFlyFileList' => array(),
            'aCronList' => array(),
            'aCronLang' => (!empty(GMerchantCenter::$conf['GMC_CHECK_EXPORT']) ? GMerchantCenter::$conf['GMC_CHECK_EXPORT'] : array()),
        );

        // handle data feed file name
        if (!empty($aAssign['sGmcLink'])) {
            // handle manual xml file / on-the-fly output / cron URL
            if (!empty(GMerchantCenter::$aAvailableLangCurrencyCountry)) {
                foreach (GMerchantCenter::$aAvailableLangCurrencyCountry as $sKey => $aData) {
                    $sFileSuffix = BT_GmcModuleTools::buildFileSuffix($aData['langIso'], $aData['countryIso'], (!empty($aData['currencyFirst']) ? '' : $aData['currencyIso']));
                    $sFileName = GMerchantCenter::$sFilePrefix . '.' . $sFileSuffix . '.xml';

                    // XML file
                    if (is_file(_GMC_SHOP_PATH_ROOT . $sFileName)) {
                        $aAssign['aFeedFileList'][] = array(
                            'link' => $aAssign['sGmcLink'] . __PS_BASE_URI__ . $sFileName,
                            'filename' => $sFileName,
                            'filemtime' => date("d-m-Y H:i:s", filemtime(_GMC_SHOP_PATH_ROOT . $sFileName)),
                            'country' => $aData['countryIso'],
                            'lang' => $aData['langIso'],
                            'langId' => $aData['langId'],
                            'currencyIso' => $aData['currencyIso'],
                            'checked' => (in_array($aData['langIso'] . '_' . $aData['countryIso'] . '_' . $aData['currencyIso'], $aAssign['aCronLang']) ? true : false),
                            'currencyFirst' => (!empty($aData['currencyFirst']) ? 1 : 0),
                            'countryName' => $aData['countryName'],
                            'langName' => $aData['langName'],
                            'currencySign' => $aData['currencySign'],
                        );

                        // cron URLs
                        $aAssign['aCronList'][] = array(
                            'lang' => $aData['langIso'],
                            'country' => $aData['countryIso'],
                            'currencyIsoCron' => $aData['currencyIso'],
                            'currencyFirst' => (!empty($aData['currencyFirst']) ? 1 : 0),
                            'link' => Context::getContext()->link->getModuleLink(_GMC_MODULE_SET_NAME, _GMC_CTRL_CRON, array('id_shop' =>  GMerchantCenter::$iShopId, 'gmc_lang_id' => $aData['langId'], 'country' => $aData['countryIso'], 'currency_iso' => $aData['currencyIso'], 'token' => GMerchantCenter::$conf['GMC_FEED_TOKEN'], 'sType' => 'cron')),
                            'countryName' => $aData['countryName'],
                            'langName' => $aData['langName'],
                            'currencySign' => $aData['currencySign'],
                        );
                    }


                    // FLY OUTPUT
                    $aAssign['aFlyFileList'][] = array(
                        'lang' => $aData['langIso'],
                        'country' => $aData['countryIso'],
                        'iso_code' => $aData['langIso'],
                        'currencyIso' => $aData['currencyIso'],
                        'link' => Context::getContext()->link->getModuleLink(_GMC_MODULE_SET_NAME, _GMC_CTRL_FLY, array('id_shop' =>  GMerchantCenter::$iShopId, 'gmc_lang_id' => $aData['langId'], 'country' => $aData['countryIso'], 'currency_iso' => $aData['currencyIso'], 'token' => GMerchantCenter::$conf['GMC_FEED_TOKEN'], 'sType' => 'flyOutput')),
                        'countryName' => $aData['countryName'],
                        'langName' => $aData['langName'],
                        'currencySign' => $aData['currencySign'],
                        'countryIso' => $aData['countryIso'],
                    );
                }
            }
            // handle the cron URL for each data feed type
            $aAssign['sCronUrl'] =  Context::getContext()->link->getModuleLink(_GMC_MODULE_SET_NAME, _GMC_CTRL_CRON, array('id_shop' =>  GMerchantCenter::$iShopId));

            // check if the feed protection is activated
            if (!empty(GMerchantCenter::$conf['GMC_FEED_TOKEN'])) {
                $aAssign['sCronUrl'] .= '&token=' . GMerchantCenter::$conf['GMC_FEED_TOKEN'];
            }
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_LIST,
            'assign' => $aAssign,
        );
    }

    /**
     * displays reporting settings
     *
     * @param array $aPost
     * @return array
     */
    private function displayReporting(array $aPost = null)
    {
        $aAssign = array(
            'aLangCurrencies' => BT_GmcModuleTools::getGeneratedReport(),
            'bReporting' => GMerchantCenter::$conf['GMC_REPORTING'],
        );

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_REPORTING,
            'assign' => $aAssign,
        );
    }


    /**
     * displays reporting fancybox
     *
     * @param array $aPost
     * @return array
     */
    private function displayReportingBox(array $aPost = null)
    {
        // clean headers
        @ob_end_clean();

        $aAssign = array();
        $aTmp = array();

        // get the current lang ID
        $sLang = Tools::getValue('lang');
        $iProductCount = Tools::getValue('count');
        $sCurrencyIso = Tools::getValue('sCurrencyIso');

        $sLang = $sLang . '_' . $sCurrencyIso;

        if (
            !empty($sLang)
            && strstr($sLang, '_')
        ) {
            list($sLangIso, $sCountryIso, $sCurrencyIso) = explode('_', $sLang);

            // get the identify lang ID
            $iLangId = BT_GmcModuleTools::getLangId($sLangIso);

            // include
            require_once(_GMC_PATH_LIB . 'module-reporting_class.php');

            // set reporting object
            BT_GmcReporting::create(true)->setFileName(_GMC_REPORTING_DIR . 'reporting-' . $sLangIso . '-' . Tools::strtolower($sCountryIso) . '-' . $sCurrencyIso . '.txt');

            // get the current report
            $aReporting = BT_GmcReporting::create()->get();

            if (!empty($aReporting)) {

                static $aTmpProduct = array();

                // get the language name
                $aLanguage = Language::getLanguage($iLangId);
                $sLanguageName = $aLanguage['name'];
                // get the country name
                $sCountryName = Country::getNameById($iLangId, Country::getByIso(Tools::strtolower($sCountryIso)));

                // check if exists counter key in the reporting
                if (!empty($aReporting['counter'][0])) {
                    if (empty($iProductCount)) {
                        $iProductCount = $aReporting['counter'][0]['products'];
                    }
                    unset($aReporting['counter']);
                }

                // load google tags
                $aGoogleTags = BT_GmcModuleTools::loadGoogleTags();

                foreach ($aReporting as $sTagName => &$aGTag) {
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['count'] = count($aGTag);
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['label'] = (isset($aGoogleTags[$sTagName]) ? $aGoogleTags[$sTagName]['label'] : '');
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['msg'] = (isset($aGoogleTags[$sTagName]) ? $aGoogleTags[$sTagName]['msg'] : '');
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['faq_id'] = (isset($aGoogleTags[$sTagName]) ? (int) ($aGoogleTags[$sTagName]['faq_id']) : 0);
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['anchor'] = (isset($aGoogleTags[$sTagName]) ? $aGoogleTags[$sTagName]['anchor'] : '');
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['mandatory'] = (isset($aGoogleTags[$sTagName]) ? $aGoogleTags[$sTagName]['mandatory'] : false);

                    // detect the old format system and the new format
                    if (
                        isset($aGTag[0]['productId'])
                        && strstr($aGTag[0]['productId'], '_')
                    ) {
                        foreach ($aGTag as $iKey => &$aProdValue) {
                            list($iProdId, $iAttributeId) = explode('_', $aProdValue['productId']);
                            if (empty($aTmpProduct[$aProdValue['productId']])) {
                                // get the product obj
                                $oProduct = new Product((int) $iProdId, true, (int) $iLangId);
                                $oCategory = new Category((int) ($oProduct->id_category_default), (int) $iLangId);

                                // set the product URL
                                $aProdValue['productUrl'] = BT_GmcModuleTools::getProductLink($oProduct, $iLangId);
                                // set the product name
                                $aProdValue['productName'] = $oProduct->name;

                                // if combination
                                if (!empty($iAttributeId)) {
                                    // set the product URL
                                    $aProdValue['productUrl'] = Context::getContext()->link->getProductLink($oProduct, $iLangId, Tools::strtolower($oCategory->link_rewrite));

                                    // get the combination attributes to format the product name
                                    $aCombinationAttr = BT_GmcModuleDao::getProductComboAttributes($iAttributeId, $iLangId, GMerchantCenter::$iShopId);

                                    if (!empty($aCombinationAttr)) {
                                        $sExtraName = '';
                                        foreach ($aCombinationAttr as $c) {
                                            $sExtraName .= ' ' . Tools::stripslashes($c['name']);
                                        }
                                        $aProdValue['productName'] .= $sExtraName;
                                    }
                                }


                                $aTmpProduct[$aProdValue['productId']] = array(
                                    'productId' => $iProdId,
                                    'productAttrId' => $iAttributeId,
                                    'productUrl' => $aProdValue['productUrl'],
                                    'productName' => $aProdValue['productName'],
                                );
                            }
                            $aProdValue = $aTmpProduct[$aProdValue['productId']];
                        }
                    }
                    $aTmp[$aGoogleTags[$sTagName]['type']][$sTagName]['data'] = $aGTag;
                }
                $aTmpProduct = array();
                ksort($aTmp);

                $aAssign = array(
                    'sLangName' => $sLanguageName,
                    'sCountryName' => $sCountryName,
                    'aReport' => $aTmp,
                    'iProductCount' => (int) $iProductCount,
                    'sPath' => _GMC_PATH_ROOT,
                    'sFaqURL' => _GMC_BT_FAQ_MAIN_URL . 'faq.php?id=',
                    'sFaqLang' => $sLangIso,
                    'sToken' => Tools::getAdminTokenLite('AdminProducts'),
                    'sProductLinkController' => $_SERVER['SCRIPT_URI'] . '?controller=AdminProducts',
                    'sProductAction' => '&updateproduct',
                );
            } else {
                $aAssign['aErrors'][] = array(
                    'msg' => GMerchantCenter::$oModule->l(
                        'There isn\'t any report for this language and country',
                        'admin-display_class.php'
                    ) . ' : ' . $sLangIso . ' - ' . $sCountryIso,
                    'code' => 190
                );
            }
        } else {
            $aAssign['aErrors'][] = array(
                'msg' => GMerchantCenter::$oModule->l(
                    'Language ISO and country ISO aren\'t well formatted',
                    'admin-display_class.php'
                ),
                'code' => 191
            );
        }

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_REPORTING_BOX,
            'assign' => $aAssign,
        );
    }


    /**
     * displays advanced tag category settings
     *
     * @param array $aPost
     * @return array
     */
    private function displayAdvancedTagCategory(array $aPost = null)
    {
        require_once(_GMC_PATH_LIB . 'module-tools_class.php');

        // clean headers
        @ob_end_clean();

        $aCountriesToHandle = array();
        $aAvailableLanguage = array();
        $aShopCategories = BT_GmcModuleDao::getShopCategories(GMerchantCenter::$iShopId, GMerchantCenter::$iCurrentLang, GMerchantCenter::$conf['GMC_HOME_CAT_ID']);

        // Handle the country to handle in according to Google available language
        $aAvailableLanguage = BT_GmcModuleTools::getAvailableLanguages(GMerchantCenter::$iShopId);
        $aCountries = BT_GmcModuleTools::getLangCurrencyCountry($aAvailableLanguage, $GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES']);

        foreach ($aCountries as $key => $aCountry) {

            $aCountriesToHandle[] =  $aCountry['countryIso'];
        }

        foreach ($aShopCategories as &$aCat) {
            // get feature by category Id
            $aFeatures = BT_GmcModuleDao::getFeaturesByCategory($aCat['id_category'], GMerchantCenter::$iShopId);

            if (!empty($aFeatures)) {
                $aCat['material'] = $aFeatures['material'];
                $aCat['pattern'] = $aFeatures['pattern'];
                $aCat['agegroup'] = $aFeatures['agegroup'];
                $aCat['gender'] = $aFeatures['gender'];
                $aCat['adult'] = $aFeatures['adult'];
                $aCat['sizeType'] = $aFeatures['sizeType'];
                $aCat['sizeSystem'] = $aFeatures['sizeSystem'];
                $aCat['excluded_destination'] = !empty($aFeatures['excluded_destination']) ? explode(' ', $aFeatures['excluded_destination']) : '';
                $aCat['excluded_country'] = !empty($aFeatures['excluded_country']) ? explode(' ', $aFeatures['excluded_country']) : '';
            } else {
                $aCat['material'] = '';
                $aCat['pattern'] = '';
                $aCat['agegroup'] = '';
                $aCat['gender'] = '';
                $aCat['adult'] = '';
                $aCat['sizeSystem'] = '';
                $aCat['exluded_destination'] = '';
            }
        }

        $aAssign = array(
            'aShopCategories' => $aShopCategories,
            'aCountries' => array_unique($aCountriesToHandle),
            'aFeatures' => Feature::getFeatures(GMerchantCenter::$iCurrentLang),
            'sUseTag' => Tools::getValue('sUseTag'),
            'bMaterial' => GMerchantCenter::$conf['GMC_INC_MATER'],
            'bPattern' => GMerchantCenter::$conf['GMC_INC_PATT'],
            'bGender' => GMerchantCenter::$conf['GMC_INC_GEND'],
            'bAgeGroup' => GMerchantCenter::$conf['GMC_INC_AGE'],
            'bTagAdult' => GMerchantCenter::$conf['GMC_INC_TAG_ADULT'],
            'bSizeType' => GMerchantCenter::$conf['GMC_SIZE_TYPE'],
            'bSizeSystem' => GMerchantCenter::$conf['GMC_SIZE_SYSTEM'],
            'bExcludedDest' => GMerchantCenter::$conf['GMC_EXCLUDED_DEST'],
            'bExcludedCountry' => GMerchantCenter::$conf['GMC_EXCLUDED_COUNTRY'],
        );

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_ADVANCED_TAG_CATEGORY,
            'assign' => $aAssign,
        );
    }


    /**
     * displays search product name for autocomplete
     *
     * @param array $aPost
     * @return array
     */
    private function displaySearchProduct(array $aPost = null)
    {
        // clean headers
        @ob_end_clean();

        // set
        $sOutput = '';

        // get the query to search
        $sSearch = Tools::getValue('q');
        $sExcludedList = Tools::getValue('excludeIds');

        if (!empty($sSearch)) {
            $aMatchingProducts = BT_GmcModuleDao::searchProducts($sSearch, (int) GMerchantCenter::$conf['GMERCHANTCENTER_P_COMBOS'], $sExcludedList);

            if (!empty($aMatchingProducts)) {
                foreach ($aMatchingProducts as $aProduct) {
                    // check if we export with combinations
                    if (!empty($aProduct['id_product_attribute'])) {
                        $aProduct['name'] .= BT_GmcModuleTools::getProductCombinationName(
                            $aProduct['id_product_attribute'],
                            GMerchantCenter::$iCurrentLang,
                            GMerchantCenter::$iShopId
                        );
                    }
                    $sOutput .= trim($aProduct['name']) . '|' . (int) $aProduct['id_product'] . '|' . (!empty($aProduct['id_product_attribute']) ? $aProduct['id_product_attribute'] : '0') . "\n";
                }
            }
        }

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_PROD_SEARCH,
            'assign' => array('json' => $sOutput),
        );
    }

    /**
     * returns the matching requested translations
     *
     * @param string $sSerializedVar
     * @param string $sGlobalVar
     * @return array
     */
    private function getDefaultTranslations($sSerializedVar, $sGlobalVar)
    {
        $aTranslations = array();

        if (!empty(GMerchantCenter::$conf[strtoupper($sSerializedVar)])) {
            $aTranslations = is_string(GMerchantCenter::$conf[strtoupper($sSerializedVar)]) ? unserialize(GMerchantCenter::$conf[strtoupper($sSerializedVar)]) : GMerchantCenter::$conf[strtoupper($sSerializedVar)];
        } else {
            foreach ($GLOBALS[_GMC_MODULE_NAME . '_' . strtoupper($sGlobalVar)] as $sIsoCode => $sTranslation) {
                $iLangId = BT_GmcModuleTools::getLangId($sIsoCode);

                if ($iLangId) {
                    // get Id by iso
                    $aTranslations[$iLangId] = $sTranslation;
                }
            }
        }

        if (!empty($aTranslations)) {
            foreach ($aTranslations as $iLangId => $sTitle) {
                if (empty($sTitle)) {
                    $aTranslations[$iLangId] = $GLOBALS[_GMC_MODULE_NAME . '_' . strtoupper($sGlobalVar)]['en'];
                }
            }
        }

        return $aTranslations;
    }


    /**
     * set singleton
     *
     * @return obj
     */
    public static function create()
    {
        static $oDisplay;

        if (null === $oDisplay) {
            $oDisplay = new BT_AdminDisplay();
        }
        return $oDisplay;
    }
}
