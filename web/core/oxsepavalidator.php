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
 * SEPA (Single Euro Payments Area) validation class
 *
 */
class oxSepaValidator
{

    /**
     * @var array IBAN Code Length array
     */
    protected $_aIBANCodeLengths = array(
        'AL' => 28,
        'AD' => 24,
        'AT' => 20,
        'AZ' => 28,
        'BH' => 22,
        'BE' => 16,
        'BA' => 20,
        'BR' => 29,
        'BG' => 22,
        'CR' => 21,
        'HR' => 21,
        'CY' => 28,
        'CZ' => 24,
        'DK' => 18, // Same DENMARK
        'FO' => 18, // Same DENMARK
        'GL' => 18, // Same DENMARK
        'DO' => 28,
        'EE' => 20,
        'FI' => 18,
        'FR' => 27,
        'GE' => 22,
        'DE' => 22,
        'GI' => 23,
        'GR' => 27,
        'GT' => 28,
        'HU' => 28,
        'IS' => 26,
        'IE' => 22,
        'IL' => 23,
        'IT' => 27,
        'KZ' => 20,
        'KW' => 30,
        'LV' => 21,
        'LB' => 28,
        'LI' => 21,
        'LT' => 20,
        'LU' => 20,
        'MK' => 19,
        'MT' => 31,
        'MR' => 27,
        'MU' => 30,
        'MD' => 24,
        'MC' => 27,
        'ME' => 22,
        'NL' => 18,
        'NO' => 15,
        'PK' => 24,
        'PS' => 29,
        'PL' => 28,
        'PT' => 25,
        'RO' => 24,
        'SM' => 27,
        'SA' => 24,
        'RS' => 22,
        'SK' => 24,
        'SI' => 19,
        'ES' => 24,
        'SE' => 24,
        'CH' => 21,
        'TN' => 24,
        'TR' => 26,
        'AE' => 23,
        'GB' => 22,
        'VG' => 24
    );

    /**
     * Business identifier code validation
     *
     * @param string $sBIC code to check
     *
     * @return bool
     */
    public function isValidBIC($sBIC)
    {
        $oBICValidator = oxNew('oxSepaBICValidator');

        return $oBICValidator->isValid($sBIC);
    }

    /**
     * International bank account number validation
     *
     * @param string $sIBAN code to check
     *
     * @return bool
     */
    public function isValidIBAN($sIBAN)
    {
        $oIBANValidator = oxNew('oxSepaIBANValidator');
        $oIBANValidator->setCodeLengths($this->getIBANCodeLengths());

        return $oIBANValidator->isValid($sIBAN);
    }

    /**
     * Validation of IBAN registry
     *
     * @param array $aIBANRegistry
     *
     * @deprecated since v5.1.2 (2013-12-11); Use oxSepaIBANValidator::isCodeLengthsValid().
     *
     * @return bool
     */
    public function isValidIBANRegistry($aIBANRegistry = null)
    {
        $oIBANValidator = oxNew('oxSepaIBANValidator');

        if (is_null($aIBANRegistry)) {
            $aIBANRegistry = $this->getIBANCodeLengths();
        }

        return $oIBANValidator->isCodeLengthsValid($aIBANRegistry);
    }


    /**
     * Set IBAN Registry
     *
     * @param array $aIBANRegistry
     *
     * @deprecated since v5.1.2 (2013-12-11); Use oxSepaIBANValidator::setCodeLengths().
     *
     * @return bool
     */
    public function setIBANRegistry($aIBANRegistry)
    {
        if ($this->isValidIBANRegistry($aIBANRegistry)) {
            $this->_aIBANCodeLengths = $aIBANRegistry;

            return true;
        } else {
            return false;
        }
    }

    /**
     * Get IBAN length by country data
     *
     * @deprecated since v5.1.2 (2013-12-11); Use oxSepaValidator::getIBANCodeLengths().
     *
     * @return array
     */
    public function getIBANRegistry()
    {
        return $this->_aIBANCodeLengths;
    }

    /**
     * Get IBAN length by country data
     *
     * @return array
     */
    public function getIBANCodeLengths()
    {
        return $this->_aIBANCodeLengths;
    }
}
