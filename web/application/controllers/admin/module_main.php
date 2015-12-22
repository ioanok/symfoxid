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
 * Admin article main deliveryset manager.
 * There is possibility to change deliveryset name, article, user
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling -> Main Sets.
 */
class Module_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates deliveryset category tree,
     * passes data to Smarty engine and returns name of template file "deliveryset_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        if (oxRegistry::getConfig()->getRequestParameter("moduleId")) {
            $sModuleId = oxRegistry::getConfig()->getRequestParameter("moduleId");
        } else {
            $sModuleId = $this->getEditObjectId();
        }

        $oModule = oxNew('oxModule');

        if ($sModuleId) {
            if ($oModule->load($sModuleId)) {
                $iLang = oxRegistry::getLang()->getTplLanguage();

                $this->_aViewData["oModule"] = $oModule;
                $this->_aViewData["sModuleName"] = basename($oModule->getInfo("title", $iLang));
                $this->_aViewData["sModuleId"] = str_replace("/", "_", $oModule->getModulePath());
            } else {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay(new oxException('EXCEPTION_MODULE_NOT_LOADED'));
            }
        }

        parent::render();

        return 'module_main.tpl';
    }

    /**
     * Activate module
     *
     * @return null
     */
    public function activateModule()
    {
        $sModule = $this->getEditObjectId();
        /** @var oxModule $oModule */
        $oModule = oxNew('oxModule');
        if (!$oModule->load($sModule)) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay(new oxException('EXCEPTION_MODULE_NOT_LOADED'));

            return;
        }
        try {
            /** @var oxModuleCache $oModuleCache */
            $oModuleCache = oxNew('oxModuleCache', $oModule);
            /** @var oxModuleInstaller $oModuleInstaller */
            $oModuleInstaller = oxNew('oxModuleInstaller', $oModuleCache);

            if ($oModuleInstaller->activate($oModule)) {
                $this->_aViewData["updatenav"] = "1";
            }
        } catch (oxException $oEx) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
            $oEx->debugOut();
        }
    }

    /**
     * Deactivate module
     *
     * @return null
     */
    public function deactivateModule()
    {
        $sModule = $this->getEditObjectId();
        /** @var oxModule $oModule */
        $oModule = oxNew('oxModule');
        if (!$oModule->load($sModule)) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay(new oxException('EXCEPTION_MODULE_NOT_LOADED'));

            return;
        }
        try {
            /** @var oxModuleCache $oModuleCache */
            $oModuleCache = oxNew('oxModuleCache', $oModule);
            /** @var oxModuleInstaller $oModuleInstaller */
            $oModuleInstaller = oxNew('oxModuleInstaller', $oModuleCache);

            if ($oModuleInstaller->deactivate($oModule)) {
                $this->_aViewData["updatenav"] = "1";
            }
        } catch (oxException $oEx) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
            $oEx->debugOut();
        }
    }
}
