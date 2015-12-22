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
 * Exception class for PayPal returned exceptions
 */
class oePayPalResponseException extends oePayPalException
{
    /**
     * Exception constructor. Adds additional prefix string to error message.
     *
     * @param string  $sMessage exception message
     * @param integer $iCode    exception code
     */
    public function __construct($sMessage = "", $iCode = 0)
    {
        $sPrefix = oxRegistry::getLang()->translateString("OEPAYPAL_RESPONSE_FROM_PAYPAL");

        parent::__construct($sPrefix . $sMessage, $iCode);
    }
}
