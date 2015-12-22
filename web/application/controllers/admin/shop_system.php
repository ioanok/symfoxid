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
 * Admin shop system setting manager.
 * Collects shop system settings, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> System.
 */
class Shop_System extends Shop_Config
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'shop_system.tpl';

    /**
     * Executes parent method parent::render(), passes shop configuration parameters
     * to Smarty and returns name of template file "shop_system.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $aConfArrs = array();

        $oLang = oxRegistry::getLang();

        $aLanguages = $oLang->getLanguageArray();
        $sLangAbbr = $aLanguages[$oLang->getObjectTplLanguage()]->abbr;

        // loading shop location countries list (defines in which country shop exists)
        include "shop_countries.php";

        $soxId = $this->getEditObjectId();
        if (!$soxId) {
            $soxId = $myConfig->getShopId();
        }

        $oDb = oxDb::getDb();
        $sShopCountry = $oDb->getOne("select DECODE( oxvarvalue, " . $oDb->quote($myConfig->getConfigParam('sConfigKey')) . ") as oxvarvalue from oxconfig where oxshopid = '$soxId' and oxvarname = 'sShopCountry'", false, false);

        $this->_aViewData["shop_countries"] = $aLocationCountries[$sLangAbbr];
        $this->_aViewData["confstrs"]["sShopCountry"] = $sShopCountry;

        return $this->_sThisTemplate;
    }
}
