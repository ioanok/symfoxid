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
 * exception class covering voucher exceptions
 */
class oxVoucherException extends oxException
{

    /**
     * Voucher nr. involved in this exception
     *
     * @var string
     */
    private $_sVoucherNr;

    /**
     * Sets the voucher number as a string
     *
     * @param string $sVoucherNr voucher number
     */
    public function setVoucherNr($sVoucherNr)
    {
        $this->_sVoucherNr = ( string ) $sVoucherNr;
    }

    /**
     * get voucher nr. involved
     *
     * @return string
     */
    public function getVoucherNr()
    {
        return $this->_sVoucherNr;
    }

    /**
     * Get string dump
     * Overrides oxException::getString()
     *
     * @return string
     */
    public function getString()
    {
        return __CLASS__ . '-' . parent::getString() . " Faulty Voucher Nr --> " . $this->_sVoucherNr;
    }

    /**
     * Creates an array of field name => field value of the object.
     * To make a easy conversion of exceptions to error messages possible.
     * Should be extended when additional fields are used!
     * Overrides oxException::getValues().
     *
     * @return array
     */
    public function getValues()
    {
        $aRes = parent::getValues();
        $aRes['voucherNr'] = $this->getVoucherNr();

        return $aRes;
    }
}
