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
 * Country manager
 *
 */
class oxCountry extends oxI18n
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxcountry';

    /**
     * State list
     *
     * @var oxStateList
     */
    protected $_aStates = null;

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxcountry');
    }

    /**
     * returns true if this country is a foreign country
     *
     * @return bool
     */
    public function isForeignCountry()
    {
        return !in_array($this->getId(), $this->getConfig()->getConfigParam('aHomeCountry'));
    }

    /**
     * returns true if this country is marked as EU
     *
     * @return bool
     */
    public function isInEU()
    {
        return (bool) ($this->oxcountry__oxvatstatus->value == 1);
    }

    /**
     * Returns current state list
     *
     * @return array
     */
    public function getStates()
    {
        if (!is_null($this->_aStates)) {
            return $this->_aStates;
        }

        $sCountryId = $this->getId();
        $sViewName = getViewName("oxstates", $this->getLanguage());
        $sQ = "select * from {$sViewName} where `oxcountryid` = '$sCountryId' order by `oxtitle`  ";
        $this->_aStates = oxNew("oxlist");
        $this->_aStates->init("oxstate");
        $this->_aStates->selectString($sQ);

        return $this->_aStates;
    }

    /**
     * Returns country id by code
     *
     * @param string $sCode country code
     *
     * @return string
     */
    public function getIdByCode($sCode)
    {
        $oDb = oxDb::getDb();

        return $oDb->getOne("select oxid from oxcountry where oxisoalpha2 = " . $oDb->quote($sCode));
    }

    /**
     * Method returns VAT identification number prefix.
     *
     * @return string
     */
    public function getVATIdentificationNumberPrefix()
    {
        return $this->oxcountry__oxvatinprefix->value;
    }

}
