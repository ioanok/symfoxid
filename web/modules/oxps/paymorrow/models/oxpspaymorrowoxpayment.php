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
 * Class OxpsPaymorrowOxPayment extends oxPayment
 *
 * @see oxPayment
 */
class OxpsPaymorrowOxPayment extends OxpsPaymorrowOxPayment_parent
{

    /**
     * @var array Paymorrow payment method type options.
     */
    private $_aPaymorrowValidPaymentTypes = array(
        0 => 'pm_off',
        1 => 'pm_invoice',
        2 => 'pm_sdd', // Direct Debit
    );


    /**
     * Check if current payment method is mapped to Paymorrow payment method.
     *
     * @return bool
     */
    public function isPaymentMappedToPaymorrowMethod()
    {
        $iPaymorrowMap = $this->getPaymorrowPaymentMap();

        return ( ( $iPaymorrowMap == 1 ) OR ( $iPaymorrowMap == 2 ) );
    }

    /**
     * Check if current payment method, is active and mapped as Paymorrow payment method.
     * Also checks if merchant ID is configured.
     *
     * @return bool
     */
    public function isPaymorrowActiveAndMapped()
    {
        return ( $this->isPaymorrowActive() and
                 $this->isPaymentMappedToPaymorrowMethod() and
                 oxRegistry::get( 'OxpsPaymorrowSettings' )->getMerchantId() );
    }

    /**
     * Check if current payment method is active as Paymorrow payment method.
     *
     * @return bool
     */
    public function isPaymorrowActive()
    {
        return (bool) $this->getPaymorrowActive();
    }

    /**
     * Set current payment method as disabled or active as Paymorrow payment method.
     *
     * @param integer $iActive - 0/1 | Disabled/Active
     */
    public function setPaymorrowActive( $iActive )
    {
        $this->oxpayments__oxpspaymorrowactive = new oxField( $iActive );
    }

    /**
     * Get payment method disabled or active status for being set as Paymorrow payment method.
     *
     * @return integer
     */
    public function getPaymorrowActive()
    {
        return $this->oxpayments__oxpspaymorrowactive->value;
    }

    /**
     * Get payment method type code as string for template injection.
     *
     * @return string 'pm_invoice' - Invoice, 'pm_sdd' - Direct Debit
     */
    public function getPaymorrowPaymentType()
    {
        $iType = $this->getPaymorrowPaymentMap();

        return $this->_aPaymorrowValidPaymentTypes[$iType];
    }

    /**
     * Get Paymorrow payment method ID.
     *
     * @return integer 1 - Invoice, 2 - Direct Debit
     */
    public function getPaymorrowPaymentMap()
    {
        return $this->oxpayments__oxpspaymorrowmap->value;
    }

    /**
     * Set Paymorrow payment method ID.
     *
     * 0 - Off / none
     * 1 - Paymorrow Invoice
     * 2 - Paymorrow Direct Debit
     */
    public function setPaymorrowPaymentMap( $iType )
    {
        $blValidMapping = array_key_exists( $iType, $this->_aPaymorrowValidPaymentTypes );

        if ( $blValidMapping ) {
            $this->oxpayments__oxpspaymorrowmap = new oxField( $iType );
        }

        return $blValidMapping;
    }

    /**
     * Get payment method name.
     *
     * @return string
     */
    public function getTitle()
    {
        return (string) $this->oxpayments__oxdesc->value;
    }

    /**
     * Overridden parent method.
     * Ignores default payment method form error in case method is converted to a Paymorrow payment method.
     *
     * @param $aDynValue
     * @param $sShopId
     * @param $oUser
     * @param $dBasketPrice
     * @param $sShipSetId
     *
     * @return bool
     */
    public function isValidPayment( $aDynValue, $sShopId, $oUser, $dBasketPrice, $sShipSetId )
    {
        /** @var OxpsPaymorrowOxPayment|oxPayment $this */

        $blIsValid = (bool) $this->_OxpsPaymorrowOxPayment_isValidPayment_parent(
            $aDynValue, $sShopId, $oUser, $dBasketPrice, $sShipSetId
        );

        $iErrorCode = (int) $this->getPaymentErrorNumber();

        // In case it is Paymorrow payment method unset error code and tell than validation passed.
        if ( $this->isPaymorrowActive() and !$blIsValid and ( $iErrorCode === 1 ) ) {
            $this->_iPaymentError = null;

            return true;
        }

        return $blIsValid;
    }

    /**
     * Load active, mapped and selected Paymorrow payment method.
     * If several are selected loads it by sorting and last updated.
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function loadPaymorrowDefault()
    {
        /** @var OxpsPaymorrowOxPayment|oxPayment $this */

        $sQuery = sprintf(
            "SELECT * FROM `%s`
              WHERE `OXACTIVE` = 1 AND `OXCHECKED` = 1 AND `OXPSPAYMORROWACTIVE` = 1 AND `OXPSPAYMORROWMAP` > 0
              ORDER BY `OXSORT` ASC, `OXTIMESTAMP` DESC
              LIMIT 1",
            getViewName( 'oxpayments' )
        );

        return $this->assignRecord( $sQuery );
    }


    /**
     * Parent `isValidPayment` call.
     *
     * @codeCoverageIgnore
     *
     * @param $aDynValue
     * @param $sShopId
     * @param $oUser
     * @param $dBasketPrice
     * @param $sShipSetId
     *
     * @return mixed
     */
    protected function _OxpsPaymorrowOxPayment_isValidPayment_parent( $aDynValue, $sShopId, $oUser, $dBasketPrice,
                                                                      $sShipSetId )
    {
        return parent::isValidPayment( $aDynValue, $sShopId, $oUser, $dBasketPrice, $sShipSetId );
    }
}
