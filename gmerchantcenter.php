<?php

/**
 * Google Merchant Center
 *
 * @author    BusinessTech.fr - https://www.businesstech.fr
 * @copyright Business Tech 2021 - https://www.businesstech.fr
 * @license   Commercial
 * @version 4.7.34
 *
 *           ____    _______
 *          |  _ \  |__   __|
 *          | |_) |    | |
 *          |  _ <     | |
 *          | |_) |    | |
 *          |____/     |_|
 */

if (!defined('_PS_VERSION_')) {
    exit(1);
}

class GMerchantCenter extends Module
{
    /**
     * @var array $conf : array of set configuration
     */
    public static $conf = array();

    /**
     * @var int $iCurrentLang : store id of default lang
     */
    public static $iCurrentLang = null;

    /**
     * @var int $sCurrentLang : store iso of default lang
     */
    public static $sCurrentLang = null;

    /**
     * @var obj $oCookie : store cookie obj
     */
    public static $oCookie = null;

    /**
     * @var obj $oModule : obj module itself
     */
    public static $oModule = array();

    /**
     * @var string $sQueryMode : query mode - detect XHR
     */
    public static $sQueryMode = null;

    /**
     * @var string $sBASE_URI : base of URI in prestashop
     */
    public static $sBASE_URI = null;

    /**
     * @var string $sHost : store the current domain
     */
    public static $sHost = '';

    /**
     * @var int $iShopId : shop id used for 1.5 and for multi shop
     */
    public static $iShopId = 1;

    /**
     * @var bool $bCompare1550 : get compare version for PS 1.5.5.0
     */
    public static $bCompare1550 = false;

    /**
     * @var bool $bCompare16 : get compare version for PS 1.6
     */
    public static $bCompare16 = false;

    /**
     * @var bool $bCompare16013 : get compare version for PS 1.6
     */
    public static $bCompare16013 = false;

    /**
     * @var bool $bCompare17 : get compare version for PS 1.7
     */
    public static $bCompare17 = false;

    /**
     * @var bool $bCompare1730 : get compare version for PS 1.7.3.0
     */
    public static $bCompare1730 = false;

    /**
     * @var bool $bCompare1770 : get compare version for PS 1.7.7.0
     */
    public static $bCompare1770 = false;

    /**
     * @var array $aAvailableLanguages : store the available languages
     */
    public static $aAvailableLanguages = array();

    /**
     * @var array $aAvailableLangCurrencyCountry : store the available related languages / countries / currencies
     */
    public static $aAvailableLangCurrencyCountry = array();

    /**
     * @var string $sFilePrefix : store the XML file's prefix
     */
    public static $sFilePrefix = '';

    /**
     * @var bool $bAdvancedPack : check advanced pack module installation
     */
    public static $bAdvancedPack = false;


    /**
     * @var array $aErrors : array get error
     */
    public $aErrors = null;


