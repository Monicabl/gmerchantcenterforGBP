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

class BT_AdminGenerate implements BT_IAdmin
{
    /**
     * @var array $aParamsForXml : array for all parameters provided to generate XMl files
     */
    protected static $aParamsForXml = array();


    /**
     * generate data feed content
     *
     * @param string $sType => define which method to execute
     * @param array $aParam
     * @return array
     */
    public function run($sType, array $aParam = null)
    {
        // set variables
        $aData = array();

        switch ($sType) {
            case 'xml' : // use case - generate XML file
            case 'flyOutput' : // use case - generate XML file on fly output
            case 'cron' : // use case - generate XML file via the cron execution
                // execute match function
                $aData = call_user_func_array(array($this, 'generate' . ucfirst($sType)), array($aParam));
                break;
            default :
                break;
        }
        return $aData;
    }

    /**
     * generate an XML file
     *
     * @param array $aPost
     * @return array
     */
    private function generateXml(array $aPost = null)
    {
        // set
        $aAssign = array();

        if (empty(self::$aParamsForXml)) {
            self::$aParamsForXml = $GLOBALS[_GMC_MODULE_NAME . '_PARAM_FOR_XML'];
        }

        try {
            foreach (self::$aParamsForXml as $sParamName) {
                $mValue = Tools::getValue($sParamName);
                if (Tools::getValue($sParamName) !== false) {
                    $$sParamName = $mValue;
                } else {
                    throw new Exception(GMerchantCenter::$oModule->l('One or more of the required parameters are not provided, please check the list in the current class','admin-generate_class') . '.', 800);
                }
            }

            // detect if we force the reporting or not
            $bForceReporting = Tools::getValue('bReporting');
            $bForceReporting = ($bForceReporting !== false) ? $bForceReporting : GMerchantCenter::$conf['GMC_REPORTING'];

            // include
            require_once(_GMC_PATH_LIB . 'module-reporting_class.php');
            require_once(_GMC_PATH_LIB . 'module-dao_class.php');
            require_once(_GMC_PATH_LIB_COMMON . 'file.class.php');
            require_once(_GMC_PATH_LIB_XML . 'xml-strategy_class.php');

            // handle excluded products list
            $aExcludedProducts = array();

            if (!empty(GMerchantCenter::$conf['GMC_PROD_EXCL'])) {
                if (is_string(GMerchantCenter::$conf['GMC_PROD_EXCL'])) {
                    GMerchantCenter::$conf['GMC_PROD_EXCL'] = unserialize(GMerchantCenter::$conf['GMC_PROD_EXCL']);
                }
                foreach (GMerchantCenter::$conf['GMC_PROD_EXCL'] as $sProdIds) {
                    list($iProdId, $iAttrId) = explode('¤', $sProdIds);
                    $aExcludedProducts[$iProdId][] = $iAttrId;
                }
            }

            $aFreeShippingProducts = array();

            if (!empty(GMerchantCenter::$conf['GMC_FREE_SHIP_PROD'])) {
                if (is_string(GMerchantCenter::$conf['GMC_FREE_SHIP_PROD'])) {
                    GMerchantCenter::$conf['GMC_FREE_SHIP_PROD'] = unserialize(GMerchantCenter::$conf['GMC_FREE_SHIP_PROD']);
                }
                foreach (GMerchantCenter::$conf['GMC_FREE_SHIP_PROD'] as $sProdIds) {
                    list($iProdId, $iAttrId) = explode('¤', $sProdIds);
                    $aFreeShippingProducts[$iProdId][] = $iAttrId;
                }
            }

            // set params
            $aParams = array(
                'bExport' => GMerchantCenter::$conf['GMC_EXPORT_MODE'],
                'iShopId' => !empty($iShopId) ? (int)$iShopId : GMerchantCenter::$iShopId,
                'iLangId' => (int)$iLangId,
                'sLangIso' => $sLangIso,
                'sCountryIso' => $sCountryIso,
                'sCurrencyIso' => $sCurrencyIso,
                'sGmcLink' => GMerchantCenter::$conf['GMC_LINK'],
                'iFloor' => (int)$iFloor,
                'iStep' => (int)$iStep,
                'iTotal' => (int)$iTotal,
                'iProcess' => (int)$iProcess,
                'bOutput' => Tools::getValue('bOutput'),
                'excluded' => $aExcludedProducts,
                'freeShipping' => $aFreeShippingProducts,
                'bUseTax' => BT_GmcModuleTools::isTax($sLangIso, $sCountryIso, $sCurrencyIso)
            );

            // get the XMl strategy
            $oXmlStrategy = new BT_XmlStrategy($aParams);

            // composition of File Obj into XMlStrategy
            $oXmlStrategy->setFile(BT_GmcFile::create());

            // check if reporting is activated
            BT_GmcReporting::create($bForceReporting)->setFileName(_GMC_REPORTING_DIR . 'reporting-' . $sLangIso . '-' . Tools::strtolower($sCountryIso) . '-' . $sCurrencyIso . '.txt');

            // detect if this is the first step
            if ((int)$iFloor == 0) {
                // reset the reporting file
                BT_GmcReporting::create()->writeFile('', 'w');

                // reset the XMl file
                $oXmlStrategy->write(_GMC_SHOP_PATH_ROOT . $sFilename, '');

                // create header
                $oXmlStrategy->header();
            }

            // load products
            $aProducts = $oXmlStrategy->loadProduct();

            foreach ($aProducts as $aProduct) {
                // get the instance of the product
                $oProduct = new Product((int)($aProduct['id']), true, (int)$iLangId);

                // check if validate product
                if (Validate::isLoadedObject($oProduct)
                    && $oProduct->active
                    && ((isset($oProduct->available_for_order)
                    && $oProduct->available_for_order)
                    || empty($oProduct->available_for_order))
                ) {
                    // define the strategy
                    $sXmlProductType = $oProduct->hasAttributes() && !empty(GMerchantCenter::$conf['GMERCHANTCENTER_P_COMBOS']) ? 'Combination' : 'Product';

                    // set the matching object
                    $oXmlStrategy->get($sXmlProductType, $aParams);

                    // check if combinations
                    $aCombinations = $oXmlStrategy->hasCombination($oProduct->id);

                    foreach ($aCombinations as $aCombination) {
                        $oXmlStrategy->buildProductXml($oXmlStrategy->data, $oProduct, $aCombination);
                    }
                }
            }

            // get the number of products really processed
            $aAssign['process'] = (int)($iProcess + $oXmlStrategy->getProcessedProduct());

            // detect if the last step
            if (((int)$iFloor + (int)$iStep) >= $iTotal) {
                $oXmlStrategy->footer();

                // store the nb of products really processed by the export action
                BT_GmcReporting::create()->set('counter', array('products' => $aAssign['process']));

                // define the status of the feed generation
                $aAssign['bContinueStatus'] = false;
                $aAssign['bFinishStatus'] = true;
            } else {
                // define the status of the feed generation
                $aAssign['bContinueStatus'] = true;
                $aAssign['bFinishStatus'] = false;
            }

            // write
            $oXmlStrategy->write(_GMC_SHOP_PATH_ROOT . $sFilename, $oXmlStrategy->sContent, false, true);

            // merge reporting file's content + current reporting
            $aReporting = BT_GmcReporting::create()->mergeData();

            // write reporting file by country and currency
            if (!empty($aReporting)) {
                $bWritten = BT_GmcReporting::create()->writeFile($aReporting, 'w');
            }
        } catch (Exception $e) {
            $aErrorParam = array('msg' => $e->getMessage(), 'code' => $e->getCode());

            if (_GMC_DEBUG) {
                $aErrorParam['file'] = $e->getFile();
                $aErrorParam['trace'] = $e->getTraceAsString();
            }
            $aAssign['aErrors'][] = $aErrorParam;
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_GENERATE_OUTPUT,
            'assign' => $aAssign,
        );
    }

