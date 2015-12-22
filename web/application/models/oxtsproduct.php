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
 * TRusted shops protection product manager.
 *
 */
class oxTsProduct extends oxSuperCfg
{

    /**
     * Id of TS protection product
     *
     * @var string
     */
    protected $_sTsId = null;

    /**
     * Amount of TS protection product
     *
     * @var integer
     */
    protected $_iAmount = null;

    /**
     * Price of TS protection product
     *
     * @deprecated in v4.8/5.1 on 2013-10-14; for formatting use oxPrice smarty plugin
     *
     * @var float
     */
    protected $_fPrice = null;

    /**
     * Price of TS protection netto product
     *
     * @deprecated in v4.8/5.1 on 2013-10-14; for formatting use oxPrice smarty plugin
     *
     * @var float
     */
    protected $_fNettoPrice = null;

    /**
     * Price of TS protection vat value
     *
     * @deprecated in v4.8/5.1 on 2013-10-14; for formatting use oxPrice smarty plugin
     *
     * @var float
     */
    protected $_fVatValue = null;

    /**
     * Price of TS protection product
     *
     * @var object
     */
    protected $_oPrice = null;


    /**
     * Price of TS protection vat
     *
     * @var object
     */
    protected $_dVat = null;

    /**
     * Buyer protection products
     *
     * @var array
     */
    protected $_sTsProtectProducts = array("TS080501_500_30_EUR"   => array("netto" => "0.82", "amount" => "500"),
                                           "TS080501_1500_30_EUR"  => array("netto" => "2.47", "amount" => "1500"),
                                           "TS080501_2500_30_EUR"  => array("netto" => "4.12", "amount" => "2500"),
                                           "TS080501_5000_30_EUR"  => array("netto" => "8.24", "amount" => "5000"),
                                           "TS080501_10000_30_EUR" => array("netto" => "16.47", "amount" => "10000"),
                                           "TS080501_20000_30_EUR" => array("netto" => "32.94", "amount" => "20000")
    );

    /**
     * Return protection vat
     *
     * @return float
     */
    public function getVat()
    {
        return $this->_dVat;
    }

    /**
     * set protection vat
     *
     * @param float $dVat - vat
     */
    public function setVat($dVat)
    {
        $this->_dVat = $dVat;
    }

    /**
     * Returns id of TS protection product
     *
     * @return string
     */
    public function getTsId()
    {
        return $this->_sTsId;
    }

    /**
     * Sets id of TS protection product
     *
     * @param string $sTsId TS product id
     */
    public function setTsId($sTsId)
    {
        $this->_sTsId = $sTsId;
    }

    /**
     * Returns amount of TS protection product
     *
     * @return integer
     */
    public function getAmount()
    {
        if ($this->_iAmount == null) {
            if ($sTsId = $this->getTsId()) {
                $aTsProducts = $this->getAllTsProducts();
                if ($aTsProducts[$sTsId] && is_array($aTsProducts[$sTsId])) {
                    $this->_iAmount = $aTsProducts[$sTsId]['amount'];
                }
            }
        }

        return $this->_iAmount;
    }

    /**
     * Returns formatted brutto price of TS protection product
     *
     * @deprecated in v4.8/5.1 on 2013-10-14; for formatting use oxPrice smarty plugin
     *
     * @return string
     */
    public function getFPrice()
    {
        if ($this->_fPrice == null) {
            if ($oPrice = $this->getPrice()) {
                $this->_fPrice = oxRegistry::getLang()->formatCurrency($oPrice->getBruttoPrice());
            }
        }

        return $this->_fPrice;
    }

    /**
     * Returns formatted brutto price of TS protection product
     *
     * @deprecated in v4.8/5.1 on 2013-10-14; for formatting use oxPrice smarty plugin
     *
     * @return string
     */
    public function getFNettoPrice()
    {
        if ($this->_fNettoPrice == null) {
            if ($oPrice = $this->getPrice()) {
                $this->_fNettoPrice = oxRegistry::getLang()->formatCurrency($oPrice->getNettoPrice());
            }
        }

        return $this->_fNettoPrice;
    }

    /**
     * Returns formatted brutto price of TS protection product
     *
     * @deprecated in v4.8/5.1 on 2013-10-14; for formatting use oxPrice smarty plugin
     *
     * @return string
     */
    public function getFVatValue()
    {
        if ($this->_fVatValue == null) {
            if ($oPrice = $this->getPrice()) {
                $this->_fVatValue = oxRegistry::getLang()->formatCurrency($oPrice->getVatValue());
            }
        }

        return $this->_fVatValue;
    }

    /**
     * Returns price of TS protection product
     *
     * @return oxPrice
     */
    public function getPrice()
    {
        if ($this->_oPrice == null) {
            if ($sTsId = $this->getTsId()) {
                $aTsProducts = $this->getAllTsProducts();
                if ($aTsProducts[$sTsId] && is_array($aTsProducts[$sTsId])) {
                    $dPrice = $aTsProducts[$sTsId]['netto'];
                    $oPrice = oxNew('oxPrice');
                    $oPrice->setNettoPriceMode();
                    $oPrice->setPrice($dPrice);
                    $oPrice->setVat($this->getVat());
                    $this->_oPrice = $oPrice;
                }
            }
        }

        return $this->_oPrice;
    }

    /**
     * Returns array of all TS protection products
     *
     * @return array
     */
    public function getAllTsProducts()
    {
        return $this->_sTsProtectProducts;
    }
}
