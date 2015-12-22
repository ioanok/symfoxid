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
 * Class OxpsPaymorrowPrepareOrder
 */
class OxpsPaymorrowPrepareOrder extends oxUBase
{

    /**
     * Paymorrow function for Verifying Form Data against Paymorrow Services
     */
    public function prepareOrder()
    {
        /** @var OxpsPaymorrowRequestControllerProxy $oPmGateWay */
        $oPmGateWay = oxNew( 'OxpsPaymorrowRequestControllerProxy' );

        $oUtils = oxRegistry::getUtils();

        $oUtils->setHeader( "Content-Type: application/json" );
        $oUtils->showMessageAndExit( $oPmGateWay->prepareOrder( $_POST ) );
    }
}
