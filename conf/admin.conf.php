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

require_once(dirname(__FILE__) . '/common.conf.php');

/* defines modules support product id */
define('_GMC_SUPPORT_ID', '1768');

/* defines activate the BT support if false we use the ADDONS support url */
define('_GMC_SUPPORT_BT', false);
//define('_GMC_SUPPORT_BT', true);

/* defines activate the BT support if false we use the ADDONS support url */
define('_GMC_SUPPORT_URL', 'https://addons.prestashop.com/');
//define('_GMC_SUPPORT_URL', 'http://www.businesstech.fr/');

/* defines admin library path */
define('_GMC_PATH_LIB_ADMIN', _GMC_PATH_LIB . 'admin/');

/* defines xml library path */
define('_GMC_PATH_LIB_XML', _GMC_PATH_LIB . 'xml/');

/* defines xml shopping action path */
define('_GMC_PATH_LIB_GSA', _GMC_PATH_LIB . 'shopping-action/');

/* defines admin tpl path */
define('_GMC_TPL_ADMIN_PATH', 'admin/');

/* defines header tpl */
define('_GMC_TPL_HEADER', 'header.tpl');

/* defines body tpl */
define('_GMC_TPL_BODY', 'body.tpl');

/* defines top tpl */
define('_GMC_TPL_TOP', 'top.tpl');

/* defines welcome list settings tpl */
define('_GMC_TPL_WELCOME', 'welcome-include.tpl');

/* defines template prerequisites settings tpl */
define('_GMC_TPL_PREREQUISITES', 'prerequisites.tpl');

/* defines basics settings tpl */
define('_GMC_TPL_BASICS', 'basics.tpl');

/* defines template shopping action settings tpl */
define('_GMC_TPL_GSA', 'shopping-action.tpl');

/* defines feed settings tpl */
define('_GMC_TPL_FEED_SETTINGS', 'feed-settings.tpl');

/* defines google settings tpl */
define('_GMC_TPL_GOOGLE_SETTINGS', 'google-settings.tpl');

/* defines google category list tpl */
define('_GMC_TPL_GOOGLE_CATEGORY_LIST', 'google-category-list.tpl');

/* defines google category popup tpl */
define('_GMC_TPL_GOOGLE_CATEGORY_POPUP', 'google-category-popup.tpl');

/* defines google category update tpl */
define('_GMC_TPL_GOOGLE_CATEGORY_UPD', 'google-category-update.tpl');

/* defines google custom label tpl */
define('_GMC_TPL_GOOGLE_CUSTOM_LABEL', 'google-custom-label.tpl');

/* defines google custom label update tpl */
define('_GMC_TPL_GOOGLE_CUSTOM_LABEL_UPD', 'google-custom-label-update.tpl');

/* defines feed list settings tpl */
define('_GMC_TPL_FEED_LIST', 'feed-list.tpl');

/* defines reporting settings tpl */
define('_GMC_TPL_REPORTING', 'reporting-settings.tpl');

/* defines feed generate action tpl */
define('_GMC_TPL_FEED_GENERATE', 'feed-generate.tpl');

/* defines feed generate output tpl */
define('_GMC_TPL_FEED_GENERATE_OUTPUT', 'feed-generate-output.tpl');

/* defines advanced tag category settings tpl */
define('_GMC_TPL_ADVANCED_TAG_CATEGORY', 'advanced-tag-category.tpl');

/* defines advanced tag update tpl */
define('_GMC_TPL_ADVANCED_TAG_UPD', 'advanced-tag-update.tpl');

/* defines reporting fancybox tpl */
define('_GMC_TPL_REPORTING_BOX', 'reporting-box.tpl');

/* defines product search tpl */
define('_GMC_TPL_PROD_SEARCH', 'product-search.tpl');

/* defines update sql file */
define('_GMC_TPL_STEP_POPUP', 'step-popup.tpl');

/* defines update sql file */
define('_GMC_UPDATE_SQL_FILE', 'update.sql');

/* defines update sql file */
define('_GMC_TAXONOMY_SQL_FILE', 'update-taxonomy.sql');

