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
 * PayPal order payment comment class
 */
class oePayPalOrderPaymentComment extends oePayPalModel
{

    /**
     * Sets date value.
     */
    public function __construct()
    {
        $this->_setValue('oepaypal_date', date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime()));
    }

    /**
     * Sets comment id.
     *
     * @param string $sCommentId
     */
    public function setId($sCommentId)
    {
        $this->setCommentId($sCommentId);
    }

    /**
     * Returns comment id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getCommentId();
    }

    /**
     * Set PayPal order comment Id
     *
     * @param string $sCommentId
     */
    public function setCommentId($sCommentId)
    {
        $this->_setValue('oepaypal_commentid', $sCommentId);
    }

    /**
     * Set PayPal comment Id
     *
     * @return string
     */
    public function getCommentId()
    {
        return $this->_getValue('oepaypal_commentid');
    }

    /**
     * Set PayPal order payment Id
     *
     * @param string $sPaymentId
     */
    public function setPaymentId($sPaymentId)
    {
        $this->_setValue('oepaypal_paymentid', $sPaymentId);
    }

    /**
     * Set PayPal order payment Id
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->_getValue('oepaypal_paymentid');
    }

    /**
     * Set date
     *
     * @param string $sDate
     */
    public function setDate($sDate)
    {
        $this->_setValue('oepaypal_date', $sDate);
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->_getValue('oepaypal_date');
    }

    /**
     * Set comment
     *
     * @param string $sComment
     */
    public function setComment($sComment)
    {
        $this->_setValue('oepaypal_comment', $sComment);
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->_getValue('oepaypal_comment');
    }

    /**
     * Return database gateway
     *
     * @return oePayPalOrderPaymentCommentDbGateway
     */
    protected function _getDbGateway()
    {
        if (is_null($this->_oDbGateway)) {
            $this->_setDbGateway(oxNew('oePayPalOrderPaymentCommentDbGateway'));
        }

        return $this->_oDbGateway;
    }
}
