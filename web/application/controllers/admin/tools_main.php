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
 * CVS export manager.
 * Performs export function according to user chosen categories.
 * Admin Menu: Maine Menu -> Im/Export -> Export.
 */
class Tools_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), passes data to Smarty engine
     * and returns name of template file "imex_export.tpl".
     *
     * @return string
     */
    public function render()
    {
        if ($this->getConfig()->isDemoShop()) {
            oxRegistry::getUtils()->showMessageAndExit("Access denied !");
        }

        parent::render();

        $oAuthUser = oxNew('oxuser');
        $oAuthUser->loadAdminUser();
        $this->_aViewData["blIsMallAdmin"] = $oAuthUser->oxuser__oxrights->value == "malladmin";

        $blShowUpdateViews = $this->getConfig()->getConfigParam('blShowUpdateViews');
        $this->_aViewData['showViewUpdate'] = (isset($blShowUpdateViews) && !$blShowUpdateViews) ? false : true;

        return "tools_main.tpl";
    }
}
