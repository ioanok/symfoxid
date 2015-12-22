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
 * PayPal order payment status list class
 */
class oePayPalOrderPaymentStatusList extends oePayPalList
{
    /**
     * All available statuses
     *
     * @return array
     */
    protected $_aArray = array(
        'completed',
        'pending',
        'canceled',
        'failed'
    );

    /**
     * Available statuses depending on action
     *
     * @var array
     */
    protected $_aAvailableStatuses = array(
        'capture'         => array('completed'),
        'capture_partial' => array('completed', 'pending'),
        'refund'          => array('completed', 'pending', 'canceled'),
        'refund_partial'  => array('completed', 'pending', 'canceled'),
        'void'            => array('completed', 'pending', 'canceled'),
    );

    /**
     * Returns the list of available statuses to choose from for admin
     *
     * @param string $sAction
     *
     * @return array
     */
    public function getAvailableStatuses($sAction)
    {
        $aList = $this->_aAvailableStatuses[$sAction];

        return $aList ? $aList : array();
    }
}
