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

class BT_GmcModuleUpdate
{
    /**
     * @var $aErrors : store errors
     */
    protected $aErrors = array();

    /**
     * execute required function
     *
     * @param string $sType
     * @param array $aParam
     */
    public function run($sType, $aParam = null)
    {

        // get type
        $sType = empty($sType) ? 'tables' : $sType;

        switch ($sType) {
            case 'tables': // use case - update tables
            case 'fields': // use case - update fields
            case 'hooks': // use case - update hooks
            case 'templates': // use case - update templates
            case 'moduleAdminTab': // use case - update old module admin tab version
            case 'xmlFiles': // use case - initialize XML files
            case 'orderState': //use case - init order states for GSA module 
                // execute match function
                call_user_func_array(array($this, 'update' . ucfirst($sType)), array($aParam));
                break;
            case 'configuration': // use case - update configuration
                // execute match function
                call_user_func(array($this, 'update' . ucfirst($sType)), $aParam);
                break;
            default:
                break;
        }
    }


    /**
     * update tables if required
     *
     * @param array $aParam
     */
    private function updateTables(array $aParam = null)
    {
        // set transaction
        Db::getInstance()->Execute('BEGIN');

        if (!empty($GLOBALS[_GMC_MODULE_NAME . '_SQL_UPDATE']['table'])) {
            $iCount = 1;
            // loop on each elt to update SQL
            foreach ($GLOBALS[_GMC_MODULE_NAME . '_SQL_UPDATE']['table'] as $sTable => $sSqlFile) {
                // execute query
                $bResult = Db::getInstance()->ExecuteS('SHOW TABLES LIKE "' . _DB_PREFIX_ . strtolower(_GMC_MODULE_NAME) . '_' . $sTable . '"');

                // if empty - update
                if (empty($bResult)) {
                    require_once(_GMC_PATH_CONF . 'install.conf.php');
                    require_once(_GMC_PATH_LIB_INSTALL . 'install-ctrl_class.php');

                    // use case - KO update
                    if (!BT_InstallCtrl::run('install', 'sql', _GMC_PATH_SQL . $sSqlFile)) {
                        $this->aErrors[] = array(
                            'msg' => GMerchantCenter::$oModule->l(
                                'There is an error around the SQL table update!',
                                'module-update_class'
                            ),
                            'code' => intval(190 + $iCount),
                            'file' => $sSqlFile,
                            'context' => GMerchantCenter::$oModule->l(
                                'Issue around table update for: ',
                                'module-update_class'
                            ) . $sTable
                        );
                        ++$iCount;
                    }
                }
            }
        }

        if (empty($this->aErrors)) {
            Db::getInstance()->Execute('COMMIT');
        } else {
            Db::getInstance()->Execute('ROLLBACK');
        }
    }


    /**
     * update fields if required
     *
     * @param array $aParam
     */
    private function updateFields(array $aParam = null)
    {
        // set transaction
        Db::getInstance()->Execute('BEGIN');

        if (!empty($GLOBALS[_GMC_MODULE_NAME . '_SQL_UPDATE']['field'])) {
            $iCount = 1;
            // loop on each elt to update SQL
            foreach ($GLOBALS[_GMC_MODULE_NAME . '_SQL_UPDATE']['field'] as $aOption) {
                // execute query
                $bResult = Db::getInstance()->ExecuteS('SHOW COLUMNS FROM ' . _DB_PREFIX_ . strtolower(_GMC_MODULE_NAME) . '_' . $aOption['table'] . ' LIKE "' . $aOption['field'] . '"');

                // if empty - update
                if (empty($bResult)) {
                    require_once(_GMC_PATH_CONF . 'install.conf.php');
                    require_once(_GMC_PATH_LIB_INSTALL . 'install-ctrl_class.php');

                    // use case - KO update
                    if (!BT_InstallCtrl::run('install', 'sql', _GMC_PATH_SQL . $aOption['file'])) {
                        $aErrors[] = array(
                            'field' => $aOption['field'],
                            'linked' => $aOption['table'],
                            'file' => $aOption['file']
                        );
                        $this->aErrors[] = array(
                            'msg' => GMerchantCenter::$oModule->l(
                                'There is an error around the SQL field update!',
                                'module-update_class'
                            ),
                            'code' => intval(180 + $iCount),
                            'file' => $aOption['file'],
                            'context' => GMerchantCenter::$oModule->l(
                                'Issue around field update for: ',
                                'module-update_class'
                            ) . $aOption['field']
                        );
                        ++$iCount;
                    }
                }
            }
        }

        if (empty($this->aErrors)) {
            Db::getInstance()->Execute('COMMIT');
        } else {
            Db::getInstance()->Execute('ROLLBACK');
        }
    }