    /**
     * assigns few information about module and instantiate parent class
     */
    public function __construct()
    {
        require_once(_PS_MODULE_DIR_ . 'gmerchantcenter/conf/common.conf.php');
        require_once(_GMC_PATH_LIB . 'module-tools_class.php');

        $this->name = 'gmerchantcenter';
        $this->module_key = '315713e1154d1eeae38c07f1548fef39';
        $this->tab = 'seo';
        $this->version = '4.7.34';
        $this->author = 'Business Tech';

        parent::__construct();

        $this->displayName = $this->l('Google Merchant Center (Google Shopping)');
        $this->description = $this->l('Export your products on Google Merchant Center. Show them on Google Shopping and Google\'s partner sites to get thousands of new highly qualified visitors on your shop!');
        $this->confirmUninstall = $this->l('Are you sure you want to remove Google Merchant Center ?');

        // get cookie obj
        self::$oCookie = $this->context->cookie;

        // compare PS version
        self::$bCompare1550 = version_compare(_PS_VERSION_, '1.5.5.0', '>=');
        self::$bCompare16 = version_compare(_PS_VERSION_, '1.6', '>=');
        self::$bCompare16013 = version_compare(_PS_VERSION_, '1.6.0.13', '>=');
        self::$bCompare17 = version_compare(_PS_VERSION_, '1.7.0.0', '>=');
        self::$bCompare1730 = version_compare(_PS_VERSION_, '1.7.3.0', '>=');
        self::$bCompare1770 = version_compare(_PS_VERSION_, '1.7.7.0', '>=');
        self::$bAdvancedPack = BT_GmcModuleTools::isInstalled('pm_advancedpack');

        // get shop id
        self::$iShopId = $this->context->shop->id;

        // get current  lang id
        self::$iCurrentLang = self::$oCookie->id_lang;
        // get current lang iso
        self::$sCurrentLang = BT_GmcModuleTools::getLangIso();

        // stock itself obj
        self::$oModule = $this;

        //set bootstrap
        if (!empty(self::$bCompare16)) {
            $this->bootstrap = true;
        }

        // set base of URI
        self::$sBASE_URI = $this->_path;
        self::$sHost = BT_GmcModuleTools::setHost();

        // get configuration options
        BT_GmcModuleTools::getConfiguration(array(
            'GMC_HOME_CAT',
            'GMC_COLOR_OPT',
            'GMC_SIZE_OPT',
            'GMC_SHIP_CARRIERS',
            'GMC_CHECK_EXPORT',
            'GMC_FEED_TAX'
        ));

        // get available languages
        self::$aAvailableLanguages = BT_GmcModuleTools::getAvailableLanguages(self::$iShopId);

        // get available languages / currencies / countries
        self::$aAvailableLangCurrencyCountry = BT_GmcModuleTools::getLangCurrencyCountry(self::$aAvailableLanguages, $GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES']);

        // get call mode - Ajax or dynamic - used for clean headers and footer in ajax request
        self::$sQueryMode = Tools::getValue('sMode');
    }

    /**
     * installs all mandatory structure (DB or Files) => sql queries and update values and hooks registered
     *
     * @return bool
     */
    public function install()
    {
        require_once(_GMC_PATH_CONF . 'install.conf.php');
        require_once(_GMC_PATH_LIB_INSTALL . 'install-ctrl_class.php');

        // set return
        $bReturn = true;

        if (
            !parent::install()
            || !BT_InstallCtrl::run('install', 'sql', _GMC_PATH_SQL . _GMC_INSTALL_SQL_FILE)
            || !BT_InstallCtrl::run('install', 'config', array('bConfigOnly' => true))
        ) {
            $bReturn = false;
        }

        return $bReturn;
    }

    /**
     * uninstalls all mandatory structure (DB or Files)
     *
     * @return bool
     */
    public function uninstall()
    {
        require_once(_GMC_PATH_CONF . 'install.conf.php');
        require_once(_GMC_PATH_LIB_INSTALL . 'install-ctrl_class.php');

        // set return
        $bReturn = true;

        if (
            !parent::uninstall()
            //          || !BT_InstallCtrl::run('uninstall', 'sql', _GMC_PATH_SQL . _GMC_UNINSTALL_SQL_FILE)
            || !BT_InstallCtrl::run('uninstall', 'config')
        ) {
            $bReturn = false;
        }

        return $bReturn;
    }

    /**
     * manages all data in Back Office
     *
     * @return string
     */
    public function getContent()
    {
        require_once(_GMC_PATH_CONF . 'admin.conf.php');
        require_once(_GMC_PATH_LIB_ADMIN . 'base-ctrl_class.php');
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-ctrl_class.php');

        try {
            // transverse execution
            self::$sFilePrefix = BT_GmcModuleTools::setXmlFilePrefix();

            // get controller type
            $sControllerType = (!Tools::getIsset(_GMC_PARAM_CTRL_NAME) || (Tools::getIsset(_GMC_PARAM_CTRL_NAME) && 'admin' == Tools::getValue(_GMC_PARAM_CTRL_NAME))) ? (Tools::getIsset(_GMC_PARAM_CTRL_NAME) ? Tools::getValue(_GMC_PARAM_CTRL_NAME) : 'admin') : Tools::getValue(_GMC_PARAM_CTRL_NAME);
            // instantiate matched controller object
            $oCtrl = BT_GmcBaseCtrl::get($sControllerType);

            // execute good action in admin
            // only displayed with key : tpl and assign in order to display good smarty template
            $aDisplay = $oCtrl->run(array_merge($_GET, $_POST));
            if (!empty($aDisplay)) {
                $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
                    'oJsTranslatedMsg' => BT_GmcModuleTools::jsonEncode($GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']),
                    'bAddJsCss' => true
                ));

                // get content
                $sContent = $this->displayModule($aDisplay['tpl'], $aDisplay['assign']);

                if (!empty(self::$sQueryMode)) {
                    echo $sContent;
                } else {
                    return $sContent;
                }
            } else {
                throw new Exception('action returns empty content', 110);
            }
        } catch (Exception $e) {
            $this->aErrors[] = array('msg' => $e->getMessage(), 'code' => $e->getCode());

            // get content
            $sContent = $this->displayErrorModule();

            if (!empty(self::$sQueryMode)) {
                echo $sContent;
            } else {
                return $sContent;
            }
        }
        // exit clean with XHR mode
        if (!empty(self::$sQueryMode)) {
            exit(0);
        }
    }

