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
 */

/**
 * Class for splitting user name.
 *
 * @package core
 */
class oePayPalFullName
{
    private $_sFirstName = '';
    private $_sLastName = '';

    /**
     * User first name and second name.
     *
     * @param string $sFullName
     */
    public function __construct($sFullName)
    {
        $this->_split($sFullName);
    }

    /**
     * Return user first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->_sFirstName;
    }

    /**
     * Return user second name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->_sLastName;
    }

    /**
     * Split user full name to first name and second name.
     *
     * @param string $sFullName
     */
    protected function _split($sFullName)
    {
        $aNames = explode(" ", trim($sFullName), 2);

        $this->_sFirstName = trim($aNames[0]);
        $this->_sLastName = trim($aNames[1]);
    }
}