/* defines update sql file */
define('_GMC_TAXONOMY_CAT_SQL_FILE', 'update-taxonomy-cat.sql');

/* defines update sql file */
define('_GMC_TAXONOMY_SHOP_SQL_FILE', 'update-taxonomy-shop.sql');

/* defines update sql file */
define('_GMC_FEATURE_SHOP_SQL_FILE', 'update-feature-shop.sql');

/* defines update sql file */
define('_GMC_TAGS_SQL_FILE', 'update-tags.sql');

/* defines update sql file */
define('_GMC_TAGS_ID_SQL_FILE', 'update-tags-shopid.sql');

/* defines update sql file */
define('_GMC_GOOGLECAT_ID_SQL_FILE', 'update-googlecat-shopid.sql');

/* defines update sql file */
define('_GMC_BRANDS_SQL_FILE', 'update-brands.sql');

/* defines update sql file */
define('_GMC_FEATURES_SQL_FILE', 'update-features.sql');

/* defines update sql file */
define('_GMC_GSA_SQL_FILE', 'update-gsa.sql');

/* defines constant for external BT API URL */
define('_GMC_BT_API_MAIN_URL', 'https://api.businesstech.fr:441/prestashop-modules/');

/* defines constant for external BT FAQ URL */
define('_GMC_BT_FAQ_MAIN_URL', 'https://faq.businesstech.fr/');

/* defines constant for external Google taxonomy URL */
define('_GMC_GOOGLE_TAXONOMY_URL', 'http://www.google.com/basepages/producttype/');

/* defines loader gif name */
define('_GMC_LOADER_GIF', 'bx_loader.gif');

//define('_GMC_LOADER_GIF', 'loading.gif');
define('_GMC_LOADER_GIF_BIG', 'ajax-loader.gif');

/* defines title length */
define('_GMC_FEED_TITLE_LENGTH', 150);

/* defines the limit of additional images you can provide */
define('_GMC_IMG_LIMIT', 10);

/* defines the limit of number of custom label you can provide */
define('_GMC_CUSTOM_LABEL_LIMIT', 5);

/* defines the reporting Directory */
define('_GMC_REPORTING_DIR', _PS_MODULE_DIR_ . _GMC_MODULE_SET_NAME . '/reporting/');

/* defines variable for sql update */
$GLOBALS[_GMC_MODULE_NAME . '_SQL_UPDATE'] = array(
    'table' => array(
        'taxonomy' => _GMC_TAXONOMY_SQL_FILE,
        'tags' => _GMC_TAGS_SQL_FILE,
        'brands' => _GMC_BRANDS_SQL_FILE,
        'features_by_cat' => _GMC_FEATURES_SQL_FILE,
        'gsa_sync_data' => _GMC_GSA_SQL_FILE,
    ),
    'field' => array(
        array('field' => 'txt_taxonomy', 'table' => 'taxonomy_categories', 'file' => _GMC_TAXONOMY_CAT_SQL_FILE),
        array('field' => 'id_shop', 'table' => 'categories', 'file' => _GMC_TAXONOMY_SHOP_SQL_FILE),
        array('field' => 'id_shop', 'table' => 'tags', 'file' => _GMC_TAGS_ID_SQL_FILE),
        array('field' => 'id_shop', 'table' => 'taxonomy_categories', 'file' => _GMC_GOOGLECAT_ID_SQL_FILE),
        array('field' => 'id_shop', 'table' => 'features_by_cat', 'file' => _GMC_FEATURE_SHOP_SQL_FILE),
    )
);

