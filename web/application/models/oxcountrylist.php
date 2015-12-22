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
 * Country list manager class.
 * Collects a list of countries according to collection rules (active).
 *
 */
class oxCountryList extends oxList
{

    /**
     * Call parent class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct('oxcountry');
    }

    /**
     * Selects and loads all active countries
     *
     * @param integer $iLang language
     */
    public function loadActiveCountries($iLang = null)
    {
        $sViewName = getViewName('oxcountry', $iLang);
        $sSelect = "SELECT oxid, oxtitle, oxisoalpha2 FROM {$sViewName} WHERE oxactive = '1' ORDER BY oxorder, oxtitle ";
        $this->selectString($sSelect);
    }
}
