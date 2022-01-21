<?php
/**
 * Google Merchant Center
 *
 * @author    BusinessTech.fr - https://www.businesstech.fr
 * @copyright Business Tech 2019 - https://www.businesstech.fr
 * @license   Commercial
 *
 *           ____    _______
 *          |  _ \  |__   __|
 *          | |_) |    | |
 *          |  _ <     | |
 *          | |_) |    | |
 *          |____/     |_|
 */

/* defines constant of module name */
define('_GMC_MODULE_NAME', 'GMC');
/* defines module name */
define('_GMC_MODULE_SET_NAME', 'gmerchantcenter');
/* defines root path of the shop */
define('_GMC_SHOP_PATH_ROOT', _PS_ROOT_DIR_ . '/');
/* defines root path of module */
define('_GMC_PATH_ROOT', _PS_MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/');
/* defines conf path */
define('_GMC_PATH_CONF', _GMC_PATH_ROOT . 'conf/');
/* defines library path */
define('_GMC_PATH_LIB', _GMC_PATH_ROOT . 'lib/');
/* defines common library path */
define('_GMC_PATH_LIB_COMMON', _GMC_PATH_LIB . 'common/');
/* defines sql path */
define('_GMC_PATH_SQL', _GMC_PATH_ROOT . 'sql/');
/* defines hook tpl path */
define('_GMC_TPL_HOOK_PATH', 'hook/');
/* defines views folder */
define('_GMC_PATH_VIEWS', 'views/');
/* defines js URL */
define('_GMC_URL_JS', _MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/' . _GMC_PATH_VIEWS . 'js/');
/* defines css URL */
define('_GMC_URL_CSS', _MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/' . _GMC_PATH_VIEWS . 'css/');
/* defines MODULE URL */
define('_GMC_MODULE_URL', _MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/');
/* defines img path */
define('_GMC_PATH_IMG', 'img/');
/* defines img URL */
define('_GMC_URL_IMG', _MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/' . _GMC_PATH_VIEWS . _GMC_PATH_IMG);
/* defines tpl path name */
define('_GMC_PATH_TPL_NAME', _GMC_PATH_VIEWS . 'templates/');
/* defines tpl path */
define('_GMC_PATH_TPL', _GMC_PATH_ROOT . _GMC_PATH_TPL_NAME);
/* defines constant of error tpl */
define('_GMC_TPL_ERROR', 'error.tpl');
/* defines confirm tpl */
define('_GMC_TPL_CONFIRM', 'confirm.tpl');
/* defines activate / deactivate debug mode */
define('_GMC_DEBUG', true);
/* defines constant to use or not js on submit action */
define('_GMC_USE_JS', true);
/* defines variable for admin ctrl name */
define('_GMC_PARAM_CTRL_NAME', 'sController');
/* defines variable for admin ctrl name */
define('_GMC_ADMIN_CTRL', 'admin');
/* defines variable for the php script file to copy */
define('_GMC_FEED_PHP_NAME', 'gmerchantcenter.feed.php');
/* defines variable for front module controller for the cron */
define('_GMC_CTRL_CRON', 'cron');
/* defines variable for front module controller for the fly output */
define('_GMC_CTRL_FLY', 'fly');

/* defines variables to configuration settings */
$GLOBALS[_GMC_MODULE_NAME . '_CONFIGURATION'] = array(
    'GMC_VERSION' => '',
    'GMC_HOME_CAT' => '',
    'GMC_LINK' => '',
    'GMERCHANTCENTER_ID_PREFIX' => '',
    'GMC_AJAX_CYCLE' => 1000,
    'GMC_EXPORT_OOS' => 1,
    'GMC_COND' => 'new',
    'GMERCHANTCENTER_P_COMBOS' => 1,
    'GMC_P_DESCR_TYPE' => 3,
    'GMC_IMG_SIZE' => '',
    'GMC_EXC_NO_EAN' => 0,
    'GMC_EXC_NO_MREF' => 0,
    'GMC_MIN_PRICE' => 0,
    'GMC_INC_STOCK' => 1,
    'GMC_INC_FEAT' => 0,
    'GMC_FEAT_OPT' => 0,
    'GMC_INC_GENRE' => 0,
    'GMC_GENRE_OPT' => 0,
    'GMC_INC_SIZE' => 0,
    'GMC_SIZE_OPT' => '',
    'GMC_INC_COLOR' => '',
    'GMC_COLOR_OPT' => '',
    'GMC_INC_MATER' => 0,
    'GMC_MATER_OPT' => 0,
    'GMC_INC_PATT' => 0,
    'GMC_PATT_OPT' => 0,
    'GMC_INC_GEND' => 0,
    'GMC_GEND_OPT' => 0,
    'GMC_INC_ADULT' => 0,
    'GMC_ADULT_OPT' => 0,
    'GMC_INC_AGE' => 0,
    'GMC_AGE_OPT' => 0,
    'GMC_SHIP_CARRIERS' => '',
    'GMC_REPORTING' => 1,
    'GMC_HOME_CAT_ID' => 1,
    'GMC_MPN_TYPE' => 'supplier_ref',
    'GMC_INC_ID_EXISTS' => 0,
    'GMC_ADD_CURRENCY' => 0,
    'GMC_UTM_CAMPAIGN' => '',
    'GMC_UTM_SOURCE' => '',
    'GMC_UTM_MEDIUM' => '',
    'GMC_UTM_CONTENT' => 0,
    'GMC_FEED_PROTECTION' => 1,
    'GMC_FEED_TOKEN' => md5(rand(1000, 1000000) . time()),
    'GMC_EXPORT_MODE' => 0,
    'GMC_ADV_PRODUCT_NAME' => 0,
    'GMC_ADV_PROD_TITLE' => 0,
    'GMC_CHECK_EXPORT' => '',
    'GMC_INC_TAG_ADULT' => 0,
    'GMC_SHIPPING_USE' => 1,
    'GMC_PROD_EXCL' => '',
    'GMC_FREE_SHIP_PROD' => '',
    'GMC_GTIN_PREF' => 'ean',
    'GMC_SIZE_TYPE' => 0,
    'GMC_SIZE_SYSTEM' => 0,
    'GMC_FEED_TAX' => '',
    'GMC_URL_ATTR_ID_INCL' => (version_compare(_PS_VERSION_, '1.6.0.13', '>=') ? 1 : 0),
    'GMC_URL_NUM_ATTR_REWRITE' => 0,
    'GMC_EXPORT_PROD_OOS_ORDER' => 0,
    'GMC_SIMPLE_PROD_ID' => 0,
    'GMC_CONF_STEP_1' => 0,
    'GMC_CONF_STEP_2' => 0,
    'GMC_CONF_STEP_3' => 0,
    'GMC_ADD_IMAGES' => 1,
    'GMC_FORCE_IDENTIFIER' => 0,
    'GMC_API_KEY' => '',
    'GMC_GSA_CUSTOMER_GROUP' => 3,
    'GMC_GSA_DEFAULT_CARRIER' => '',
    'GMC_MERCHANT_ID' => '',
    'GMC_SHOP_LINK_API' => 0,
    'GMC_GSA_CARRIERS_MAP' => '',
    'GMC_EXCLUDED_DEST' => '',
    'GMC_EXCLUDED_COUNTRY' => '',
    'GMC_COMBO_SEPARATOR' => 'v',
    'GMC_DIMENSION' => 0,
);

/* defines variable to translate js msg */
$GLOBALS[_GMC_MODULE_NAME . '_JS_MSG'] = array();

/* defines variable to define available weight units */
$GLOBALS[_GMC_MODULE_NAME . '_WEIGHT_UNITS'] = array('kg', 'lb', 'g', 'oz');

/* defines variable to define available dimenstion units */
$GLOBALS[_GMC_MODULE_NAME . '_DIMENSION_UNITS'] = array('cm', 'in');

/* defines variable to define default home cat name translations */
$GLOBALS[_GMC_MODULE_NAME . '_HOME_CAT_NAME'] = array(
    'en' => 'home',
    'fr' => 'accueil',
    'it' => 'ignazio',
    'es' => 'ignacio',
);

$GLOBALS['GMC_HOOKS'] = array(
    array('name' => 'moduleRoutes', 'use' => false, 'title' => 'Module route'),
);

/* defines available languages / countries / currencies for Google */
$GLOBALS['GMC_AVAILABLE_COUNTRIES'] = array(
    'en' => array(
        'IE' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'GB' => array('currency' => array('GBP', 'KES', 'NGN', 'PAB', 'PKR', 'DZD', 'AOA', 'BYN', 'KHR', 'XAF', 'XOF', 'ETB', 'GHS', 'JOD', 'KZT', 'KWD', 'LBP', 'MGA', 'MUR', 'MAD', 'MZN', 'MMK', 'NPR', 'NIO', 'OMR', 'PYG', 'PEN', 'RON', 'XOF', 'LKR', 'UGX', 'UYU', 'UZS', 'ZMW'), 'taxonomy' => 'en-US'),
        'US' => array('currency' => array('USD', 'KES', 'NGN', 'PAB', 'PKR', 'DZD', 'AOA', 'BYN', 'KHR', 'XAF', 'XOF', 'ETB', 'GHS', 'JOD', 'KZT', 'KWD', 'LBP', 'MGA', 'MUR', 'MAD', 'MZN', 'MMK', 'NPR', 'NIO', 'OMR', 'PYG', 'PEN', 'RON', 'XOF', 'LKR', 'UGX', 'UYU' ,'UZS', 'ZMW'), 'taxonomy' => 'en-US'),
        'AU' => array('currency' => array('AUD'), 'taxonomy' => 'en-US'),
        'CA' => array('currency' => array('CAD'), 'taxonomy' => 'en-US'),
        'IN' => array('currency' => array('INR'), 'taxonomy' => 'en-US'),
        'CH' => array('currency' => array('CHF'), 'taxonomy' => 'en-US'),
        'BE' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'DK' => array('currency' => array('DKK'), 'taxonomy' => 'en-US'),
        'NO' => array('currency' => array('NOK'), 'taxonomy' => 'en-US'),
        'MY' => array('currency' => array('MYR'), 'taxonomy' => 'en-US'),
        'ID' => array('currency' => array('RP'), 'taxonomy' => 'en-US'),
        'SE' => array('currency' => array('SEK'), 'taxonomy' => 'en-US'),
        'HK' => array('currency' => array('HKD'), 'taxonomy' => 'en-US'),
        'MX' => array('currency' => array('MXN'), 'taxonomy' => 'en-US'),
        'NZ' => array('currency' => array('NZD'), 'taxonomy' => 'en-US'),
        'PH' => array('currency' => array('PHP'), 'taxonomy' => 'en-US'),
        'SG' => array('currency' => array('SGD'), 'taxonomy' => 'en-US'),
        'TW' => array('currency' => array('TWD'), 'taxonomy' => 'en-US'),
        'AE' => array('currency' => array('AED', 'DZD', 'EGP', 'TND'), 'taxonomy' => 'en-US'),
        'DE' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'AT' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'NL' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'TR' => array('currency' => array('TRY'), 'taxonomy' => 'en-US'),
        'ZA' => array('currency' => array('ZAR'), 'taxonomy' => 'en-US'),
        'CZ' => array('currency' => array('CZK'), 'taxonomy' => 'en-US'),
        'IL' => array('currency' => array('ILS'), 'taxonomy' => 'en-US'),
        'VN' => array('currency' => array('VND'), 'taxonomy' => 'en-US'),
        'TH' => array('currency' => array('THB'), 'taxonomy' => 'en-US'),
        'KO' => array('currency' => array('KRW'), 'taxonomy' => 'en-US'),
        'AR' => array('currency' => array('ARS', 'CRC', 'DOP', 'GTQ'), 'taxonomy' => 'en-US'),
        'BR' => array('currency' => array('BRL'), 'taxonomy' => 'en-US'),
        'CL' => array('currency' => array('CLP'), 'taxonomy' => 'en-US'),
        'CO' => array('currency' => array('COP'), 'taxonomy' => 'en-US'),
        'IT' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'JP' => array('currency' => array('JPY'), 'taxonomy' => 'en-US'),
        'PL' => array('currency' => array('PLN'), 'taxonomy' => 'en-US'),
        'RU' => array('currency' => array('RUB', 'GEL'), 'taxonomy' => 'en-US'),
        'PT' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'SA' => array('currency' => array('AED, SAR', 'DZD', 'EGP'), 'taxonomy' => 'en-US'),
        'ES' => array('currency' => array('EUR', 'GTQ'), 'taxonomy' => 'en-US'),
        'GE' => array('currency' => array('KAS'), 'taxonomy' => 'en-US'),
        'UR' => array('currency' => array('PKR'), 'taxonomy' => 'en-US'),
        'VE' => array('currency' => array('VEF'), 'taxonomy' => 'en-US'),
        'SK' => array('currency' => array('EUR'), 'taxonomy' => 'en-US'),
        'HU' => array('currency' => array('HUF'), 'taxonomy' => 'en-US'),
        'KW' => array('currency' => array('KWD'), 'taxonomy' => 'en-US'),
    ),
    'gb' => array(
        'AU' => array('currency' => array('AUD'), 'taxonomy' => 'en-GB'),
        'IE' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'IN' => array('currency' => array('INR'), 'taxonomy' => 'en-GB'),
        'CH' => array('currency' => array('CHF'), 'taxonomy' => 'en-GB'),
        'BE' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'DK' => array('currency' => array('DKK'), 'taxonomy' => 'en-GB'),
        'NO' => array('currency' => array('NOK'), 'taxonomy' => 'en-GB'),
        'MY' => array('currency' => array('MYR'), 'taxonomy' => 'en-GB'),
        'ID' => array('currency' => array('IDR'), 'taxonomy' => 'en-GB'),
        'SE' => array('currency' => array('SEK'), 'taxonomy' => 'en-GB'),
        'HK' => array('currency' => array('HKD'), 'taxonomy' => 'en-GB'),
        'MX' => array('currency' => array('MXN'), 'taxonomy' => 'en-GB'),
        'NZ' => array('currency' => array('NZD'), 'taxonomy' => 'en-GB'),
        'PH' => array('currency' => array('PHP'), 'taxonomy' => 'en-GB'),
        'SG' => array('currency' => array('SGD'), 'taxonomy' => 'en-GB'),
        'TW' => array('currency' => array('TWD'), 'taxonomy' => 'en-GB'),
        'SA' => array('currency' => array('AED, SAR', 'DZD', 'EGP', 'TND'), 'taxonomy' => 'en-GB'),
        'DE' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'AT' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'NL' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'TR' => array('currency' => array('TRY'), 'taxonomy' => 'en-GB'),
        'ZA' => array('currency' => array('ZAR'), 'taxonomy' => 'en-GB'),
        'CZ' => array('currency' => array('CZK'), 'taxonomy' => 'en-GB'),
        'IL' => array('currency' => array('ILS'), 'taxonomy' => 'en-GB'),
        'VN' => array('currency' => array('VND'), 'taxonomy' => 'en-GB'),
        'TH' => array('currency' => array('THB'), 'taxonomy' => 'en-GB'),
        'US' => array('currency' => array('USD'), 'taxonomy' => 'en-GB'),
        'GB' => array('currency' => array('GBP'), 'taxonomy' => 'en-GB'),
        'KO' => array('currency' => array('KRW'), 'taxonomy' => 'en-GB'),
        'AR' => array('currency' => array('ARS', 'CRC', 'DOP', 'GTQ'), 'taxonomy' => 'en-GB'),
        'BR' => array('currency' => array('BRL'), 'taxonomy' => 'en-GB'),
        'CL' => array('currency' => array('CLP'), 'taxonomy' => 'en-GB'),
        'CO' => array('currency' => array('COP'), 'taxonomy' => 'en-GB'),
        'IT' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'JP' => array('currency' => array('JPY'), 'taxonomy' => 'en-GB'),
        'PL' => array('currency' => array('PLN'), 'taxonomy' => 'en-GB'),
        'RU' => array('currency' => array('RUB', 'GEL'), 'taxonomy' => 'en-GB'),
        'PT' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'ES' => array('currency' => array('EUR', 'GTQ'), 'taxonomy' => 'en-GB'),
        'GE' => array('currency' => array('KAS'), 'taxonomy' => 'en-GB'),
        'UR' => array('currency' => array('PKR'), 'taxonomy' => 'en-GB'),
        'VE' => array('currency' => array('VEF'), 'taxonomy' => 'en-GB'),
        'SK' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
        'HU' => array('currency' => array('HUF'), 'taxonomy' => 'en-GB'),
    ),
    'fr' => array(
        'FR' => array('currency' => array('EUR', 'TND', 'DZD', 'XAF', 'XOF', 'MGA', 'MAD'), 'taxonomy' => 'fr-FR'),
        'CH' => array('currency' => array('CHF'), 'taxonomy' => 'fr-FR'),
        'CA' => array('currency' => array('CAD'), 'taxonomy' => 'fr-FR'),
        'BE' => array('currency' => array('EUR'), 'taxonomy' => 'fr-FR'),
        'SA' => array('currency' => array('DZD'), 'taxonomy' => 'fr-FR'),
    ),
    'de' => array(
        'EN' => array('currency' => array('EUR'), 'taxonomy' => 'de-DE'),
        'BE' => array('currency' => array('EUR'), 'taxonomy' => 'de-DE'),
        'DE' => array('currency' => array('EUR'), 'taxonomy' => 'de-DE'),
        'CH' => array('currency' => array('CHF'), 'taxonomy' => 'de-DE'),
        'AT' => array('currency' => array('EUR'), 'taxonomy' => 'de-DE')
    ),
    'it' => array(
        'IT' => array('currency' => array('EUR'), 'taxonomy' => 'it-IT'),
        'CH' => array('currency' => array('CHF'), 'taxonomy' => 'it-IT')
    ),
    'nl' => array(
        'NL' => array('currency' => array('EUR'), 'taxonomy' => 'nl-NL'),
        'BE' => array('currency' => array('EUR'), 'taxonomy' => 'nl-NL')
    ),
    'es' => array(
        'ES' => array('currency' => array('EUR', 'MXN', 'ARS', 'CLP', 'COP', 'USD', 'CRC', 'GTQ', 'PYG', 'NIO', 'PEN', 'UYU'), 'taxonomy' => 'es-ES'),
        'MX' => array('currency' => array('MXN', 'EUR', 'ARS', 'CLP', 'COP', 'USD', 'CRC', 'GTQ', 'PYG', 'NIO', 'PEN', 'UYU'), 'taxonomy' => 'es-ES'),
        'AR' => array('currency' => array('ARS', 'EUR', 'MXN', 'CLP', 'COP', 'USD', 'CRC', 'GTQ', 'PYG', 'NIO', 'PEN', 'UYU'), 'taxonomy' => 'es-ES'),
        'CL' => array('currency' => array('CLP', 'EUR', 'MXN', 'ARS', 'COP', 'USD', 'CRC', 'GTQ', 'PYG', 'NIO', 'PEN', 'UYU'), 'taxonomy' => 'es-ES'),
        'CO' => array('currency' => array('COP', 'EUR', 'MXN', 'ARS', 'CLP', 'USD', 'CRC', 'GTQ', 'PYG', 'NIO', 'PEN', 'UYU'), 'taxonomy' => 'es-ES'),
        'US' => array('currency' => array('USD', 'EUR', 'MXN', 'ARS', 'CLP', 'COP', 'CRC', 'GTQ', 'PYG', 'NIO', 'PEN', 'UYU'), 'taxonomy' => 'es-ES'),
    ),

    'mx' => array(
        'ES' => array('currency' => array('EUR', 'MXN', 'ARS', 'CLP', 'COP', 'USD'), 'taxonomy' => 'es-ES'),
        'MX' => array('currency' => array('EUR', 'MXN', 'ARS', 'CLP', 'COP'), 'taxonomy' => 'es-ES'),
        'AR' => array('currency' => array('ARS', 'EUR', 'MXN', 'CLP', 'COP', 'USD'), 'taxonomy' => 'es-ES'),
        'CL' => array('currency' => array('CLP', 'EUR', 'MXN', 'ARS', 'COP', 'USD'), 'taxonomy' => 'es-ES'),
        'CO' => array('currency' => array('COP', 'EUR', 'MXN', 'ARS', 'CLP', 'USD'), 'taxonomy' => 'es-ES'),
        'US' => array('currency' => array('USD', 'EUR', 'MXN', 'ARS', 'CLP', 'COP'), 'taxonomy' => 'es-ES'),
    ),
    'ca' => array(
        'ES' => array('currency' => array('EUR'), 'taxonomy' => 'es-ES'),
    ),
    'zh' => array(
        'CN' => array('currency' => array('CNY'), 'taxonomy' => 'zh-CN'),
        'EN' => array('currency' => array('CNY'), 'taxonomy' => 'zh-CN'),
        'HK' => array('currency' => array('HKD'), 'taxonomy' => 'zh-CN'),
        'TW' => array('currency' => array('TWD'), 'taxonomy' => 'zh-CN'),
        'AU' => array('currency' => array('AUD'), 'taxonomy' => 'zh-CN'),
        'CA' => array('currency' => array('CAD'), 'taxonomy' => 'zh-CN'),
        'US' => array('currency' => array('USD'), 'taxonomy' => 'zh-CN'),
        'SG' => array('currency' => array('SGD'), 'taxonomy' => 'zh-CN'),
    ),
    'ja' => array(
        'JP' => array('currency' => array('JPY'), 'taxonomy' => 'ja-JP')
    ),
    'br' => array(
        'BR' => array('currency' => array('BRL'), 'taxonomy' => 'pt-BR')
    ),
    'cs' => array(
        'CZ' => array('currency' => array('CZK'), 'taxonomy' => 'cs-CZ')
    ),
    'ru' => array(
        'RU' => array('currency' => array('RUB', 'BYR', 'GEL', 'BYN', 'KZT', 'KWD', 'UZS'), 'taxonomy' => 'ru-RU'),
        'UA' => array('currency' => array('UAH'), 'taxonomy' => 'ru-RU')
    ),
    'sv' => array(
        'SE' => array('currency' => array('SEK'), 'taxonomy' => 'sv-SE'),
        'EN' => array('currency' => array('SEK'), 'taxonomy' => 'sv-SE')
    ),
    'da' => array(
        'DK' => array('currency' => array('DKK'), 'taxonomy' => 'da-DK'),
        'EN' => array('currency' => array('DKK'), 'taxonomy' => 'da-DK')
    ),
    'no' => array(
        'NO' => array('currency' => array('NOK'), 'taxonomy' => 'no-NO')
    ),
    'pl' => array(
        'PL' => array('currency' => array('PLN'), 'taxonomy' => 'pl-PL')
    ),
    'tr' => array(
        'TR' => array('currency' => array('TRY'), 'taxonomy' => 'tr-TR')
    ),
    'ms' => array(
        'MY' => array('currency' => array('MYR'), 'taxonomy' => 'en-US')
    ),
    'pt' => array(
        'PT' => array('currency' => array('EUR', 'AOA', 'MZN'), 'taxonomy' => 'es-ES')
    ),
    'ar' => array(
        'SA' => array('currency' => array('SAR', 'AED', 'DZD', 'CRC', 'EGP', 'TND', 'DZD', 'JOD', 'LBP', 'MAD', 'OMR'), 'taxonomy' => 'es-ES'),
        'AE' => array('currency' => array('AED', 'SAR', 'DZD', 'EGP', 'DZD', 'JOD'), 'taxonomy' => 'es-ES'),
        'KW' => array('currency' => array('KWD'), 'taxonomy' => 'ar-SA'),
    ),
    'id' => array(
        'ID' => array('currency' => array('IDR'), 'taxonomy' => 'en-US'),
    ),
    'he' => array(
        'IL' => array('currency' => array('ILS'), 'taxonomy' => 'en-US'),
    ),
    'vn' => array(
        'VN' => array('currency' => array('VND'), 'taxonomy' => 'en-US'),
    ),
    'uk' => array(
        'UA' => array('currency' => array('UAH'), 'taxonomy' => 'en-US'),
    ),
    'th' => array(
        'TH' => array('currency' => array('THB'), 'taxonomy' => 'en-US'),
    ),
    'ko' => array(
        'KO' => array('currency' => array('KRW'), 'taxonomy' => 'en-US'),
    ),
    'fi' => array(
        'FI' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
    ),
    'hu' => array(
        'HU' => array('currency' => array('HUF'), 'taxonomy' => 'en-GB'),
    ),
    'ag' => array(
        'AR' => array('currency' => array('CRC', 'DOP', 'GTQ'), 'taxonomy' => 'es-ES'),
    ),
    'ur' => array(
        'UR' => array('currency' => array('PKR'), 'taxonomy' => 'en-US'),
    ),
    've' => array(
        'VE' => array('currency' => array('VEF'), 'taxonomy' => 'es-ES'),
    ),
    'sk' => array(
        'SK' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
    ),
    'ro' => array(
        'RO' => array('currency' => array('RON'), 'taxonomy' => 'en-GB'),
    ),
    'el' => array(
        'GR' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
    ),
    'lt' => array(
        'LT' => array('currency' => array('EUR'), 'taxonomy' => 'en-GB'),
    ),
    'qc' => array(
        'CA' => array('currency' => array('cAD'), 'taxonomy' => 'fr-FR')
    ),
);
