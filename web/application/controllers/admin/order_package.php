<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 * @version   OXID eShop PE
 */

/**
 * Admin order package manager.
 * Collects order package information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders.
 */
class Order_Package extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), fetches order info from DB,
     * passes it to Smarty engine and returns name of template file.
     * "order_package.tpl"
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $aOrders = oxNew('oxlist');
        $aOrders->init('oxorder');
        $sSql = "select * from oxorder where oxorder.oxsenddate = '0000-00-00 00:00:00' and oxorder.oxshopid = '" .
                $myConfig->getShopId() . "' order by oxorder.oxorderdate asc limit 5000";
        $aOrders->selectString($sSql);

        $this->_aViewData['resultset'] = $aOrders;

        return "order_package.tpl";
    }
}
