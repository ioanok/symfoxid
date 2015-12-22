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
 * Company VAT identification number (VATIN)
 */
class oxCompanyVatIn
{

    /**
     * VAT identification number
     *
     * @var string
     */
    private $_sCompanyVatNumber;

    /**
     * Constructor
     *
     * @param string $sCompanyVatNumber - company vat identification number.
     */
    public function __construct($sCompanyVatNumber)
    {
        $this->_sCompanyVatNumber = $sCompanyVatNumber;
    }

    /**
     * Returns country code from number.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return (string) oxStr::getStr()->strtoupper(oxStr::getStr()->substr($this->_cleanUp($this->_sCompanyVatNumber), 0, 2));
    }

    /**
     * Returns country code from number.
     *
     * @return string
     */
    public function getNumbers()
    {
        return (string) oxStr::getStr()->substr($this->_cleanUp($this->_sCompanyVatNumber), 2);
    }

    /**
     * Removes spaces and symbols: '-',',','.' from string
     *
     * @param string $sValue Value.
     *
     * @return string
     */
    protected function _cleanUp($sValue)
    {
        return (string) oxStr::getStr()->preg_replace("/\s|-/", '', $sValue);
    }


    /**
     * Cast to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_sCompanyVatNumber;
    }
}
