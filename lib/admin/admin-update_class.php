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

class BT_AdminUpdate implements BT_IAdmin
{
    /**
     * update all tabs content of admin page
     *
     * @param string $sType => define which method to execute
     * @param array $aParam
     * @return array
     */
    public function run($sType, array $aParam = null)
    {
        // set variables
        $aDisplayData = array();

        switch ($sType) {
            case 'stepPopup': // use case - update stepPopup settings
            case 'basic': // use case - update basic settings
            case 'gsa': // use case - update gsa
            case 'shopLink': // use case for shop link management
            case 'feed': // use case - update feed settings
            case 'feedList': // use case - update feed list settings
            case 'tag': // use case - update advanced tag settings
            case 'label': // use case - update custom label settings
            case 'google': // use case - update google campaign settings
            case 'googleCategoriesMatching': // use case - update google categories matching settings
            case 'reporting': // use case - update reporting settings
            case 'googleCategoriesSync': // use case - update google categories sync action
            case 'xml': // use case - update the xml file
                // execute match function
                $aDisplayData = call_user_func_array(array($this, 'update' . ucfirst($sType)), array($aParam));
                break;
            default:
                break;
        }
        return $aDisplayData;
    }

    /**
     * method update advice settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateStepPopup(array $aPost)
    {

        // clean headers
        @ob_end_clean();

        // set
        $aAssign = array();

        Configuration::updateValue('GMC_CONF_STEP_3', 1);

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_BODY,
            'assign' => $aAssign,
        );
    }

    /**
     * update basic settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateBasic(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        try {
            // register title
            $sShopLink = Tools::getValue('bt_link');

            // clean the end slash if exists
            if (substr($sShopLink, -1) == '/') {
                $sShopLink = substr($sShopLink, 0, strlen($sShopLink) - 1);
            }
            if (!Configuration::updateValue('GMC_LINK', $sShopLink)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during shop link update', 'admin-update_class') . '.', 501);
            }

            if (Tools::getIsset('bt_simple_id')) {
                $bSimpleProduct = Tools::getValue('bt_simple_id');
                if (!Configuration::updateValue('GMC_SIMPLE_PROD_ID', $bSimpleProduct)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during lang id update', 'admin-update_class') . '.', 530);
                }
            }

            // register prefix
            $sPrefix = Tools::getValue('bt_prefix-id');
            if (!Configuration::updateValue('GMERCHANTCENTER_ID_PREFIX', $sPrefix)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during shop prefix ID update', 'admin-update_class') . '.', 502);
            }

            // register home category name in all active languages
            $this->updateLang($aPost, 'bt_home-cat-name', 'GMC_HOME_CAT', false, GMerchantCenter::$oModule->l('home category name', 'admin-update_class'));

            // register ajax cycle
            $iAjaxCycle = Tools::getValue('bt_ajax-cycle');
            if (!Configuration::updateValue('GMC_AJAX_CYCLE', $iAjaxCycle)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during ajax cycle update', 'admin-update_class') . '.', 503);
            }

            // register image type
            $sImageType = Tools::getValue('bt_image-size');
            if (!Configuration::updateValue('GMC_IMG_SIZE', $sImageType)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during image size update', 'admin-update_class') . '.', 504);
            }

            if (!Configuration::updateValue('GMC_ADD_IMAGES', Tools::getValue('bt_add_images'))) {
                throw new Exception(GMerchantCenter::$oModule->l(
                    'An error occurred during home category ID update',
                    'admin-update_class'
                ) . '.', 505);
            }

            if (!Configuration::updateValue('GMC_FORCE_IDENTIFIER', Tools::getValue('bt_identifier_exist'))) {
                throw new Exception(GMerchantCenter::$oModule->l(
                    'An error occurred during home category ID update',
                    'admin-update_class'
                ) . '.', 505);
            }

            // register home category ID
            $iHomeCatId = Tools::getValue('bt_home-cat-id');
            if (!Configuration::updateValue('GMC_HOME_CAT_ID', $iHomeCatId)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during home category ID update', 'admin-update_class') . '.', 505);
            }

            // register if add currency or not
            $bAddCurrency = Tools::getValue('bt_add-currency');
            if (!Configuration::updateValue('GMC_ADD_CURRENCY', $bAddCurrency)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during adding currency update', 'admin-update_class') . '.', 506);
            }

            // register product condition
            $sProductCondition = Tools::getValue('bt_product-condition');
            if (!Configuration::updateValue('GMC_COND', $sProductCondition)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during product condition update', 'admin-update_class') . '.', 507);
            }

            // register advanced product name
            $sAdvancedProdName = Tools::getValue('bt_advanced-prod-name');
            if (!Configuration::updateValue('GMC_ADV_PRODUCT_NAME', $sAdvancedProdName)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during advanced format name update', 'admin-update_class') . '.', 508);
            }

            // register protection mode
            if (!Configuration::updateValue('GMC_FEED_PROTECTION', 1)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during protection mode update', 'admin-update_class') . '.', 509);
            }
            // register feed token
            $sFeedToken = Tools::getValue('bt_feed-token');
            if (!Configuration::updateValue('GMC_FEED_TOKEN', $sFeedToken)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during feed token update', 'admin-update_class') . '.', 510);
            }

            // register advanced product title
            $sAdvancedProdTitle = Tools::getValue('bt_advanced-prod-title');
            if (!Configuration::updateValue('GMC_ADV_PROD_TITLE', $sAdvancedProdTitle)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during format title update', 'admin-update_class') . '.', 511);
            }


            Configuration::updateValue('GMC_CONF_STEP_1', 1);
        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration();

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with basics settings updated
        $aDisplay = BT_AdminDisplay::create()->run('basics');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return $aDisplay;
    }

    /**
     * update gsa settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateGsa(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        require_once(_GMC_PATH_LIB_GSA . 'gsa-client_class.php');

        try {

            // register apiKey
            $sApiKey = Tools::getValue('bt_api-key');
            if (!Configuration::updateValue('GMC_API_KEY', $sApiKey)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during api key update', 'admin-update_class') . '.', 100);
            }

            // Update merchant ID
            $sMerchantId = Tools::getValue('bt_merchant-id');
            if (!Configuration::updateValue('GMC_MERCHANT_ID', $sMerchantId)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during Merchant Center ID update', 'admin-update_class') . '.', 101);
            }

            //Customer group update
            if (Tools::getIsset('bt_default-group')) {
                $iDefaultCustGroup = Tools::getValue('bt_default-group');

                if (is_numeric($iDefaultCustGroup)) {
                    if (!Configuration::updateValue('GMC_GSA_CUSTOMER_GROUP', $iDefaultCustGroup)) {
                        throw new Exception(GMerchantCenter::$oModule->l(
                            'An error occurred during default customer group update',
                            'admin-update_class'
                        ) . '.', 114);
                    }
                } else {
                    throw new Exception(GMerchantCenter::$oModule->l(
                        'Default customer group is not a numeric',
                        'admin-update_class'
                    ) . '.', 115);
                }
            }
            // Update gsa carrier ID
            $iCarrierId = Tools::getValue('bt_gsa-carrier-default');
            if (!Configuration::updateValue('GMC_GSA_DEFAULT_CARRIER', $iCarrierId)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during carrier ID update', 'admin-update_class') . '.', 101);
            }

            // Update the gsa carrier mapping
            if (Tools::getIsset('bt_gsa-carrier')) {
                $aShippingCarriers = array();
                $aPostShippingCarriers = Tools::getValue('bt_gsa-carrier');

                if (
                    !empty($aPostShippingCarriers)
                    && is_array($aPostShippingCarriers)
                ) {
                    foreach ($aPostShippingCarriers as $iKey => $mVal) {
                        $aShippingCarriers[$iKey] = $mVal;
                    }
                    $sShippingCarriers = serialize($aShippingCarriers);
                } else {
                    $sShippingCarriers = '';
                }
                if (!Configuration::updateValue('GMC_GSA_CARRIERS_MAP', $sShippingCarriers)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during carriers matching update', 'admin-update_class') . '.', 102);
                }
            }

            $sModuleConfiguration = Context::getContext()->link->getAdminLink('AdminModules');

            //Use case to build the module configuration return URL
            if (empty(GMerchantCenter::$bCompare17)) {
                $sAdminFolder = array_pop((array_slice(explode('/', _PS_ADMIN_DIR_), -1)));
            }

            //Get the adminModule tab
            $sModuleConfiguration = !empty(GMerchantCenter::$bCompare17) ? Context::getContext()->link->getAdminLink('AdminModules') : _PS_BASE_URL_ . __PS_BASE_URI__ . $sAdminFolder . '/' . Context::getContext()->link->getAdminLink('AdminModules');

            // Mangage data send to API
            $aConf = array(
                'api_key' => GMerchantCenter::$conf['GMC_API_KEY'],
                'merchant_id' => GMerchantCenter::$conf['GMC_MERCHANT_ID'],
                'module_name' => GMerchantCenter::$oModule->name,
                'module_url' => str_replace('controller=AdminModules', 'controller=AdminModules&configure=' . GMerchantCenter::$oModule->name, $sModuleConfiguration),
                'backoffice_url' =>  !empty(GMerchantCenter::$bCompare17) ? Context::getContext()->link->getAdminLink('AdminModules') : _PS_BASE_URL_ . __PS_BASE_URI__ . $sAdminFolder . '/' . Context::getContext()->link->getAdminLink('AdminDashboard'),
                'backoffice_orders_url' => !empty(GMerchantCenter::$bCompare17) ? Context::getContext()->link->getAdminLink('AdminModules') : _PS_BASE_URL_ . __PS_BASE_URI__ . $sAdminFolder . '/' . Context::getContext()->link->getAdminLink('AdminOrders'),
                'module_conf' => GMerchantCenter::$conf,
                'module_version' => GMerchantCenter::$conf['GMC_VERSION']
            );
            //Use case for shop link creation with our API
            if (GsaClient::authApi($sApiKey)) {
                if (!Configuration::updateValue('GMC_SHOP_LINK_API', 1)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during carrier ID update', 'admin-update_class') . '.', 101);
                }
            } else {
                if (!Configuration::updateValue('GMC_SHOP_LINK_API', 0)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during carrier ID update', 'admin-update_class') . '.', 101);
                }
            }

            //Use case if the shop is created on our service and if we have API key we can send the configuration via the API
            if (!empty(GMerchantCenter::$conf['GMC_SHOP_LINK_API']) && !empty($sApiKey)) {
                GsaClient::updateModuleConfigurationForGsa(GMerchantCenter::$conf['GMC_API_KEY'], $aConf);
            }
        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration();

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with basics settings updated
        $aDisplay = BT_AdminDisplay::create()->run('gsa');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return $aDisplay;
    }


    /**
     * update shop link association
     *
     * @param array $aPost
     * @return array
     */
    private function updateShopLink(array $aPost)
    {
        // clean headers
        @ob_end_clean();
        $aData = array();
        require_once(_GMC_PATH_LIB_GSA . 'gsa-client_class.php');

        $bActivate = Tools::getValue('bLink');

        if (empty($bActivate)) {
            GsaClient::disableShop(GMerchantCenter::$conf['GMC_API_KEY']);
        } else {
            GsaClient::enableShop(GMerchantCenter::$conf['GMC_API_KEY']);
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration();

        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with basics settings updated
        $aDisplay = BT_AdminDisplay::create()->run('gsa');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        return $aDisplay;
    }

    /**
     * update feed management settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateFeed(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        try {
            // include
            require_once(_GMC_PATH_LIB . 'module-dao_class.php');

            /* USE CASE - update categories and brands to export */
            if (Tools::getIsset('bt_export')) {
                $bExportMode = Tools::getValue('bt_export');
                if (!Configuration::updateValue('GMC_EXPORT_MODE', $bExportMode)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during export mode update', 'admin-update_class') . '.', 520);
                }

                // handle categories and brands to export
                if ($bExportMode == 0) {
                    $aCategoryBox = Tools::getValue('bt_category-box');

                    if (empty($aCategoryBox)) {
                        throw new Exception(GMerchantCenter::$oModule->l('An error occurred because you would select one category at least', 'admin-update_class') . '.', 521);
                    } else {
                        // delete previous categories
                        $bResult = BT_GmcModuleDao::deleteCategories(GMerchantCenter::$iShopId);

                        foreach ($aCategoryBox as $iCatId) {
                            // insert
                            $bResult = BT_GmcModuleDao::insertCategory($iCatId, GMerchantCenter::$iShopId);
                        }
                    }
                } else {
                    $aBrandBox = Tools::getValue('bt_brand-box');

                    if (empty($aBrandBox)) {
                        throw new Exception(GMerchantCenter::$oModule->l('An error occurred because you would select one brand at least', 'admin-update_class') . '.', 522);
                    } else {
                        // delete previous brands
                        BT_GmcModuleDao::deleteBrands(GMerchantCenter::$iShopId);

                        foreach ($aBrandBox as $iBrandId) {
                            // insert
                            BT_GmcModuleDao::insertBrand($iBrandId, GMerchantCenter::$iShopId);
                        }
                    }
                }
            }

            /* USE CASE - update exclusion rules */
            // handle if we export or not products out of stock
            if (Tools::getIsset('bt_export-oos')) {
                $bExportOOSMode = Tools::getValue('bt_export-oos');
                if (!Configuration::updateValue('GMC_EXPORT_OOS', $bExportOOSMode)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during export out of stock mode update', 'admin-update_class') . '.', 523);
                }

                if ($bExportOOSMode) {
                    $bProductOosOrder = Tools::getValue('bt_product-oos-order');
                    if (!Configuration::updateValue('GMC_EXPORT_PROD_OOS_ORDER', $bProductOosOrder)) {
                        throw new Exception(GMerchantCenter::$oModule->l('An error occurred during product out of stock update', 'admin-update_class') . '.', 549);
                    }
                }
            }

            // handle if we export or not products without EAN code
            if (Tools::getIsset('bt_excl-no-ean')) {
                $bExportNoEan = Tools::getValue('bt_excl-no-ean');
                if (!Configuration::updateValue('GMC_EXC_NO_EAN', $bExportNoEan)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during export without EAN code update', 'admin-update_class') . '.', 524);
                }
            }

            // handle if we export or not products without manufacturer code
            if (Tools::getIsset('bt_excl-no-mref')) {
                $bExportNoMref = Tools::getValue('bt_excl-no-mref');
                if (!Configuration::updateValue('GMC_EXC_NO_MREF', $bExportNoMref)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during export without manufacturer ref update', 'admin-update_class') . '.', 525);
                }
            }

            // handle if we export products over a min price
            if (Tools::getIsset('bt_min-price')) {
                $fMinPrice = Tools::getValue('bt_min-price');
                if (!Configuration::updateValue(
                    'GMC_MIN_PRICE',
                    (!empty($fMinPrice) ? number_format(str_replace(',', '.', $fMinPrice), 2) : 0.00)
                )) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during export with a min price update', 'admin-update_class') . '.', 526);
                }
            }