    /**
     * update hooks if required
     *
     * @param array $aParam
     */
    private function updateHooks(array $aParam = null)
    {
        require_once(_GMC_PATH_CONF . 'install.conf.php');
        require_once(_GMC_PATH_LIB_INSTALL . 'install-ctrl_class.php');

        // use case - hook register ko
        if (!BT_InstallCtrl::run('install', 'config', array('bHookOnly' => true))) {
            $this->aErrors[] = array(
                'msg' => GMerchantCenter::$oModule->l(
                    'There is an error around the HOOKS update!',
                    'module-update_class'
                ),
                'code' => 170,
                'file' => GMerchantCenter::$oModule->l(
                    'see the variable $GLOBALS[\'GMC_HOOKS\'] in the conf/common.conf.php file',
                    'module-update_class'
                ),
                'context' => GMerchantCenter::$oModule->l('Issue around hook update', 'module-update_class')
            );
        }
    }


    /**
     * update templates if required
     *
     * @param array $aParam
     */
    private function updateTemplates(array $aParam = null)
    {
        require_once(_GMC_PATH_LIB_COMMON . 'dir-reader.class.php');

        // get templates files
        $aTplFiles = BT_GmcDirReader::create()->run(array(
            'path' => _GMC_PATH_TPL,
            'recursive' => true,
            'extension' => 'tpl',
            'subpath' => true
        ));

        if (!empty($aTplFiles)) {
            if (version_compare(_PS_VERSION_, '1.5', '>=')) {
                $smarty = Context::getContext()->smarty;
            } else {
                global $smarty;
            }

            if (method_exists($smarty, 'clearCompiledTemplate')) {
                $smarty->clearCompiledTemplate();
            } elseif (method_exists($smarty, 'clear_compiled_tpl')) {
                foreach ($aTplFiles as $aFile) {
                    $smarty->clear_compiled_tpl($aFile['filename']);
                }
            }
        }
    }


    /**
     * update module admin tab in case of an update
     *
     * @param array $aParam
     */
    private function updateModuleAdminTab(array $aParam = null)
    {
        foreach ($GLOBALS[_GMC_MODULE_NAME . '_TABS'] as $sModuleTabName => $aTab) {
            if (isset($aTab['oldName'])) {
                if (Tab::getIdFromClassName($aTab['oldName']) != false) {
                    // include install ctrl class
                    require_once(_GMC_PATH_LIB_INSTALL . 'install-ctrl_class.php');

                    // use case - if uninstall succeeded
                    if (BT_InstallCtrl::run('uninstall', 'tab', array('name' => $aTab['oldName']))) {
                        // install new admin tab
                        BT_InstallCtrl::run('install', 'tab', array('name' => $sModuleTabName));
                    }
                }
            }
        }
    }

    /**
     * initialize XML files
     *
     * @param array $aParam
     */
    private function updateXmlFiles(array $aParam = null)
    {
        if (
            !empty($aParam['aAvailableData'])
            && is_array($aParam['aAvailableData'])
        ) {
            // require
            require_once(_GMC_PATH_LIB_COMMON . 'file.class.php');

            $iCount = 1;
            foreach ($aParam['aAvailableData'] as $aData) {
                // check if file exist
                $sFileSuffix = BT_GmcModuleTools::buildFileSuffix(
                    $aData['langIso'],
                    $aData['countryIso'],
                    (!empty($aData['currencyFirst']) ? '' : $aData['currencyIso'])
                );
                $sFilePath = GMerchantCenter::$sFilePrefix . '.' . $sFileSuffix . '.xml';

                if (!is_file(_GMC_SHOP_PATH_ROOT . $sFilePath)) {
                    try {
                        BT_GmcFile::create()->write(_GMC_SHOP_PATH_ROOT . $sFilePath, '');

                        // test if file exists
                        $bFileExists = is_file(_GMC_SHOP_PATH_ROOT . $sFilePath);
                    } catch (Exception $e) {
                        $bFileExists = false;
                    }

                    if (!$bFileExists) {
                        $aError = array(
                            'msg' => GMerchantCenter::$oModule->l('There is an error around the data feed XML file generated in the shop\'s root directory', 'module-update_class'),
                            'code' => intval(160 + $iCount),
                            'file' => _GMC_SHOP_PATH_ROOT . $sFilePath,
                            'context' => GMerchantCenter::$oModule->l('Issue around the xml files which have to be generated in the shop\'s root directory', 'module-update_class'),
                            'howTo' => GMerchantCenter::$oModule->l('Please follow our FAQ link on how to get your XML files generated to your shop\'s root directory', 'module-update_class') . '&nbsp;=>&nbsp;<i class="icon-question-sign"></i>&nbsp;<a href="' . _GMC_BT_FAQ_MAIN_URL . 'faq.php?id=21" target="_blank">FAQ</a>'
                        );
                        $this->aErrors[] = $aError;
                        $iCount++;
                    }
                }
            }
        }
    }

