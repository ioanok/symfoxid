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
 * Company VAT identification number validator. Executes added validators on given VATIN.
 */
class oxCompanyVatInValidator
{

    /**
     * @var oxCountry
     */
    private $_oCountry = null;

    /**
     * Array of validators (checkers)
     *
     * @var array
     */
    private $_aCheckers = array();

    /**
     * Error message
     *
     * @var string
     */
    private $_sError = '';

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
     * Error setter
     *
     * @param string $sError
     */
    public function setError($sError)
    {
        $this->_sError = $sError;
    }

    /**
     * Error getter
     *
     * @return string
     */
    public function getError()
    {
        return $this->_sError;
    }

    /**
     * Constructor
     *
     * @param oxCountry $oCountry
     */
    public function __construct(oxCountry $oCountry)
    {
        $this->setCountry($oCountry);
    }

    /**
     * Adds validator
     *
     * @param oxCompanyVatInChecker $oValidator
     */
    public function addChecker(oxCompanyVatInChecker $oValidator)
    {
        $this->_aCheckers[] = $oValidator;
    }

    /**
     * Returns added validators
     *
     * @return array
     */
    public function getCheckers()
    {
        return $this->_aCheckers;
    }

    /**
     * Validate company VAT identification number.
     *
     * @param oxCompanyVatIn $oCompanyVatNumber
     *
     * @return bool
     */
    public function validate(oxCompanyVatIn $oCompanyVatNumber)
    {
        $blResult = false;
        $aValidators = $this->getCheckers();

        foreach ($aValidators as $oValidator) {
            $blResult = true;
            if ($oValidator instanceof oxICountryAware) {
                $oValidator->setCountry($this->getCountry());
            }

            if (!$oValidator->validate($oCompanyVatNumber)) {
                $blResult = false;
                $this->setError($oValidator->getError());
                break;
            }
        }

        return $blResult;
    }
}