            /* USE CASE - update feed data options */
            if (Tools::getIsset('bt_prod-combos')) {
                // how to export products
                $bProductCombos = Tools::getValue('bt_prod-combos');
                if (!Configuration::updateValue('GMERCHANTCENTER_P_COMBOS', $bProductCombos)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during export one product or combinations update', 'admin-update_class') . '.', 527);
                }
                if (!empty($bProductCombos)) {
                    // use case - options around the combination URLs for the export each combination as a single product
                    if (Tools::getIsset('bt_rewrite-num-attr')) {
                        $bRewriteNumAttr = Tools::getValue('bt_rewrite-num-attr');
                        if (!Configuration::updateValue('GMC_URL_NUM_ATTR_REWRITE', $bRewriteNumAttr)) {
                            throw new Exception(GMerchantCenter::$oModule->l('An error occurred during rewrite numeric attributes update', 'admin-update_class') . '.', 544);
                        }
                    }
                    if (Tools::getIsset('bt_incl-attr-id')) {
                        $bInclAttrId = Tools::getValue('bt_incl-attr-id');
                        if (!Configuration::updateValue('GMC_URL_ATTR_ID_INCL', $bInclAttrId)) {
                            throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include attribute id update', 'admin-update_class') . '.', 545);
                        }
                    }
                    if (Tools::getIsset('bt_combo-separator')) {
                        $sComboSeparator = Tools::getValue('bt_combo-separator');
                        if (!Configuration::updateValue('GMC_COMBO_SEPARATOR', $sComboSeparator)) {
                            throw new Exception(GMerchantCenter::$oModule->l('An error occurred during combo separator update', 'admin-update_class') . '.', 546);
                        }
                    }
                }
            }

