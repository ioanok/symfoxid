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
 * Class OxpsPaymorrowOxUserPayment extend oxUserPayment
 *
 * @see oxUserPayment
 */
class OxpsPaymorrowOxUserPayment extends OxpsPaymorrowOxUserPayment_parent
{

    /**
     * Set Paymorrow bank name.
     *
     * @param $sBankName
     */
    public function setPaymorrowBankName( $sBankName )
    {
        $this->oxuserpayments__oxpspaymorrowbankname = new oxField( $sBankName );
    }

    /**
     * Get Paymorrow bank name.
     *
     * @return string
     */
    public function getPaymorrowBankName()
    {
        return $this->oxuserpayments__oxpspaymorrowbankname->value;
    }

    /**
     * Set Paymorrow transaction IBAN code.
     *
     * @param string $sIBAN
     */
    public function setPaymorrowIBAN( $sIBAN )
    {
        $this->oxuserpayments__oxpspaymorrowiban = new oxField( $sIBAN );
    }

    /**
     * Get Paymorrow transaction IBAN code.
     *
     * @return string
     */
    public function getPaymorrowIBAN()
    {
        return $this->oxuserpayments__oxpspaymorrowiban->value;
    }

    /**
     * Set Paymorrow transaction BIC code.
     *
     * @param string $sBIC
     */
    public function setPaymorrowBIC( $sBIC )
    {
        $this->oxuserpayments__oxpspaymorrowbic = new oxField( $sBIC );
    }

    /**
     * Get Paymorrow transaction BIC code.
     *
     * @return string
     */
    public function getPaymorrowBIC()
    {
        return $this->oxuserpayments__oxpspaymorrowbic->value;
    }

    /**
     * Set Paymorrow order ID received after `confirmOrder`.
     *
     * @param string $sId
     */
    public function setPaymorrowOrderId( $sId )
    {
        $this->oxuserpayments__oxpspaymorroworderid = new oxField( $sId );
    }

    /**
     * Get Paymorrow order ID received after `confirmOrder`.
     *
     * @return string
     */
    public function getPaymorrowOrderId()
    {
        return $this->oxuserpayments__oxpspaymorroworderid->value;
    }


    /**
     * Overridden `load` function - loads additional Paymorrow fields in oxUserPayments table.
     * Loads user payment object.
     *
     * @param string $sOxId
     *
     * @return mixed
     */
    public function load( $sOxId )
    {
        $sSQL = sprintf(
            'SELECT `OXID`, `OXUSERID`, `OXPAYMENTSID`, DECODE( `OXVALUE`, "%s" ) AS `OXVALUE`,
              `OXPSPAYMORROWBANKNAME`, `OXPSPAYMORROWIBAN`, `OXPSPAYMORROWBIC`, `OXPSPAYMORROWORDERID`
              FROM `oxuserpayments` WHERE `OXID` = %s',
            $this->getPaymentKey(),
            oxDb::getDb()->quote( $sOxId )
        );

        return $this->assignRecord( $sSQL );
    }

    /**
     * Check if user payment method is mapped to a Paymorrow payment method.
     *
     * @return bool
     */
    public function isUserPaymentPaymorrowMethod()
    {
        $sPaymentId = $this->oxuserpayments__oxpaymentsid->value;

        /** @var OxpsPaymorrowOxPayment|oxPayment $oxPayment */
        $oxPayment = oxNew( 'oxPayment' );
        $oxPayment->load( $sPaymentId );

        return $oxPayment->isPaymorrowActiveAndMapped();
    }

    /**
     * Get related payment method.
     * Used in custom paymorrow email templates.
     *
     * @return oxPayment
     */
    public function getPaymorrowOxPayment()
    {
        $sPaymentId = $this->oxuserpayments__oxpaymentsid->value;

        $oPayment = oxNew( 'oxPayment' );
        $oPayment->load( $sPaymentId );

        return $oPayment;
    }
}
