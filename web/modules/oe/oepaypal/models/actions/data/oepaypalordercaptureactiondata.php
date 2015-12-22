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
 * PayPal order action factory class
 */
class oePayPalOrderCaptureActionData extends oePayPalOrderActionData
{

    /**
     * returns action type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getRequest()->getRequestParameter('capture_type');
    }

    /**
     * returns action amount
     *
     * @return string
     */
    public function getAmount()
    {
        $dAmount = $this->getRequest()->getRequestParameter('capture_amount');

        return $dAmount ? $dAmount : $this->getOrder()->getPayPalOrder()->getRemainingOrderSum();
    }

    /**
     * returns currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getOrder()->getPayPalOrder()->getCurrency();
    }
}
