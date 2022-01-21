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

abstract class BT_GmcHookBase
{
    /**
     * @var string $sHook : define hook display or action
     */
    protected $sHook = null;

    /**
     * assigns few information about hook
     *
     * @param string $sHook
     */
    public function __construct($sHook)
    {
        // set hook
        $this->sHook = $sHook;
    }

    /**
     * execute hook
     *
     * @category hook collection
     * @uses
     *
     * @param array $aParams
     * @return array
     */
    abstract public function run(array $aParams = null);
}
