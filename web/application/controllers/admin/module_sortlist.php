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
 * Extensions sorting list handler.
 * Admin Menu: Extensions -> Module -> Installed Shop Modules.
 */
class Module_SortList extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), loads active and disabled extensions,
     * checks if there are some deleted and registered modules and returns name of template file "module_sortlist.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oModuleList = oxNew("oxModuleList");

        $this->_aViewData["aExtClasses"] = $this->getConfig()->getModulesWithExtendedClass();
        $this->_aViewData["aDisabledModules"] = $oModuleList->getDisabledModuleClasses();

        // checking if there are any deleted extensions
        if (oxRegistry::getSession()->getVariable("blSkipDeletedExtChecking") == false) {
            $aDeletedExt = $oModuleList->getDeletedExtensions();
        }

        if (!empty($aDeletedExt)) {
            $this->_aViewData["aDeletedExt"] = $aDeletedExt;
        }

        return 'module_sortlist.tpl';
    }

    /**
     * Saves updated aModules config var
     */
    public function save()
    {
        $aModule = oxRegistry::getConfig()->getRequestParameter("aModules");

        $aModules = json_decode($aModule, true);

        $oModuleInstaller = oxNew('oxModuleInstaller');
        $aModules = $oModuleInstaller->buildModuleChains($aModules);

        $this->getConfig()->saveShopConfVar("aarr", "aModules", $aModules);

    }

    /**
     * Removes extension metadata from eShop
     *
     * @return null
     */
    public function remove()
    {
        //if user selected not to update modules, skipping all updates
        if (oxRegistry::getConfig()->getRequestParameter("noButton")) {
            oxRegistry::getSession()->setVariable("blSkipDeletedExtChecking", true);

            return;
        }

        $oModuleList = oxNew("oxModuleList");
        $oModuleList->cleanup();
    }

}
