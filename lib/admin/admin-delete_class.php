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

class BT_AdminDelete implements BT_IAdmin
{
    /**
     * delete content
     *
     * @param string $sType => define which method to execute
     * @param array $aParam
     * @return array
     */
    public function run($sType, array $aParam = null)
    {
        // set variables
        $aDisplayData = array();

        switch ($sType) {
            case 'label' : // use case - delete custom label
                // execute match function
                $aDisplayData = call_user_func_array(array($this, 'delete' . ucfirst($sType)), array($aParam));
                break;
            default :
                break;
        }
        return $aDisplayData;
    }

    /**
     * delete one tag label
     *
     * @param array $aPost
     * @return array
     */
    private function deleteLabel(array $aPost)
    {
        // clean headers
        @ob_end_clean();

        // set
        $aData = array();

        try {
            $iTagId = Tools::getValue('iTagId');

            if (empty($iTagId)) {
                throw new Exception(GMerchantCenter::$oModule->l('Your Custom label ID is not valid',
                        'admin-update_class') . '.', 700);
            } else {
                // include
                require_once(_GMC_PATH_LIB . 'module-dao_class.php');

                BT_GmcModuleDao::deleteGmcTag($iTagId, $GLOBALS[_GMC_MODULE_NAME . '_LABEL_LIST']);
            }
        } catch (Exception $e) {
            $aData['aErrors'][] = array('msg' => $e->getMessage(), 'code' => $e->getCode());
        }

        // get configuration options
        BT_GmcModuleTools::getConfiguration();

        // require admin configure class - to factorise
        require_once(_GMC_PATH_LIB_ADMIN . 'admin-display_class.php');

        // get run of admin display in order to display first page of admin with basics settings updated
        $aDisplay = BT_AdminDisplay::create()->run('google');

        // use case - empty error and updating status
        $aDisplay['assign'] = array_merge($aDisplay['assign'], array(
            'bUpdate' => (empty($aData['aErrors']) ? true : false),
        ), $aData);

        return $aDisplay;
    }


    /**
     * set singleton
     *
     * @return obj
     */
    public static function create()
    {
        static $oDelete;

        if (null === $oDelete) {
            $oDelete = new BT_AdminDelete();
        }
        return $oDelete;
    }
}
