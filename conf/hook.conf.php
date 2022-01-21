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

require_once(dirname(__FILE__) . '/common.conf.php');

/* defines gsa library path */
define('_GMC_PATH_GSA_LIB', _GMC_PATH_ROOT . 'lib/');

/* defines front tpl path */
define('_GMC_GSA_LIB', _GMC_PATH_GSA_LIB . 'shopping-action/');

/* defines HOOK  path */
define('_GMC_PATH_LIB_HOOK', _GMC_PATH_GSA_LIB . 'hook/');

/* defines hook empty tpl path */
define('_GMC_TPL_EMPTY', 'empty.tpl');

/* defines variable for setting all request params */
$GLOBALS[_GMC_MODULE_NAME . '_REQUEST_PARAMS'] = array(
    'search' => array('action' => 'search', 'type' => 'product'),
);
