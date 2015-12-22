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
 * Company VAT identification number (VATIN) checker
 *
 */
abstract class oxCompanyVatInChecker
{

    /**
     * Error message
     *
     * @var string
     */
    protected $_sError = '';

    /**
     * Error message setter
     *
     * @param string $sError
     */
    public function setError($sError)
    {
        $this->_sError = $sError;
    }

    /**
     * Error message getter
     *
     * @return string
     */
    public function getError()
    {
        return $this->_sError;
    }

    /**
     * Validates company VAT identification number
     *
     * @param oxCompanyVatIn $oVatIn
     *
     * @return mixed
     */
    abstract public function validate(oxCompanyVatIn $oVatIn);
}
