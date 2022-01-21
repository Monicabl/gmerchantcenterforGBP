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

class BT_GmcHookCtrl
{
    /**
     * @var obj $_oHook : defines hook object to display
     */
    private $_oHook = null;

    /**
     * instantiate the matching hook class
     *
     * @param string $sType : type of interface to execute
     * @param string $sAction
     */
    public function __construct($sType, $sAction)
    {
        // include interface of hook executing
        require_once(_GMC_PATH_LIB_HOOK . 'hook-base_class.php');

        // check if file exists
        if (!file_exists(_GMC_PATH_LIB_HOOK . 'hook-' . $sType . '_class.php')) {
            throw new Exception("no valid file", 100);
        } else {
            // include matched hook object
            require_once(_GMC_PATH_LIB_HOOK . 'hook-' . $sType . '_class.php');

            if (!class_exists('BT_GmcHook' . ucfirst($sType))
                && !method_exists('BT_GmcHook' . ucfirst($sType), 'run')
            ) {
                throw new Exception("no valid class and method", 101);
            } else {
                // set class name
                $sClassName = 'BT_GmcHook' . ucfirst($sType);

                // instantiate
                $this->_oHook = new $sClassName($sAction);
            }
        }
    }

    /**
     * execute hook
     *
     * @param array $aParams
     * @return array $aDisplay : empty => false / not empty => true
     */
    public function run(array $aParams = null)
    {
        return $this->_oHook->run($aParams);
    }
}