    /**
     * initialize order state
     *
     * @param array $aParam
     */
    private function updateOrderState(array $aParam = null)
    {
        //Processsing in progress during the half hour
        BT_GmcModuleTools::addOrderState('Validation in Progress ( Google Shopping Action )', '#27FF00', false, _GMC_MODULE_SET_NAME, '');

        //Use case for validate order state when an order is placed from GSA
        BT_GmcModuleTools::addOrderState('Google order pending shipment.', '#27FF00', false, _GMC_MODULE_SET_NAME, '', true, true, false, true);

        //Use case for cancel customer on GSA order state
        BT_GmcModuleTools::addOrderState('Cancel by the customer ( Google Shopping Action )', '#DC143C', false, _GMC_MODULE_SET_NAME, '', false);
    }


    /**
     * update specific configuration options
     *
     * @param string $sType
     */
    private function updateConfiguration($sType)
    {
        switch ($sType) {
            case 'languages':
                $aHomeCat = Configuration::get('GMC_HOME_CAT');
                if (empty($aHomeCat)) {
                    $aHomeCat = array();
                    foreach (GMerchantCenter::$aAvailableLanguages as $aLanguage) {
                        $aHomeCat[$aLanguage['id_lang']] = !empty($GLOBALS[_GMC_MODULE_NAME . '_HOME_CAT_NAME'][$aLanguage['iso_code']]) ? $GLOBALS[_GMC_MODULE_NAME . '_HOME_CAT_NAME'][$aLanguage['iso_code']] : '';
                    }
                    // update
                    Configuration::updateValue('GMC_HOME_CAT', serialize($aHomeCat));
                } elseif (is_array(GMerchantCenter::$conf['GMC_HOME_CAT'])) {
                    // update
                    Configuration::updateValue('GMC_HOME_CAT', serialize(GMerchantCenter::$conf['GMC_HOME_CAT']));
                }
                break;
            case 'color':
                if (!empty(GMerchantCenter::$conf['GMC_COLOR_OPT'])) {
                    if (is_numeric(GMerchantCenter::$conf['GMC_COLOR_OPT'])) {
                        GMerchantCenter::$conf['GMC_COLOR_OPT'] = array(GMerchantCenter::$conf['GMC_COLOR_OPT']);

                        $aAttributeIds = array();
                        foreach (GMerchantCenter::$conf['GMC_COLOR_OPT'] as $iAttributeId) {
                            $aAttributeIds['attribute'][] = $iAttributeId;
                        }
                        Configuration::updateValue('GMC_COLOR_OPT', serialize($aAttributeIds));
                    }
                }
                break;
            case 'size':
                if (!empty(GMerchantCenter::$conf['GMC_SIZE_OPT'])) {
                    if (is_numeric(GMerchantCenter::$conf['GMC_SIZE_OPT'])) {
                        GMerchantCenter::$conf['GMC_SIZE_OPT'] = array(GMerchantCenter::$conf['GMC_SIZE_OPT']);

                        $aAttributeIds = array();
                        foreach (GMerchantCenter::$conf['GMC_SIZE_OPT'] as $iAttributeId) {
                            $aAttributeIds['attribute'][] = $iAttributeId;
                        }
                        Configuration::updateValue('GMC_SIZE_OPT', serialize($aAttributeIds));
                    }
                }
                break;
            case 'cronlang':
                if (!empty(GMerchantCenter::$conf['GMC_CHECK_EXPORT'])) {
                    if (!is_array(GMerchantCenter::$conf['GMC_CHECK_EXPORT'])) {
                        GMerchantCenter::$conf['GMC_CHECK_EXPORT'] = unserialize(GMerchantCenter::$conf['GMC_CHECK_EXPORT']);
                    }

                    if (!empty(GMerchantCenter::$conf['GMC_CHECK_EXPORT'][0])) {
                        if (!strstr(GMerchantCenter::$conf['GMC_CHECK_EXPORT'][0], '_')) {
                            Configuration::updateValue('GMC_CHECK_EXPORT', serialize(''));
                        }
                    }
                }
                break;
            default:
                break;
        }
    }


    /**
     * returns errors
     *
     * @return array
     */
    public function getErrors()
    {
        return empty($this->aErrors) ? false : $this->aErrors;
    }

    /**
     * manages singleton
     *
     * @return object
     */
    public static function create()
    {
        static $oModuleUpdate;

        if (null === $oModuleUpdate) {
            $oModuleUpdate = new BT_GmcModuleUpdate();
        }
        return $oModuleUpdate;
    }
}
