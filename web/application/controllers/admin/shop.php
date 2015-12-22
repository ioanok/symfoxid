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
 * Admin shop manager.
 * Returns template, that arranges two other templates ("shop_list.tpl"
 * and "shop_main.tpl") to frame.
 * Admin Menu: Main Menu -> Core Settings.
 */
class Shop extends oxAdminView
{

    /**
     * Executes parent method parent::render() and returns name of template
     * file "shop.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $sCurrentAdminShop = oxRegistry::getSession()->getVariable("currentadminshop");

        if (!$sCurrentAdminShop) {
            if (oxRegistry::getSession()->getVariable("malladmin")) {
                $sCurrentAdminShop = "oxbaseshop";
            } else {
                $sCurrentAdminShop = oxRegistry::getSession()->getVariable("actshop");
            }
        }

        $this->_aViewData["currentadminshop"] = $sCurrentAdminShop;
        oxRegistry::getSession()->setVariable("currentadminshop", $sCurrentAdminShop);


        return "shop.tpl";
    }
}
