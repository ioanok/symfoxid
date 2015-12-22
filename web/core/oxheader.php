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
 * HTTP headers formator.
 * Collects HTTP headers and form HTTP header.
 */
class oxHeader
{

    protected $_aHeader = array();

    /**
     * Sets header.
     *
     * @param string $sHeader header value.
     */
    public function setHeader($sHeader)
    {
        $sHeader = str_replace(array("\n", "\r"), '', $sHeader);
        $this->_aHeader[] = (string) $sHeader . "\r\n";
    }

    /**
     * Return header.
     *
     * @return array
     */
    public function getHeader()
    {
        return $this->_aHeader;
    }

    /**
     * Outputs HTTP header.
     */
    public function sendHeader()
    {
        foreach ($this->_aHeader as $sHeader) {
            if (isset($sHeader)) {
                header($sHeader);
            }
        }
    }

    /**
     * Set to not cacheable.
     *
     * @todo check browser for different no-cache signs.
     */
    public function setNonCacheable()
    {
        $sHeader = "Cache-Control: no-cache;";
        $this->setHeader($sHeader);
    }
}
