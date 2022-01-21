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

class BT_GmcHookAction extends BT_GmcHookBase
{
    /**
     * execute hook
     *
     * @param array $aParams
     * @return array
     */
    public function run(array $aParams = null)
    {
        require_once(_GMC_GSA_LIB . 'gsa-dao_class.php');

        // set variables
        $aDisplayHook = array();

        switch ($this->sHook) {
            case 'searchProduct':
                // use case - display nothing only process storage in order to send an email
                $aDisplayHook = call_user_func_array(array($this, 'searchProduct'), array($aParams));
                break;
            case 'updateOrderStatus':
                // use case - display nothing only process storage in order to send an email
                $aDisplayHook = call_user_func_array(array($this, 'updateOrderStatus'), array($aParams));
                break;
            default:
                break;
        }

        return $aDisplayHook;
    }

    /**
     * search product with the autocomplete feature
     *
     * @param array $aParams
     * @return array
     */
    private function searchProduct(array $aParams = null)
    {
        return array('tpl' => _GMC_TPL_HOOK_PATH . _GMC_TPL_ORDER_CONFIRMATION, 'assign' => array());
    }

    /**
     * action on update status to manage the link with GSA
     *
     * @param array $aParams
     * @return array
     */
    private function updateOrderStatus(array $aParams = null)
    {
        if (!isset($_POST['source'])) {

            //Get the gsa order id
            $sGsaOrderId = BT_GmcGsaDao::getGsaOrderFromPsOrder((int) $aParams['id_order']);

            if (!empty($sGsaOrderId)) {
                //Manage case paid
                if (!BT_GmcGsaDao::updateGsaOrders('is_paid', (int) $aParams['newOrderStatus']->paid, (string) $sGsaOrderId)) {
                    throw new Exception("Update paid status failed", 100);
                } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'paid', (string) $sGsaOrderId)) {
                    throw new Exception("Update paid status failed", 101);
                }

                // Manage case shipped
                if (!empty($aParams['newOrderStatus']->shipped)) {
                    if (!BT_GmcGsaDao::updateGsaOrders('is_shipped', (int) $aParams['newOrderStatus']->shipped, (string) $sGsaOrderId)) {
                        throw new Exception("Update shipped status failed", 101);
                    } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'shipped', (string) $sGsaOrderId)) {
                        throw new Exception("Update shipped status failed", 101);
                    }
                }

                //Manage delivered orders only if the shipped status sync is ok
                $bIsShipped = BT_GmcGsaDao::isOrderShippedSync($sGsaOrderId, 1);
                if (!empty($bIsShipped)) {
                    if (!BT_GmcGsaDao::updateGsaOrders('is_delivered', (int) $aParams['newOrderStatus']->delivery, (string) $sGsaOrderId)) {
                        throw new Exception("Update delivery status failed", 101);
                    } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'delivered', (string) $sGsaOrderId)) {
                        throw new Exception("Update delivered status failed", 101);
                    }
                }

                // Manage case prepared
                // Use the id to check if the order preparation is in progress in PS the ID is 3
                $bPrepared = $aParams['newOrderStatus']->id == 3 ? 1 : 0;
                if (!empty($bPrepared)) {
                    if (!BT_GmcGsaDao::updateGsaOrders('is_prepared', (int) $bPrepared, (string) $sGsaOrderId)) {
                        throw new Exception("Update preparation status failed", 101);
                    } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'preparation', (string) $sGsaOrderId)) {
                        throw new Exception("Update shipped status failed", 101);
                    }
                }

                // Manage case refund
                // Use the id to check if the order refund is done in PS the ID is 7
                $bRefunded = $aParams['newOrderStatus']->id == 7 ? 1 : 0;
                if (!empty($bRefunded)) {
                    if (!BT_GmcGsaDao::updateGsaOrders('is_refunded', (int) $bRefunded, (string) $sGsaOrderId)) {
                        throw new Exception("Update refunded status failed", 102);
                    } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'refunded', (string) $sGsaOrderId)) {
                        throw new Exception("Update refunded status failed", 101);
                    }
                }

                //Check if there is order lines to refund
                $aProductLinesToRefund = BT_GmcGsaDao::haveOrderLineToRefund((int) $aParams['id_order']);
                // If we find some product line to refund
                if (!empty($aProductLinesToRefund)) {
                    //We update the module table column gsa_orders_data
                    if (!BT_GmcGsaDao::updateGsaOrders('is_product_refunded', 1, (string) $sGsaOrderId)) {
                        throw new Exception("Update product refunded status failed", 102);
                    } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'partial refund', (string) $sGsaOrderId)) {
                        throw new Exception("Update partial refund status failed", 101);
                    } else {
                        if (is_array($aProductLinesToRefund) && isset($aProductLinesToRefund)) {
                            //For each order lines refunded we update our module table
                            foreach ($aProductLinesToRefund as $aProductData) {
                                if (!BT_GmcGsaDao::updateGsaOrdersProducts('quantity_refunded', (int) $aProductData['product_quantity_refunded'], (string) $sGsaOrderId, (int) $aProductData['product_id'],  (int) $aProductData['product_attribute_id'])) {
                                    throw new Exception("Update Gsa order prorudct refunded failed", 103);
                                }
                            }
                        }
                    }
                }

                $aProductLinesToReturn = BT_GmcGsaDao::haveOrderLineToReturn((int) $aParams['id_order']);
                $aReturnedData = OrderReturn::getOrdersReturn($aParams['cart']->id_customer, (int) $aParams['id_order']);
                foreach ($aReturnedData as $aData) {
                    // Only manage the returned product if we have data on our table and if PS order return is 2 (waiting package from customer)
                    if (!empty($aProductLinesToReturn) && $aData['sate'] == 1) {
                        //We update the module table column gsa_orders_data
                        if (!BT_GmcGsaDao::updateGsaOrders('is_returned', 1, (string) $sGsaOrderId)) {
                            throw new Exception("Update product return status failed", 102);
                        } elseif (!BT_GmcGsaDao::updateGsaOrders('order_status', 'partial returned', (string) $sGsaOrderId)) {
                            throw new Exception("Update partial returned status failed", 101);
                        } else {
                            if (is_array($aProductLinesToReturn) && isset($aProductLinesToReturn)) {
                                //For each order lines refunded we update our module table
                                foreach ($aProductLinesToReturn as $aProductData) {
                                    if (!BT_GmcGsaDao::updateGsaOrdersProducts('quantity_returned', (int) $aProductData['product_quantity_return'], (string) $sGsaOrderId, (int) $aProductData['product_id'],  (int) $aProductData['product_attribute_id'])) {
                                        throw new Exception("Update Gsa return order failes", 103);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