/* defines variable for setting all request params : use for ajax request in to admin context */
$GLOBALS[_GMC_MODULE_NAME . '_REQUEST_PARAMS'] = array(
    'basic' => array('action' => 'update', 'type' => 'basic'),
    'gsa' => array('action' => 'update', 'type' => 'gsa'),
    'feed' => array('action' => 'update', 'type' => 'feed'),
    'feedDisplay' => array('action' => 'display', 'type' => 'feed'),
    'google' => array('action' => 'update', 'type' => 'google'),
    'feedList' => array('action' => 'display', 'type' => 'feedList'),
    'feedListUpdate' => array('action' => 'update', 'type' => 'feedList'),
    'reporting' => array('action' => 'update', 'type' => 'reporting'),
    'reportingBox' => array('action' => 'display', 'type' => 'reportingBox'),
    'tag' => array('action' => 'display', 'type' => 'tag'),
    'tagUpdate' => array('action' => 'update', 'type' => 'tag'),
    'googleCat' => array('action' => 'display', 'type' => 'googleCategories'),
    'googleCatUpdate' => array('action' => 'update', 'type' => 'googleCategoriesMatching'),
    'googleCatSync' => array('action' => 'update', 'type' => 'googleCategoriesSync'),
    'custom' => array('action' => 'display', 'type' => 'customLabel'),
    'customUpdate' => array('action' => 'update', 'type' => 'label'),
    'customDelete' => array('action' => 'delete', 'type' => 'label'),
    'autocomplete' => array('action' => 'display', 'type' => 'autocomplete'),
    'searchProduct' => array('action' => 'display', 'type' => 'searchProduct'),
    'dataFeed' => array('action' => 'update', 'type' => 'xml'),
    'stepPopup' => array('action' => 'display', 'type' => 'stepPopup'),
    'stepPopupUpd' => array('action' => 'update', 'type' => 'stepPopup'),
    'shopLink' => array('action' => 'update', 'type' => 'shopLink'),
);

/* defines variable for available list of tags to use */
$GLOBALS[_GMC_MODULE_NAME . '_TAG_LIST'] = array(
    'material',
    'pattern',
    'agegroup',
    'gender',
    'adult',
    'sizeType',
    'sizeSystem',
    'excluded_destination',
    'excluded_country',
);

/* defines variable to get the real value for excluded destination tag */
$GLOBALS['GMC_EXCLUDED_DEST_VALUE'] = array(
    'shopping' => 'Shopping Ads',
    'actions' => 'Buy on Google listings',
    'display' => 'Display Ads',
    'local' => 'Local inventory ads',
    'free-listing' => 'Free listings',
    'free-local-listing' => 'Free local listings',
);

/* defines variable for available list of label to use */
$GLOBALS[_GMC_MODULE_NAME . '_LABEL_LIST'] = array(
    'cats' => 'category',
    'brands' => 'brand',
    'suppliers' => 'supplier'
);

/* defines variable for available list of label to use */
$GLOBALS[_GMC_MODULE_NAME . '_PARAM_FOR_XML'] = array(
    'iShopId',
    'sFilename',
    'iLangId',
    'sLangIso',
    'sCountryIso',
    'sCurrencyIso',
    'iFloor',
    'iStep',
    'iTotal',
    'iProcess'
);

/* defines lang we have to remove on offer id for GSA */
$GLOBALS['GMC_LANG_TO_REMOVED_OFFERID'] = array(
    'FR', 'EN', 'US', 'GB', 'DE', 'IT', 'NL', 'ES', 'MX', 'ZA', 'CA', 'JA',
    'BR', 'CR', 'RU', 'SV', 'DA', 'NO', 'PL', 'TR', 'MS', 'PT', 'AR', 'ID',
    'HE', 'VN', 'UK', 'SV', 'TH', 'KO', 'FI', 'HU', 'AG', 'UR', 'VE', 'SK', 'RO', 'EI', 'LT'
);

/* defines GSA carrier available data */
$GLOBALS['GMC_GSA_CARRIERS_DATA'] = array(
    'ups', 'usps', 'fedex', 'dhl', 'ontrac', 'dhl express', 'deliv', 'dynamex', 'lasership', 'mpx', 'uds', 'efw',
    'jd logistics', 'yunexpress', 'china post', 'china ems', 'singapore post', 'pos malaysia', 'postnl', 'ptt', 'eub', 'chukou1', 'la poste', 'colissimo',
    'chronopost', 'gls', 'dpd', 'bpost', 'colis prive', 'boxtal', 'geodis', 'tnt', 'db schenker'
);
