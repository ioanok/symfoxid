<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.

 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 */

/**
 * Main shop configuration class.
 *
 * @package core
 */
class oeThemeSwitcherConfig extends oeThemeSwitcherConfig_parent
{
    /**
     * Bool variable true if modules configs are loaded, otherwise false
     *
     * @var bool
     */
    protected $_blIsModuleConfigLoaded = false;

    /**
     * Theme manager object
     *
     * @var oeThemeSwitcherThemeManager
     */
    protected $_oThemeManager = null;

    /**
     * Returns config parameter value if such parameter exists
     *
     * @param string $sName config parameter name
     *
     * @return mixed
     */
    public function getConfigParam($sName)
    {
        $sReturn = parent::getConfigParam($sName);

        if ($sName == "sCustomTheme") {
            //load module configs
            if (!$this->_blIsModuleConfigLoaded) {
                $this->_loadVarsFromDb($this->getShopId(), null, oxConfig::OXMODULE_MODULE_PREFIX);
                $this->_blIsModuleConfigLoaded = true;
            }

            // check for mobile devices
            if ($this->oeThemeSwitcherGetThemeManager()->isMobileThemeRequested() && !$this->isAdmin()) {
                return $this->_aConfigParams['sOEThemeSwitcherMobileTheme'];
            }
        }

        return $sReturn;
    }

    /**
     * Return current active theme
     *
     * @return string
     */
    public function oeThemeSwitcherGetActiveThemeId()
    {
        $sCustomTheme = $this->getConfigParam('sCustomTheme');
        if ($sCustomTheme) {
            return $sCustomTheme;
        }

        return $this->getConfigParam('sTheme');
    }

    /**
     * Return theme manager
     *
     * @return oeThemeSwitcherThemeManager
     */
    public function oeThemeSwitcherGetThemeManager()
    {
        if ($this->_oThemeManager == null) {
            $this->_oThemeManager = oxNew('oeThemeSwitcherThemeManager');
        }

        return $this->_oThemeManager;
    }
}
