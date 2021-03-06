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
 * Admin systeminfo manager.
 * Returns template, that arranges two other templates ("delivery_list.tpl"
 * and "delivery_main.tpl") to frame.
 */
class Tools extends oxAdminView
{

    /**
     * Executes parent method parent::render(), prints shop and
     * PHP configuration information.
     *
     * @return string
     */
    public function render()
    {
        if ($this->getConfig()->isDemoShop()) {
            return oxRegistry::getUtils()->showMessageAndExit("Access denied !");
        }

        parent::render();

        return "tools.tpl";
    }
}
