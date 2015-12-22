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
 * PayPal Current item Article container class.
 */
class oePayPalArticleToExpressCheckoutCurrentItem
{
    /**
     * Article id
     *
     * @var string
     */
    protected $_sArticleId;

    /**
     * Select list
     *
     * @var array
     */
    protected $_aSelectList;

    /**
     * Persistent param
     *
     * @var array
     */
    protected $_aPersistParam;

    /**
     * Article amount
     *
     * @var integer
     */
    protected $_iArticleAmount;

    /**
     * Method sets persistent param.
     *
     * @param array $aPersistParam
     */
    public function setPersistParam($aPersistParam)
    {
        $this->_aPersistParam = $aPersistParam;
    }

    /**
     * Method returns persistent param.
     *
     * @return array
     */
    public function getPersistParam()
    {
        return $this->_aPersistParam;
    }

    /**
     * Method sets select list.
     *
     * @param array $aSelectList
     */
    public function setSelectList($aSelectList)
    {
        $this->_aSelectList = $aSelectList;
    }

    /**
     * Method returns select list.
     *
     * @return array
     */
    public function getSelectList()
    {
        return $this->_aSelectList;
    }

    /**
     * Method sets article id.
     *
     * @param string $sArticleId
     */
    public function setArticleId($sArticleId)
    {
        $this->_sArticleId = $sArticleId;
    }

    /**
     * Method returns article id.
     *
     * @return string
     */
    public function getArticleId()
    {
        return $this->_sArticleId;
    }

    /**
     * Method sets article amount.
     *
     * @param int $iArticleAmount
     */
    public function setArticleAmount($iArticleAmount)
    {
        $this->_iArticleAmount = $iArticleAmount;
    }

    /**
     * Method returns article amount.
     *
     * @return int
     */
    public function getArticleAmount()
    {
        return ( int ) $this->_iArticleAmount;
    }
}
