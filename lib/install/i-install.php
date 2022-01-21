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

interface BT_IInstall
{
    /**
     * make installation of module
     *
     * @param mixed $mParam : array (constant to update with Configuration:updateValue) in config install / string of sql filename in sql install / array of admin tab to install
     * @return bool
     */
    public static function install($mParam = null);

    /**
     * make uninstallation of module
     *
     * @param mixed $mParam : array (constant to update with Configuration:deleteByName) in config install / string of sql filename in sql install / array of admin tab to uninstall
     * @return bool
     */
    public static function uninstall($mParam = null);
}