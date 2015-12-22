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
class Theme_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates deliveryset category tree,
     * passes data to Smarty engine and returns name of template file "deliveryset_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $soxId = $this->getEditObjectId();

        $oTheme = oxNew('oxTheme');

        if (!$soxId) {
            $soxId = $oTheme->getActiveThemeId();
        }

        if ($oTheme->load($soxId)) {
            $this->_aViewData["oTheme"] = $oTheme;
        } else {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay(oxNew("oxException", 'EXCEPTION_THEME_NOT_LOADED'));
        }

        parent::render();

        if ($this->themeInConfigFile()) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('EXCEPTION_THEME_SHOULD_BE_ONLY_IN_DATABASE');
        }

        return 'theme_main.tpl';
    }

    /**
     * Check if theme config is in config file.
     *
     * @return bool
     */
    public function themeInConfigFile()
    {
        $blThemeSet = isset($this->getConfig()->sTheme);
        $blCustomThemeSet = isset($this->getConfig()->sCustomTheme);

        if ($blThemeSet || $blCustomThemeSet) {
            return true;
        }

        return false;
    }


    /**
     * Set theme
     *
     * @return null
     */
    public function setTheme()
    {
        $sTheme = $this->getEditObjectId();
        /** @var oxTheme $oTheme */
        $oTheme = oxNew('oxtheme');
        if (!$oTheme->load($sTheme)) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay(oxNew("oxException", 'EXCEPTION_THEME_NOT_LOADED'));

            return;
        }
        try {
            $oTheme->activate();
            $this->resetContentCache();
        } catch (oxException $oEx) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
            $oEx->debugOut();
        }
    }
}