            // how to use the product desc
            if (Tools::getIsset('bt_prod-desc-type')) {
                $iProdDescType = Tools::getValue('bt_prod-desc-type');
                if (!Configuration::updateValue('GMC_P_DESCR_TYPE', $iProdDescType)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during desc type update', 'admin-update_class') . '.', 528);
                }
            }

            // product availability
            if (Tools::getIsset('bt_incl-stock')) {
                $bInclStock = Tools::getValue('bt_incl-stock');
                if (!Configuration::updateValue('GMC_INC_STOCK', $bInclStock)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during product availability update', 'admin-update_class') . '.', 529);
                }
            }

            // include adult tag
            if (Tools::getIsset('bt_incl-tag-adult')) {
                $bInclAdultTag = Tools::getValue('bt_incl-tag-adult');
                if (!Configuration::updateValue('GMC_INC_TAG_ADULT', $bInclAdultTag)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include tag adult update', 'admin-update_class') . '.', 530);
                }
            }

            // include size tag
            if (Tools::getIsset('bt_incl-size')) {
                $sInclSize = Tools::getValue('bt_incl-size');
                $aSizeIds = Tools::getValue('bt_size-opt');
                if (!Configuration::updateValue('GMC_INC_SIZE', $sInclSize)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include size tag update', 'admin-update_class') . '.', 531);
                }

                // update attributes and the feature for size tag
                if (!empty($sInclSize) && !empty($aSizeIds)) {
                    if (!Configuration::updateValue('GMC_SIZE_OPT', serialize($aSizeIds))) {
                        throw new Exception(GMerchantCenter::$oModule->l('An error occurred during size IDs update', 'admin-update_class') . '.', 532);
                    }
                }
            }

