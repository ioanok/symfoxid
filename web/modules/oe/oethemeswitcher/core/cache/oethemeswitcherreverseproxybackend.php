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
 * Class oeThemeSwitcherReverseProxyBackend defines activation events
 */
class oeThemeSwitcherReverseProxyBackend extends oeThemeSwitcherReverseProxyBackend_parent
{
    /**
     * Return hashed environment key. If no parameters are in key,
     * null will be returned.
     *
     * @return string
     */
    protected function _getEnvironmentKey()
    {
        $oManager = oxNew('oeThemeSwitcherThemeManager');
        $oUserAgent = $oManager->getUserAgent();

        $this->_addParamToEnvironmentKey($oManager->getThemeType(), "#oethemeswitcher_1#");
        $this->_addParamToEnvironmentKey($oUserAgent->getDeviceType(), "#oethemeswitcher_2#");

        return parent::_getEnvironmentKey();
    }
}
