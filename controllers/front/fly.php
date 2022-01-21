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

class GMerchantCenterFlyModuleFrontController extends ModuleFrontController
{
    /**
     * method manage post data
     *
     * @throws Exception
     * @return bool
     */
    public function postProcess()
    {
        // get the token
        $sToken = Tools::getValue('token');

        if ($sToken == GMerchantCenter::$conf['GMC_FEED_TOKEN']) {
            // use case - handle to generate XML files
            $_POST['sAction'] = Tools::getIsset('sAction') ? Tools::getValue('sAction') : 'generate';
            $_POST['sType'] = Tools::getIsset('sType') ? Tools::getValue('sType') : 'flyOutput';

            $this->module->getContent();

        } else {
            return $this->module->l('Internal server error! (security error)', 'cron');
        }

        // No return text to not break the fly output
        die();
    }
}