            // include color tag
            if (Tools::getIsset('bt_incl-color')) {
                $sInclColor = Tools::getValue('bt_incl-color');
                $aColorIds = Tools::getValue('bt_color-opt');
                if (!Configuration::updateValue('GMC_INC_COLOR', $sInclColor)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include color tag update', 'admin-update_class') . '.', 533);
                }
                // update attributes and the feature for color tag
                if (!empty($sInclColor) && !empty($aColorIds)) {
                    if (!Configuration::updateValue('GMC_COLOR_OPT', serialize($aColorIds))) {
                        throw new Exception(GMerchantCenter::$oModule->l('An error occurred during color IDs update', 'admin-update_class') . '.', 534);
                    }
                }
            }

            /* USE CASE - update apparel feed options */
            // include material tag
            if (Tools::getIsset('bt_incl-material')) {
                $bInclMaterial = Tools::getValue('bt_incl-material');
                if (!Configuration::updateValue('GMC_INC_MATER', $bInclMaterial)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include material update', 'admin-update_class') . '.', 535);
                }
            }

            // include pattern tag
            if (Tools::getIsset('bt_incl-pattern')) {
                $bInclPattern = Tools::getValue('bt_incl-pattern');
                if (!Configuration::updateValue('GMC_INC_PATT', $bInclPattern)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include pattern update', 'admin-update_class') . '.', 536);
                }
            }

            // include gender tag
            if (Tools::getIsset('bt_incl-gender')) {
                $bInclGender = Tools::getValue('bt_incl-gender');
                if (!Configuration::updateValue('GMC_INC_GEND', $bInclGender)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include gender update', 'admin-update_class') . '.', 537);
                }
            }

            // include age group tag
            if (Tools::getIsset('bt_incl-age')) {
                $bInclAge = Tools::getValue('bt_incl-age');
                if (!Configuration::updateValue('GMC_INC_AGE', $bInclAge)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include age group update', 'admin-update_class') . '.', 538);
                }
            }

            // include size type
            if (Tools::getIsset('bt_incl-size_type')) {
                $bInclSizeType = Tools::getValue('bt_incl-size_type');
                if (!Configuration::updateValue('GMC_SIZE_TYPE', $bInclSizeType)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include size type update', 'admin-update_class') . '.', 541);
                }
            }

            // include size system
            if (Tools::getIsset('bt_incl-size_system')) {
                $bInclSizeSystem = Tools::getValue('bt_incl-size_system');
                if (!Configuration::updateValue('GMC_SIZE_SYSTEM', $bInclSizeSystem)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include size type update', 'admin-update_class') . '.', 541);
                }
            }

            // include exclusion destination
            if (Tools::getIsset('bt_excl_dest')) {
                $bExclDest = Tools::getValue('bt_excl_dest');
                if (!Configuration::updateValue('GMC_EXCLUDED_DEST', $bExclDest)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include excluded destinations update', 'admin-update_class') . '.', 545);
                }
            }

            // include exclusion destination
            if (Tools::getIsset('bt_excl_country')) {
                $bExclCountry = Tools::getValue('bt_excl_country');
                if (!Configuration::updateValue('GMC_EXCLUDED_COUNTRY', $bExclCountry)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during include excluded country update', 'admin-update_class') . '.', 546);
                }
            }

