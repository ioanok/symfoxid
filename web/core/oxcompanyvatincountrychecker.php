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
 * Company VAT identification number checker. Check if number belongs to the country.
 */
class oxCompanyVatInCountryChecker extends oxCompanyVatInChecker implements oxICountryAware
{

    /**
     * Error string if country mismatch
     */
    const ERROR_ID_NOT_VALID = 'ID_NOT_VALID';

    /**
     * Country
     *
     * @var oxCountry
     */
    private $_oCountry = null;

    /**
     * Country setter
     *
     * @param oxCountry $oCountry
     */
    public function setCountry(oxCountry $oCountry)
    {
        $this->_oCountry = $oCountry;
    }

    /**
     * Country getter
     *
     * @return oxCountry
     */
    public function getCountry()
    {
        return $this->_oCountry;
    }

    /**
     * Validates.
     *
     * @param oxCompanyVatIn $oVatIn
     *
     * @return bool
     */
    public function validate(oxCompanyVatIn $oVatIn)
    {
        $blResult = false;
        $oCountry = $this->getCountry();
        if (!is_null($oCountry)) {
            $blResult = ($oCountry->getVATIdentificationNumberPrefix() === $oVatIn->getCountryCode());
            if (!$blResult) {
                $this->setError(self::ERROR_ID_NOT_VALID);
            }
        }

        return $blResult;
    }
}