    /**
     * generate the XML feed by the fly output
     *
     * @param array $aPost
     * @return array
     */
    private function generateFlyOutput(array $aPost = null)
    {
        $aAssign = array();

        try {
            // get the token
            $sToken = Tools::getValue('token');

            if (!empty(GMerchantCenter::$conf['GMC_FEED_TOKEN'])
                && $sToken != GMerchantCenter::$conf['GMC_FEED_TOKEN']
            ) {
                throw new Exception(GMerchantCenter::$oModule->l('Invalid security token','admin-generate_class') . '.', 810);
            }

            // get data feed params
            $_POST['iShopId'] = !empty(Tools::getValue('id_shop')) ? (int)Tools::getValue('id_shop') : GMerchantCenter::$iShopId ;
            $_POST['iLangId'] = !empty(Tools::getValue('gmc_lang_id'))  ? Tools::getValue('gmc_lang_id') : Tools::getValue('id_lang');
            $_POST['sLangIso'] = BT_GmcModuleTools::getLangIso($_POST['iLangId']);
            $_POST['sCountryIso'] = Tools::getValue('country');
            $_POST['sCurrencyIso'] = Tools::getValue('currency_iso');
            $_POST['iFloor'] = 0;
            $_POST['iTotal'] = 0;
            $_POST['iStep'] = 0;
            $_POST['iProcess'] = 0;
            $_POST['bOutput'] = 1;

            // set the filename
            $sFileSuffix = BT_GmcModuleTools::buildFileSuffix($_POST['sLangIso'], $_POST['sCountryIso'], $_POST['sCurrencyIso']);
            $_POST['sFilename'] = GMerchantCenter::$sFilePrefix . '.' . $sFileSuffix . '.xml';

            // execute the generate XML function
            $this->generateXml();
        } catch (Exception $e) {
            $aAssign['sErrorInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_ERROR);
            $aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_GENERATE_OUTPUT,
            'assign' => $aAssign,
        );
    }


    /**
     * generate the XML feed by the cron execution
     *
     * @param array $aPost
     * @return array
     */
    private function generateCron(array $aPost = null)
    {
        $aAssign = array();
        $aLocalisation = array();

        try {
            // get the token
            $sToken = Tools::getValue('token');
            // use case - individual data feed cron
            $sCountry = Tools::getValue('country');
            $iLang = Tools::getValue('gmc_lang_id');
            $sCurrency = Tools::getValue('currency_iso');

            // get the token if necessary
            if (!empty(GMerchantCenter::$conf['GMC_FEED_TOKEN'])
                && $sToken != GMerchantCenter::$conf['GMC_FEED_TOKEN']
            ) {
                throw new Exception(GMerchantCenter::$oModule->l('Invalid security token', 'admin-generate_class') . '.', 820);
            }

            // check if this is the first time execution of the CRON
            $_POST['aLangIds'] = Tools::getValue('aLangIds');
            $_POST['iShopId'] = !empty(Tools::getValue('id_shop')) ? (int)Tools::getValue('id_shop') : GMerchantCenter::$iShopId;
            $_POST['sCurrencyIso'] = Tools::getValue('currency_iso');

            // first execution
            if (empty($_POST['aLangIds'])) {
                // for cron per feed
                if (!empty($sCountry)
                    && !empty($iLang)
                ) {
                    $aDataFeedCron[] = BT_GmcModuleTools::getLangIso($iLang) . '_' . $sCountry . '_' . $sCurrency;
                } // use case - the general data feed cron URL
                else {
                    // get selected data feed
                    $aDataFeedCron = GMerchantCenter::$conf['GMC_CHECK_EXPORT'];
                }

                foreach ($aDataFeedCron as $iKey => &$sLangIso) {
                    $sLangIso = Tools::strtolower($sLangIso);
                }

                // set the available data feed
                foreach (GMerchantCenter::$aAvailableLanguages as $aLanguage) {
                    // set the cookie id lang to get the good language
                    Context::getContext()->cookie->id_lang = $aLanguage['id_lang'];

                    // get the matching languages
                    foreach ($GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES'][$aLanguage['iso_code']] as $sCountryIso => $aLocaleData) {
                        // Only if currency is installed
                        foreach ($aLocaleData['currency'] as $iIndex => $sCurrency) {
                            if (in_array(Tools::strtolower($aLanguage['iso_code'] . '_' . $sCountryIso . '_' . $sCurrency), $aDataFeedCron)
                                && Currency::getIdByIsoCode($sCurrency)
                            ) {
                                $aLocalisation[] = $aLanguage['iso_code'] . '_' . $sCountryIso . '_' . $sCurrency . '_' . ($iIndex == 0 ? '1' : '0');
                            }
                        }
                    }
                }

                require_once(_GMC_PATH_LIB . 'module-dao_class.php');

                if (!empty($aLocalisation[0])) {
                    list($sLangIso, $sCountryIso, $sCurrencyIso, $bCurrencyFirst) = explode('_', $aLocalisation[0]);
                    $_POST['iLangId'] = BT_GmcModuleTools::getLangId($sLangIso);
                    $_POST['iCurrentLang'] = 0;
                    $_POST['sLangIso'] = $sLangIso;
                    $_POST['sCountryIso'] = $sCountryIso;
                    $_POST['sCurrencyIso'] = $sCurrencyIso;
                    $_POST['bCurrencyFirst'] = $bCurrencyFirst;
                    $_POST['iStep'] = GMerchantCenter::$conf['GMC_AJAX_CYCLE'];
                    $_POST['iFloor'] = 0;
                    $_POST['iProcess'] = 0;
                    // get the total products to export
                    $_POST['iTotal'] = BT_GmcModuleDao::getProductIds($_POST['iShopId'], (int)GMerchantCenter::$conf['GMC_EXPORT_MODE'], true);

                    // set the filename
                    $sFileSuffix = BT_GmcModuleTools::buildFileSuffix($_POST['sLangIso'], $_POST['sCountryIso'], (!empty($_POST['bCurrencyFirst']) ? '' : $_POST['sCurrencyIso']), $_POST['iShopId']);
                    $_POST['sFilename'] = GMerchantCenter::$sFilePrefix . '.' . $sFileSuffix . '.xml';
                }

                // get lang
                $_POST['aLangIds'] = $aLocalisation;
            } else {
                $_POST['iCurrentLang'] = Tools::getValue('iCurrentLang');
                $_POST['aLangIds'] = Tools::getValue('aLangIds');

                list($sLangIso, $sCountryIso, $sCurrencyIso, $bCurrencyFirst) = explode('_',
                    $_POST['aLangIds'][$_POST['iCurrentLang']]);

                // get data feed params
                $_POST['iLangId'] = BT_GmcModuleTools::getLangId($sLangIso);
                $_POST['sLangIso'] = $sLangIso;
                $_POST['sCountryIso'] = $sCountryIso;
                $_POST['sCurrencyIso'] = $sCurrencyIso;
                $_POST['bCurrencyFirst'] = $bCurrencyFirst;
                $_POST['iFloor'] = Tools::getValue('iFloor');
                $_POST['iTotal'] = Tools::getValue('iTotal');
                $_POST['iStep'] = Tools::getValue('iStep');
                $_POST['iProcess'] = Tools::getValue('iProcess');

                // set the filename
                $sFileSuffix = BT_GmcModuleTools::buildFileSuffix($_POST['sLangIso'], $_POST['sCountryIso'], (!empty($_POST['bCurrencyFirst']) ? '' : $_POST['sCurrencyIso']), $_POST['iShopId']);
                $_POST['sFilename'] = GMerchantCenter::$sFilePrefix . '.' . $sFileSuffix . '.xml';
            }

            // execute the generate XML function
            $aContent = $this->generateXml();

            if (empty($aContent['assign']['aErrors'])) {
                // handle the cron URL
                $sCronUrl =  Context::getContext()->link->getModuleLink(_GMC_MODULE_SET_NAME, _GMC_CTRL_CRON, array('id_shop' =>  GMerchantCenter::$iShopId));

                // check if the feed protection is activated
                if (!empty($sToken)) {
                    $sCronUrl .= '&token=' . $sToken;
                }

                // set the base cron URL
                $sCronUrl .= '&aLangIds[]=' . implode('&aLangIds[]=', $_POST['aLangIds'])
                    . '&iTotal=' . (int)$_POST['iTotal']
                    . '&iStep=' . (int)$_POST['iStep'];

                if (!empty($aContent['assign']['bContinueStatus'])
                    && empty($aContent['assign']['bFinishStatus'])
                ) {
                    $_POST['iFloor'] += $_POST['iStep'];
                    $_POST['iProcess'] = $aContent['assign']['process'];
                    // header location
                    header("Location: " . $sCronUrl . '&iCurrentLang=' . $_POST['iCurrentLang'] . '&iFloor=' . $_POST['iFloor'] . '&iProcess=' . $_POST['iProcess']);
                    exit(0);
                } elseif (empty($aContent['assign']['bContinueStatus'])
                    && !empty($aContent['assign']['bFinishStatus'])
                    && isset($_POST['aLangIds'][$_POST['iCurrentLang'] + 1])
                ) {
                    // header location
                    header("Location: " . $sCronUrl . '&iCurrentLang=' . ($_POST['iCurrentLang'] + 1) . '&iFloor=0&iProcess=0');
                    exit(0);
                }
            }
        } catch (Exception $e) {
            $aAssign['sErrorInclude'] = BT_GmcModuleTools::getTemplatePath(_GMC_PATH_TPL_NAME . _GMC_TPL_ADMIN_PATH . _GMC_TPL_ERROR);
            $aAssign['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        return array(
            'tpl' => _GMC_TPL_ADMIN_PATH . _GMC_TPL_FEED_GENERATE_OUTPUT,
            'assign' => $aAssign,
        );
    }

    /**
     * set singleton
     *
     * @return obj
     */
    public static function create()
    {
        static $oGenerate;

        if (null === $oGenerate) {
            $oGenerate = new BT_AdminGenerate();
        }
        return $oGenerate;
    }
}