            /* USE CASE - update tax and shipping fees options */
            // include manage shipping
            if (Tools::getIsset('bt_manage-shipping')) {
                $bShippingUse = Tools::getValue('bt_manage-shipping');
                if (!Configuration::updateValue('GMC_SHIPPING_USE', $bShippingUse)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during shipping use update', 'admin-update_class') . '.', 539);
                }
            }

            if (Tools::getIsset('bt_manage-dimension')) {
                $bDimension = Tools::getValue('bt_manage-dimension');
                if (!Configuration::updateValue('GMC_DIMENSION', $bDimension)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during package dimensions option update', 'admin-update_class') . '.', 541);
                }
            }

            if (Tools::getIsset('bt_ship-carriers')) {
                $aShippingCarriers = array();
                $aPostShippingCarriers = Tools::getValue('bt_ship-carriers');

                if (
                    !empty($aPostShippingCarriers)
                    && is_array($aPostShippingCarriers)
                ) {
                    foreach ($aPostShippingCarriers as $iKey => $mVal) {
                        $aShippingCarriers[$iKey] = $mVal;
                    }
                    $sShippingCarriers = serialize($aShippingCarriers);
                } else {
                    $sShippingCarriers = '';
                }
                if (!Configuration::updateValue('GMC_SHIP_CARRIERS', $sShippingCarriers)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during shipping carriers update', 'admin-update_class') . '.', 540);
                }
            }

