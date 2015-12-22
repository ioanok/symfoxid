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
 * Admin Econda manager.
 *
 * @subpackage dyn
 */
class dyn_econda extends Shop_Config
{

    /**
     * Creates shop object, passes shop data to Smarty engine and returns name of
     * template file "dyn_trusted.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $this->_aViewData['oxid'] = $this->getConfig()->getShopId();

        return "dyn_econda.tpl";
    }

    /**
     * Returns view id
     *
     * @return string
     */
    public function getViewId()
    {
        return 'dyn_interface';
    }
}
