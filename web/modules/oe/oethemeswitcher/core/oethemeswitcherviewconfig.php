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
 * View config data access class. Keeps most
 * of getters needed for formatting various urls,
 * config parameters, session information etc.
 */
class oeThemeSwitcherViewConfig extends oeThemeSwitcherViewConfig_parent
{
    /**
     * User Agent.
     *
     * @var object
     */
    protected $_oUserAgent = null;

    /**
     * User Agent getter.
     *
     * @return oeThemeSwitcherUserAgent
     */
    public function oeThemeSwitcherGetUserAgent()
    {
        if (is_null($this->_oUserAgent)) {
            $this->_oUserAgent = oxNew('oeThemeSwitcherUserAgent');
        }

        return $this->_oUserAgent;
    }
}