            // update attributes and the feature for size tag
            if (Tools::getIsset('hiddenProductIds')) {
                $sExcludedIds = Tools::getValue('hiddenProductIds');

                // get an array of
                $aExcludedIds = !empty($sExcludedIds) ? explode('-', $sExcludedIds) : array();

                if (!empty($aExcludedIds)) {
                    array_pop($aExcludedIds);
                }

                if (!Configuration::updateValue('GMC_PROD_EXCL', serialize($aExcludedIds))) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during excluded product IDs update', 'admin-update_class') . '.', 541);
                }
            }


            if (Tools::getIsset('hiddenProductFreeShippingIds')) {
                $sFreeShippingProductIds = Tools::getValue('hiddenProductFreeShippingIds');

                // get an array of
                $aIdsFreeShipping = !empty($sFreeShippingProductIds) ? explode('-', $sFreeShippingProductIds) : array();

                if (!empty($sFreeShippingProductIds)) {
                    array_pop($aIdsFreeShipping);
                }

                if (!Configuration::updateValue('GMC_FREE_SHIP_PROD', serialize($aIdsFreeShipping))) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during excluded product IDs update', 'admin-update_class') . '.', 542);
                }
            }

            // select the order to check the EAN-13 or UPC
            if (Tools::getIsset('bt_gtin-pref')) {
                $sGtinPref = Tools::getValue('bt_gtin-pref');
                if (!Configuration::updateValue('GMC_GTIN_PREF', $sGtinPref)) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during gtin preference update', 'admin-update_class') . '.', 543);
                }
            }

            if (Tools::getValue('sDisplay') == 'tax') {
                // update feed tax
                $aTmpFeedTax = Tools::getValue('bt_feed-tax') != false ? Tools::getValue('bt_feed-tax') : array();
                $aFeedTaxHidden = Tools::getValue('bt_feed-tax-hidden');

                foreach ($aFeedTaxHidden as $sFeed) {
                    $aFeedTax[$sFeed] = in_array($sFeed, $aTmpFeedTax) ? 1 : 0;
                }

                if (!Configuration::updateValue('GMC_FEED_TAX', serialize($aFeedTax))) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during feed tax selection', 'admin-update_class') . '.', 543);
                }
            }

            Configuration::updateValue('GMC_CONF_STEP_2', 1);
        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration(array(
            'GMC_COLOR_OPT',
            'GMC_SIZE_OPT',
            'GMC_SHIP_CARRIERS',
            'GMC_PROD_EXCL',
            'GMC_FREE_SHIP_PROD',
            'GMC_FEED_TAX'
        ));

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with feed management settings updated
        $aDisplay = BT_AdminDisplay::create()->run('feed');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        return $aDisplay;
    }

    /**
     * update feed list settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateFeedList(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        try {
            // update cron export
            $aCronExport = Tools::getValue('bt_cron-export');
            if (!Configuration::updateValue('GMC_CHECK_EXPORT', serialize($aCronExport))) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during color IDs update', 'admin-update_class') . '.', 550);
            }
        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration(array('GMC_CHECK_EXPORT', 'GMC_FEED_TAX'));

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with feed management settings updated
        $aDisplay = BT_AdminDisplay::create()->run('feedList');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        return $aDisplay;
    }

    /**
     * update advanced tag settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateTag(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aAssign = array();
        $aCategoryList = array();

        try {
            // include
            require_once(_GMC_PATH_LIB . 'module-dao_class.php');

            /* USE CASE - handle all tags configured */
            foreach ($GLOBALS[_GMC_MODULE_NAME . '_TAG_LIST'] as $sTagType) {
                if (
                    !empty($aPost[$sTagType])
                    && is_array($aPost[$sTagType])
                ) {
                    if ($sTagType != 'excluded_destination' && $sTagType != 'excluded_country') {
                        foreach ($aPost[$sTagType] as $iCatId => $mVal) {
                            $aCategoryList[$iCatId][$sTagType] = strip_tags($mVal);
                        }
                    } else { // Use for excluded destination this a multiple select
                        if ($sTagType == 'excluded_destination') {
                            foreach ($aPost['excluded_destination'] as $iCatId => $mVal) {
                                $aCategoryList[$iCatId][$sTagType] = strip_tags(implode(' ', $mVal));
                            }
                        }
                        if ($sTagType == 'excluded_country') {
                            foreach ($aPost['excluded_country'] as $iCatId => $mVal) {
                                $aCategoryList[$iCatId][$sTagType] = strip_tags(implode(' ', $mVal));
                            }
                        }
                    }
                }
            }

            // delete all features
            BT_GmcModuleDao::deleteFeatureByCat(GMerchantCenter::$iShopId);

            if (!empty($aCategoryList)) {
                foreach ($aCategoryList as $iCatId => $aValues) {
                    BT_GmcModuleDao::insertFeatureByCat($iCatId, $aValues, GMerchantCenter::$iShopId);
                }
            }
        } catch (Exception $e) {
            $aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // check update OK
        $aAssign['bUpdate'] = empty($aAssign['aErrors']) ? true : false;
        $aAssign['sErrorInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_ERROR);

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_ADVANCED_TAG_UPD,
            'assign' => $aAssign,
        );
    }

    /**
     * update custom label settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateLabel(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aAssign = array();

        try {
            // include
            require_once(_GMC_PATH_LIB . 'module-dao_class.php');

            // get the label name
            $sLabelName = Tools::getValue('bt_label-name');
            $iTagId = Tools::getValue('bt_tag-id');
            $sLabelType = Tools::getValue('bt_custom-type');

            // if empty label name
            if (empty($sLabelName)) {
                throw new Exception(GMerchantCenter::$oModule->l('You haven\'t filled out the label name', 'admin-update_class') . '.', 560);
            } else {
                // use case - update tag
                if (!empty($iTagId)) {
                    BT_GmcModuleDao::updateGmcTag($iTagId, $sLabelName, $sLabelType);
                    foreach ($GLOBALS[_GMC_MODULE_NAME . '_LABEL_LIST'] as $sTableName => $sFieldType) {
                        // delete related tables
                        BT_GmcModuleDao::deleteGmcCatTag($iTagId, $sTableName);
                    }
                } // use case - create tag
                else {
                    $iTagId = BT_GmcModuleDao::insertGmcTag(GMerchantCenter::$iShopId, $sLabelName, $sLabelType);
                }
                // use case - insert
                foreach ($GLOBALS[_GMC_MODULE_NAME . '_LABEL_LIST'] as $sTableName => $sFieldType) {
                    if (Tools::getIsset('bt_' . $sFieldType . '-box')) {
                        $aSelectedIds = Tools::getValue('bt_' . $sFieldType . '-box');

                        foreach ($aSelectedIds as $iSelectedId) {
                            BT_GmcModuleDao::insertGmcCatTag($iTagId, $iSelectedId, $sTableName, $sFieldType);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // check update OK
        $aAssign['bUpdate'] = empty($aAssign['aErrors']) ? true : false;
        $aAssign['sErrorInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_ERROR);

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_CUSTOM_LABEL_UPD,
            'assign' => $aAssign,
        );
    }

    /**
     * update google settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateGoogle(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        try {
            // add google UTM campaign
            $sUtmCampaign = Tools::getValue('bt_utm-campaign');
            if (!Configuration::updateValue('GMC_UTM_CAMPAIGN', $sUtmCampaign)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during utm campaign update','admin-update_class') . '.', 570);
            }

            // add google UTM source
            $sUtmSource = Tools::getValue('bt_utm-source');
            if (!Configuration::updateValue('GMC_UTM_SOURCE', $sUtmSource)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during utm source update','admin-update_class') . '.', 571);
            }

            // add google UTM medium
            $sUtmMedium = Tools::getValue('bt_utm-medium');
            if (!Configuration::updateValue('GMC_UTM_MEDIUM', $sUtmMedium)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during utm medium update','admin-update_class') . '.', 572);
            }

            // add google UTM content
            $sUtmContent = Tools::getValue('bt_utm_content');
            if (!Configuration::updateValue('GMC_UTM_CONTENT', $sUtmContent)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during utm content update', 'admin-update_class') . '.', 573);
            }

        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration(array(
            'GMC_COLOR_OPT',
            'GMC_SIZE_OPT',
            'GMC_SHIP_CARRIERS'
        ));

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with feed management settings updated
        $aDisplay = BT_AdminDisplay::create()->run('google');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        return $aDisplay;
    }

    /**
     * update google categories matching
     *
     * @param array $aPost
     * @return array
     */
    private function updateGoogleCategoriesMatching(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aAssign = array();

        try {
            $iLangId = Tools::getValue('iLangId');
            $sLangIso = Tools::getValue('sLangIso');
            $aGoogleCategory = Tools::getValue('bt_google-cat');

            if (
                empty($sLangIso)
                || !Language::getIsoById((int) $iLangId)
            ) {
                throw new Exception(GMerchantCenter::$oModule->l('Invalid language parameters', 'admin-update_class') . '.', 580);
            }
            if (!is_array($aGoogleCategory)) {
                throw new Exception(GMerchantCenter::$oModule->l('Your matching Google categories is not a valid array', 'admin-update_class') . '.', 581);
            }
            // include
            require_once(_GMC_PATH_LIB . 'module-dao_class.php');

            // delete previous google matching categories
            if (BT_GmcModuleDao::deleteGoogleCategory(GMerchantCenter::$iShopId, $sLangIso)) {
                foreach ($aGoogleCategory as $iShopCatId => $sGoogleCat) {
                    if (!empty($sGoogleCat)) {
                        // insert each category
                        BT_GmcModuleDao::insertGoogleCategory(GMerchantCenter::$iShopId, $iShopCatId, $sGoogleCat, $sLangIso);
                    }
                }
            }
        } catch (Exception $e) {
            $aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // check update OK
        $aAssign['bUpdate'] = empty($aAssign['aErrors']) ? true : false;
        $aAssign['sErrorInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_ERROR);

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_CATEGORY_UPD,
            'assign' => $aAssign,
        );
    }


    /**
     * update reporting settings
     *
     * @param array $aPost
     * @return array
     */
    private function updateReporting(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        try {
            // register reporting mode
            $bReporting = Tools::getValue('bt_reporting');
            if (!Configuration::updateValue('GMC_REPORTING', $bReporting)) {
                throw new Exception(GMerchantCenter::$oModule->l('An error occurred during reporting update', 'admin-update_class') . '.', 590);
            }
        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration();

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with feed management settings updated
        $aDisplay = BT_AdminDisplay::create()->run('reporting');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        return $aDisplay;
    }


    /**
     * update the google categories by sync action
     *
     * @param array $aPost
     * @return array
     */
    private function updateGoogleCategoriesSync(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aAssign = array();

        try {
            // include
            require_once(_GMC_PATH_LIB . 'module-dao_class.php');

            $sLangIso = Tools::getValue('sLangIso');
            if ($sLangIso != false) {
                // Get and check content is here
                $sContent = BT_GmcModuleTools::getGoogleFile(_GMC_GOOGLE_TAXONOMY_URL . 'taxonomy.' . $sLangIso . '.txt');

                // use case - the Google file content is KO
                if (!$sContent || Tools::strlen($sContent) == 0) {
                    throw new Exception(GMerchantCenter::$oModule->l('An error occurred during the Google file get content', 'admin-update_class') . '.', 591);
                } else {
                    // Convert to array and check all is still OK
                    $aLines = explode("\n", trim($sContent));

                    // use case - wrong format
                    if (!$aLines || !is_array($aLines)) {
                        throw new Exception(GMerchantCenter::$oModule->l('The Google taxonomy file content is not formatted well', 'admin-update_class') . '.', 592);
                    } else {
                        // Delete past data
                        Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_taxonomy` WHERE `lang` = "' . pSQL($sLangIso) . '"');

                        // Re-insert
                        foreach ($aLines as $index => $sLine) {
                            // First line is the version number, so skip it
                            if ($index > 0) {
                                $sQuery = 'INSERT INTO `' . _DB_PREFIX_ . 'gmc_taxonomy` (`value`, `lang`) VALUES ("' . pSQL($sLine) . '", "' . pSQL($sLangIso) . '")';
                                Db::getInstance()->Execute($sQuery);
                            }
                        }
                    }
                }
                $aAssign['aCountryTaxonomies'] = BT_GmcModuleDao::getAvailableTaxonomyCountries($GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES']);

                foreach ($aAssign['aCountryTaxonomies'] as $sIsoCode => &$aTaxonomy) {
                    $aTaxonomy['countryList'] = implode(', ', $aTaxonomy['countries']);
                    $aTaxonomy['currentUpdated'] = $sLangIso == $sIsoCode ? true : false;
                    $aTaxonomy['updated'] = BT_GmcModuleDao::checkTaxonomyUpdate($sIsoCode);
                }
            } else {
                throw new Exception(GMerchantCenter::$oModule->l('The server has returned an unsecure request error (wrong parameters)!', 'admin-update_class') . '.', 593);
            }
        } catch (Exception $e) {
            $aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // check update OK
        $aAssign['bUpdate'] = empty($aAssign['aErrors']) ? true : false;
        $aAssign['sURI'] = BT_GmcModuleTools::truncateUri(array('&sAction'));
        $aAssign['sCtrlParamName'] = _GMC_PARAM_CTRL_NAME;
        $aAssign['sController'] = _GMC_ADMIN_CTRL;
        $aAssign['aQueryParams'] = $GLOBALS[_GMC_MODULE_NAME . '_REQUEST_PARAMS'];
        $aAssign['iCurrentLang'] = intval(GMerchantCenter::$iCurrentLang);
        $aAssign['sCurrentLang'] = GMerchantCenter::$sCurrentLang;

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_GOOGLE_CATEGORY_LIST,
            'assign' => $aAssign,
        );
    }


    /**
     * update the XML file
     *
     * @param array $aPost
     * @return array
     */
    private function updateXml(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aAssign = array();

        try {
            $iShopId = Tools::getValue('iShopId');
            $sFilename = Tools::getValue('sFilename');
            $iLangId = Tools::getValue('iLangId');
            $sLangIso = Tools::getValue('sLangIso');
            $sCountryIso = Tools::getValue('sCountryIso');
            $sCurrencyIso = Tools::getValue('sCurrencyIso');
            $iFloor = Tools::getValue('iFloor');
            $iTotal = Tools::getValue('iTotal');
            $iProcess = Tools::getValue('iProcess');

            if (($iShopId != false && is_numeric($iShopId))
                && ($sFilename != false && is_string($sFilename))
                && ($iLangId != false && is_numeric($iLangId))
                && ($sLangIso != false && is_string($sLangIso))
                && ($sCountryIso != false && is_string($sCountryIso))
                && ($sCurrencyIso != false && is_string($sCurrencyIso))
                && ($iFloor !== false && is_numeric($iFloor))
                && ($iTotal != false && is_numeric($iTotal))
                && ($iProcess !== false && is_numeric($iProcess))
            ) {
                $_POST['iShopId'] = $iShopId;
                $_POST['sFilename'] = $sFilename;
                $_POST['iLangId'] = $iLangId;
                $_POST['sLangIso'] = $sLangIso;
                $_POST['sCountryIso'] = Tools::strtoupper($sCountryIso);
                $_POST['sCurrencyIso'] = Tools::strtoupper($sCurrencyIso);
                $_POST['iFloor'] = $iFloor;
                $_POST['iStep'] = GMerchantCenter::$conf['GMC_AJAX_CYCLE'];
                $_POST['iTotal'] = $iTotal;
                $_POST['iProcess'] = $iProcess;

                // require admin configure class - to factorise
                require_once(_GMC_PATH_LIB_ADMIN . 'admin-generate_class.php');

                // exec the generate class to generate the XML files
                $aGenerate = BT_AdminGenerate::create()->run(
                    'xml',
                    array('reporting' => GMerchantCenter::$conf['GMC_REPORTING'])
                );

                if (empty($aGenerate['assign']['aErrors'])) {
                    $aAssign['status'] = 'ok';
                    $aAssign['counter'] = $iFloor + $_POST['iStep'];
                    $aAssign['process'] = $aGenerate['assign']['process'];
                } else {
                    $aAssign['status'] = 'ko';
                    $aAssign['error'] = $aGenerate['assign']['aErrors'];
                }
            } else {
                $sMsg = GMerchantCenter::$oModule->l('The server has returned an unsecure request error (wrong parameters)! Please check each parameter by comparing type and value below!', 'admin-update_class') . '.' . "<br/>";
                $sMsg .= GMerchantCenter::$oModule->l('Shop ID', 'admin-update_class') . ': ' . $iShopId . "<br/>"
                    . GMerchantCenter::$oModule->l('File name', 'admin-update_class') . ': ' . $sFilename . "<br/>"
                    . GMerchantCenter::$oModule->l('Language ID', 'admin-update_class') . ': ' . $iLangId . "<br/>"
                    . GMerchantCenter::$oModule->l('Language ISO', 'admin-update_class') . ': ' . $sLangIso . "<br/>"
                    . GMerchantCenter::$oModule->l('country ISO', 'admin-update_class') . ': ' . $sCountryIso . "<br/>"
                    . GMerchantCenter::$oModule->l('Step', 'admin-update_class') . ': ' . $iFloor . "<br/>"
                    . GMerchantCenter::$oModule->l(
                        'Total products to process',
                        'admin-update_class'
                    ) . ': ' . $iTotal . "<br/>"
                    . GMerchantCenter::$oModule->l(
                        'Total products to process (without counting combinations)',
                        'admin-update_class'
                    ) . ': ' . $iTotal . "<br/>"
                    . GMerchantCenter::$oModule->l(
                        'Stock the real number of products to process',
                        'admin-update_class'
                    ) . ': ' . $iProcess . "<br/>";

                throw new Exception($sMsg, 594);
            }
        } catch (Exception $e) {
            $aAssign['status'] = 'ko';
            $aAssign['error'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // force xhr mode
        GMerchantCenter::$sQueryMode = 'xhr';

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_GENERATE,
            'assign' => array('json' => BT_GmcModuleTools::jsonEncode($aAssign)),
        );
    }

    /**
     * check and update lang of multi-language fields
     *
     * @param array $aPost : params
     * @param string $sFieldName : field name linked to the translation value
     * @param string $sGlobalName : name of GLOBAL variable to get value
     * @param bool $bCheckOnly
     * @param string $sErrorDisplayName
     * @return array
     */
    private function updateLang(array $aPost, $sFieldName, $sGlobalName, $bCheckOnly = false, $sErrorDisplayName = '')
    {
        // check title in each active language
        $aLangs = array();

        foreach (Language::getLanguages() as $nKey => $aLang) {
            if (empty($aPost[$sFieldName . '_' . $aLang['id_lang']])) {
                $sException = GMerchantCenter::$oModule->l('One title of', 'admin-update_class')
                    . ' " ' . (!empty($sErrorDisplayName) ? $sErrorDisplayName : $sFieldName) . ' " '
                    . GMerchantCenter::$oModule->l('have not been filled', 'admin-update_class')
                    . '.';
                throw new Exception($sException, 595);
            } else {
                $aLangs[$aLang['id_lang']] = strip_tags($aPost[$sFieldName . '_' . $aLang['id_lang']]);
            }
        }
        if (!$bCheckOnly) {
            // update titles
            if (!Configuration::updateValue($sGlobalName, serialize($aLangs))) {
                $sException = GMerchantCenter::$oModule->l('An error occurred during', 'admin-update_class')
                    . ' " ' . $sGlobalName . ' " '
                    . GMerchantCenter::$oModule->l('update', 'admin-update_class')
                    . '.';
                throw new Exception($sException, 596);
            }
        }
        return $aLangs;
    }

    /**
     * set singleton
     * @return obj
     */
    public static function create()
    {
        static $oUpdate;

        if (null === $oUpdate) {
            $oUpdate = new BT_AdminUpdate();
        }
        return $oUpdate;
    }
}
