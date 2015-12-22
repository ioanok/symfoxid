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
class oeThemeSwitcherThemeManager
{
    /**
     * Theme type
     *
     * @var string
     */
    protected $_sThemeType = null;

    /**
     * User Agent
     *
     * @var object
     */
    protected $_oUserAgent = null;

    /**
     * User Agent setter
     *
     * @param oeThemeSwitcherUserAgent $oUserAgent user agent
     */
    public function setUserAgent($oUserAgent)
    {
        $this->_oUserAgent = $oUserAgent;
    }


    /**
     * User Agent getter
     *
     * @return oeThemeSwitcherUserAgent
     */
    public function getUserAgent()
    {
        if (is_null($this->_oUserAgent)) {
            $this->_oUserAgent = oxNew('oeThemeSwitcherUserAgent');
        }

        return $this->_oUserAgent;
    }

    /**
     * Config getter
     *
     * @return oxConfig
     */
    public function getConfig()
    {
        return oxRegistry::getConfig();
    }

    /**
     * Return theme type from request
     *
     * @return string
     */
    protected function _getThemeTypeFromRequest()
    {
        $sRequestedThemeType = $this->getConfig()->getRequestParameter('themeType');
        if ($sRequestedThemeType) {
            oxRegistry::get('oxUtilsServer')->setOxCookie('sThemeType', $sRequestedThemeType);
        }

        return $sRequestedThemeType;
    }

    /**
     * Return theme type from cookie
     *
     * @return string
     */
    protected function _getThemeTypeFromCookie()
    {
        return oxRegistry::get('oxUtilsServer')->getOxCookie('sThemeType');
    }


    /**
     * Return requested theme type
     *
     * @return string
     */
    public function getRequestedThemeType()
    {
        $sRequestedThemeType = $this->_getThemeTypeFromRequest();
        if (empty($sRequestedThemeType)) {
            $sRequestedThemeType = $this->_getThemeTypeFromCookie();
        }

        return $sRequestedThemeType;
    }

    /**
     * Check if requested mobile theme
     *
     * @return bool
     */
    public function isMobileThemeRequested()
    {
        return ($this->getThemeType() == 'mobile');
    }


    /**
     * Return theme type
     *
     * @return string
     */
    public function getThemeType()
    {
        if (is_null($this->_sThemeType)) {
            $sRequestedThemeType = $this->getRequestedThemeType();
            if (empty($sRequestedThemeType)) {
                $sRequestedThemeType = $this->getUserAgent()->getDeviceType();
            }
            $this->_sThemeType = $sRequestedThemeType;
        }

        return $this->_sThemeType;
    }
}
