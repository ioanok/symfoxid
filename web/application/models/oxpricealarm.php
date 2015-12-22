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
 * Pricealarm manager.
 * Performs Pricealarm data/objects loading, deleting.
 *
 */
class oxPricealarm extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxpricealarm';

    /**
     * Article object
     *
     * @var object
     */
    protected $_oArticle = null;

    /**
     * Formatted original article price
     *
     * @var string
     */
    protected $_fPrice = null;

    /**
     * Original article price
     *
     * @var double
     */
    protected $_dPrice = null;

    /**
     * Full article title
     *
     * @var string
     */
    protected $_sTitle = null;

    /**
     * Currency object
     *
     * @var object
     */
    protected $_oCurrency = null;

    /**
     * Customer proposed price
     *
     * @var string
     */
    protected $_fProposedPrice = null;

    /**
     * Pricealarm status
     *
     * @var int
     */
    protected $_iStatus = null;

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()), loads
     * base shop objects.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxpricealarm');
    }

    /**
     * Inserts object data into DB, returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {
        // set oxinsert value
        $this->oxpricealarm__oxinsert = new oxField(date('Y-m-d', oxRegistry::get("oxUtilsDate")->getTime()));

        return parent::_insert();
    }

    /**
     * Loads pricealarm article
     *
     * @return object
     */
    public function getArticle()
    {
        if ($this->_oArticle == null) {
            $this->_oArticle = false;
            $oArticle = oxNew("oxarticle");
            if ($oArticle->load($this->oxpricealarm__oxartid->value)) {
                $this->_oArticle = $oArticle;
            }
        }

        return $this->_oArticle;
    }

    /**
     * Returns formatted pricealarm article original price
     *
     * @return string
     */
    public function getFPrice()
    {
        if ($this->_fPrice == null) {
            $this->_fPrice = false;
            if ($dArtPrice = $this->getPrice()) {
                $myLang = oxRegistry::getLang();
                $oThisCurr = $this->getPriceAlarmCurrency();
                $this->_fPrice = $myLang->formatCurrency($dArtPrice, $oThisCurr);
            }
        }

        return $this->_fPrice;
    }

    /**
     * Returns pricealarm article original price
     *
     * @return double
     */
    public function getPrice()
    {
        if ($this->_dPrice == null) {
            $this->_dPrice = false;
            if ($oArticle = $this->getArticle()) {
                $myUtils = oxRegistry::getUtils();
                $oThisCurr = $this->getPriceAlarmCurrency();

                // #889C - Netto prices in Admin
                // (we have to call $oArticle->getPrice() to get price with VAT)
                $dArtPrice = $oArticle->getPrice()->getBruttoPrice() * $oThisCurr->rate;
                $dArtPrice = $myUtils->fRound($dArtPrice);

                $this->_dPrice = $dArtPrice;
            }
        }

        return $this->_dPrice;
    }

    /**
     * Returns pricealarm article full title
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->_sTitle == null) {
            $this->_sTitle = false;
            if ($oArticle = $this->getArticle()) {
                $this->_sTitle = $oArticle->oxarticles__oxtitle->value;
                if ($oArticle->oxarticles__oxparentid->value && !$oArticle->oxarticles__oxtitle->value) {
                    $oParent = oxNew("oxarticle");
                    $oParent->load($oArticle->oxarticles__oxparentid->value);
                    $this->_sTitle = $oParent->oxarticles__oxtitle->value . " " . $oArticle->oxarticles__oxvarselect->value;
                }
            }
        }

        return $this->_sTitle;
    }

    /**
     * Returns pricealarm currency object
     *
     * @return object
     */
    public function getPriceAlarmCurrency()
    {
        if ($this->_oCurrency == null) {
            $this->_oCurrency = false;
            $myConfig = $this->getConfig();
            $oThisCurr = $myConfig->getCurrencyObject($this->oxpricealarm__oxcurrency->value);

            // #869A we should perform currency conversion
            // (older versions doesn't have currency info - assume as it is default - first in currency array)
            if (!$oThisCurr) {
                $oDefCurr = $myConfig->getActShopCurrencyObject();
                $oThisCurr = $myConfig->getCurrencyObject($oDefCurr->name);
                $this->oxpricealarm__oxcurrency->setValue($oDefCurr->name);
            }
            $this->_oCurrency = $oThisCurr;
        }

        return $this->_oCurrency;
    }

    /**
     * Returns formatted proposed price
     *
     * @return string
     */
    public function getFProposedPrice()
    {
        if ($this->_fProposedPrice == null) {
            $this->_fProposedPrice = false;
            if ($oThisCurr = $this->getPriceAlarmCurrency()) {
                $myLang = oxRegistry::getLang();
                $this->_fProposedPrice = $myLang->formatCurrency($this->oxpricealarm__oxprice->value, $oThisCurr);
            }
        }

        return $this->_fProposedPrice;
    }

    /**
     * Returns pricealarm status
     *
     * @return integer
     */
    public function getPriceAlarmStatus()
    {
        if ($this->_iStatus == null) {
            // neutral status
            $this->_iStatus = 0;

            // shop price is less or equal
            $dArtPrice = $this->getPrice();
            if ($this->oxpricealarm__oxprice->value >= $dArtPrice) {
                $this->_iStatus = 1;
            }

            // suggestion to user is sent
            if ($this->oxpricealarm__oxsended->value != "0000-00-00 00:00:00") {
                $this->_iStatus = 2;
            }
        }

        return $this->_iStatus;
    }
}