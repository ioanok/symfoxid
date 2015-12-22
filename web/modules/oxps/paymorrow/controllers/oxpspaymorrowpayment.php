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
 * Class OxpsPaymorrowPayment extends Payment
 *
 * @see Payment
 */
class OxpsPaymorrowPayment extends OxpsPaymorrowPayment_parent
{

    /**
     * Overridden parent method.
     * Additionally checks if Paymorrow request was successful.
     * It is needed for detecting cases, when JavaScript is disabled in user browser and no requests were sent.
     *
     * @return mixed
     */
    public function validatePayment()
    {
        // Remove payment methods initialization data from session of any is set
        $this->_unsetSessionInitData();

        $mReturn = $this->_OxpsPaymorrowPayment_validatePayment_parent();

        if ( $this->_isPaymorrowPayment( oxRegistry::getConfig()->getRequestParameter( 'paymentid' ) ) and
             $this->_isPaymentResponseSessionInvalid()
        ) {
            oxRegistry::getSession()->setVariable( 'payerror', 1 );

            return null;
        }

        return $mReturn;
    }


    /**
     * Delete session key with Paymorrow init data.
     * It is set on order confirmation errors, used to redirect user to the payment spe and passed to forms init.
     *
     * @codeCoverageIgnore
     */
    protected function _unsetSessionInitData()
    {
        oxRegistry::getSession()->deleteVariable( 'pm_init_data' );
    }

    /**
     * Load payment method by ID and check if it is mapped as active Paymorrow method.
     *
     * @param string $iId
     *
     * @return bool
     */
    protected function _isPaymorrowPayment( $iId )
    {
        /** @var OxpsPaymorrowOxPayment|oxPayment $oPayment */
        $oPayment = oxNew( 'oxPayment' );

        // Load selected payment method and check if it is Paymorrow
        return ( $oPayment->load( $iId ) and $oPayment->isPaymorrowActiveAndMapped() );
    }

    /**
     * Check payment response in session fot errors.
     *
     * @return bool True is response is invalid, false otherwise.
     */
    protected function _isPaymentResponseSessionInvalid()
    {
        // Get Paymorrow response from session
        $oSession           = oxRegistry::getSession();
        $aPaymorrowResponse = (array) $oSession->getVariable( 'pm_response' );

        // The response must exist and be valid
        return (
            !isset( $aPaymorrowResponse['order_status'], $aPaymorrowResponse['response_status'] ) or
            !in_array( $aPaymorrowResponse['order_status'], array('VALIDATED', 'ACCEPTED') ) or
            ( $aPaymorrowResponse['response_status'] !== 'OK' )
        );
    }


    /**
     * Parent `validatePayment` call.
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    protected function _OxpsPaymorrowPayment_validatePayment_parent()
    {
        return parent::validatePayment();
    }
}
