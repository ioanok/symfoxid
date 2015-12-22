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
 * Exception class for all adoDb problems, e.g.:
 * - connection problems
 * - wrong credentials
 * - incorrect queries
 */
class oxAdoDbException extends oxConnectionException
{
    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     *
     * @param string $sDbDriver   Database driver
     * @param string $sFunction   The name of the calling function (in uppercase)
     * @param int    $iErrorNr    The native error number from the database
     * @param string $sErrorMsg   the native error message from the database
     * @param string $sParam1     $sFunction specific parameter
     * @param string $sParam2     $sFunction specific parameter
     * @param string $oConnection Database connection object
     */
    public function __construct($sDbDriver, $sFunction, $iErrorNr, $sErrorMsg, $sParam1, $sParam2, $oConnection)
    {
        $sUser = $oConnection->username;
        $iErrorNr = is_numeric($iErrorNr) ? $iErrorNr : -1;

        $sMessage = "$sDbDriver error: [$iErrorNr: $sErrorMsg] in $sFunction ($sParam1, $sParam2) with user $sUser\n";

        parent::__construct($sMessage, $iErrorNr);
    }
}