    /**
     * executes hook module route for old url redirect
     *
     * @param array $aParams
     * @return string
     */
    public function hookModuleRoutes(array $aParams)
    {
        $sMyRoute = array(
            'module-gmerchantcenter-fly' => array(
                'controller' => 'fly',
                'rule' => 'modules/gmerchantcenter/fly.php',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'gmerchantcenter',
                    'token' => GMerchantCenter::$conf['GMC_FEED_TOKEN'],
                ),
            ),
            'module-gmerchantcenter-cron' => array(
                'controller' => 'cron',
                'rule' => 'modules/gmerchantcenter/cron.php',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'gmerchantcenter',
                    'token' => GMerchantCenter::$conf['GMC_FEED_TOKEN'],
                ),
            )
        );

        return $sMyRoute;
    }

    /**
     * displays selected hook content
     *
     * @param string $sHookType
     * @param array $aParams
     * @return string
     */
    private function execHook($sHookType, $sAction, array $aParams = null)
    {
        // include
        require_once(_GMC_PATH_CONF . 'hook.conf.php');
        require_once(_GMC_PATH_LIB_HOOK . 'hook-ctrl_class.php');

        // set
        $aDisplay = array();

        try {
            // use cache or not
            if (
                !empty($aParams['cache'])
                && !empty($aParams['template'])
                && !empty($aParams['cacheId'])
            ) {
                $bUseCache = !$this->isCached($aParams['template'], $this->getCacheId($aParams['cacheId'])) ? false : true;

                if ($bUseCache) {
                    $aDisplay['tpl'] = $aParams['template'];
                    $aDisplay['assign'] = array();
                }
            } else {
                $bUseCache = false;
            }

            // detect cache or not
            if (!$bUseCache) {
                // define which hook class is executed in order to display good content in good zone in shop
                $oHook = new BT_GmcHookCtrl($sHookType, $sAction);

                // displays good block content
                $aDisplay = $oHook->run($aParams);
            }

            // execute good action in admin
            // only displayed with key : tpl and assign in order to display good smarty template
            if (!empty($aDisplay)) {
                return $this->displayModule($aDisplay['tpl'], $aDisplay['assign'], $bUseCache, (!empty($aParams['cacheId']) ? $aParams['cacheId'] : null));
            } else {
                throw new Exception('Chosen hook returned empty content', 110);
            }
        } catch (Exception $e) {
            $this->aErrors[] = array('msg' => $e->getMessage(), 'code' => $e->getCode());

            return $this->displayErrorModule();
        }
    }

    /**
     * displays views
     *
     * @param string $sTplName
     * @param array $aAssign
     * @param bool $bUseCache
     * @param int $iICacheId
     * @return string html
     */
    public function displayModule($sTplName, $aAssign, $bUseCache = false, $iICacheId = null)
    {
        if (file_exists(_GMC_PATH_TPL . $sTplName) && is_file(_GMC_PATH_TPL . $sTplName)) {
            $aAssign = array_merge(
                $aAssign,
                array('sModuleName' => Tools::strtolower(_GMC_MODULE_NAME), 'bDebug' => _GMC_DEBUG)
            );

            // use cache
            if (!empty($bUseCache) && !empty($iICacheId)) {
                return $this->display(__FILE__, $sTplName, $this->getCacheId($iICacheId));
            } // not use cache
            else {
                $this->context->smarty->assign($aAssign);

                return $this->display(__FILE__, _GMC_PATH_TPL_NAME . $sTplName);
            }
        } else {
            throw new Exception('Template "' . $sTplName . '" doesn\'t exists', 120);
        }
    }

    /**
     * displays view with error
     *
     * @param string $sTplName
     * @param array $aAssign
     * @return string html
     */
    public function displayErrorModule()
    {
        $this->context->smarty->assign(
            array(
                'sHomeURI' => BT_GmcModuleTools::truncateUri(),
                'aErrors' => $this->aErrors,
                'sModuleName' => Tools::strtolower(_GMC_MODULE_NAME),
                'bDebug' => _GMC_DEBUG,
            )
        );

        return $this->display(__FILE__, _GMC_PATH_TPL_NAME . _GMC_TPL_HOOK_PATH . _GMC_TPL_ERROR);
    }

    /**
     * updates module as necessary
     * @return array
     */
    public function updateModule()
    {
        require(_GMC_PATH_LIB . 'module-update_class.php');

        // check if update tables
        BT_GmcModuleUpdate::create()->run('tables');

        // check if update hook
        BT_GmcModuleUpdate::create()->run('hooks');

        // check if update fields
        BT_GmcModuleUpdate::create()->run('fields');

        // check if update templates
        BT_GmcModuleUpdate::create()->run('templates');

        // check if update some configuration options
        BT_GmcModuleUpdate::create()->run('configuration', 'languages');
        BT_GmcModuleUpdate::create()->run('configuration', 'color');
        BT_GmcModuleUpdate::create()->run('configuration', 'size');
        BT_GmcModuleUpdate::create()->run('configuration', 'cronlang');

        $aErrors = BT_GmcModuleUpdate::create()->getErrors();

        // initialize XML files
        BT_GmcModuleUpdate::create()->run('xmlFiles', array('aAvailableData' => GMerchantCenter::$aAvailableLangCurrencyCountry));

        if (
            empty($aErrors)
            && BT_GmcModuleUpdate::create()->getErrors()
        ) {
            BT_GmcWarning::create()->bStopExecution = true;
        }

        return BT_GmcModuleUpdate::create()->getErrors();
    }
}
