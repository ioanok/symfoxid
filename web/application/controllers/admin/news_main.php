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
 * Admin article main news manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Customer News -> News -> Main.
 */
class News_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxlist object and
     * collects user groups information, passes data to Smarty engine,
     * returns name of template file "news_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        // all usergroups
        $oGroups = oxNew("oxlist");
        $oGroups->init("oxgroups");
        $oGroups->selectString("select * from " . getViewName("oxgroups", $this->_iEditLang));

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oNews = oxNew("oxnews");
            $oNews->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $oNews->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oNews->loadInLang(key($oOtherLang), $soxId);
            }
            $this->_aViewData["edit"] = $oNews;


            // remove already created languages
            $this->_aViewData["posslang"] = array_diff(oxRegistry::getLang()->getLanguageNames(), $oOtherLang);

            foreach ($oOtherLang as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }
        if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
            $oNewsMainAjax = oxNew('news_main_ajax');
            $this->_aViewData['oxajax'] = $oNewsMainAjax->getColumns();

            return "popups/news_main.tpl";
        }

        return "news_main.tpl";
    }

    /**
     * Saves news parameters changes.
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        // checkbox handling
        if (!isset($aParams['oxnews__oxactive'])) {
            $aParams['oxnews__oxactive'] = 0;
        }

        // shopid
        $sShopID = oxRegistry::getSession()->getVariable("actshop");
        $aParams['oxnews__oxshopid'] = $sShopID;
        // creating fake object to save correct time value
        if (!$aParams['oxnews__oxdate']) {
            $aParams['oxnews__oxdate'] = "";
        }

        $oConvObject = new oxField();
        $oConvObject->fldmax_length = 0;
        $oConvObject->fldtype = "date";
        $oConvObject->value = $aParams['oxnews__oxdate'];
        $aParams['oxnews__oxdate'] = oxRegistry::get("oxUtilsDate")->convertDBDate($oConvObject, true);

        $oNews = oxNew("oxnews");

        if ($soxId != "-1") {
            $oNews->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxnews__oxid'] = null;
        }


        //$aParams = $oNews->ConvertNameArray2Idx( $aParams);

        $oNews->setLanguage(0);
        $oNews->assign($aParams);
        $oNews->setLanguage($this->_iEditLang);
        $oNews->save();

        // set oxid if inserted
        $this->setEditObjectId($oNews->getId());
    }

    /**
     * Saves news parameters in different language.
     *
     * @return null
     */
    public function saveinnlang()
    {
        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        // checkbox handling
        if (!isset($aParams['oxnews__oxactive'])) {
            $aParams['oxnews__oxactive'] = 0;
        }

        parent::save();

        // shopid
        $sShopID = oxRegistry::getSession()->getVariable("actshop");
        $aParams['oxnews__oxshopid'] = $sShopID;
        // creating fake object to save correct time value
        if (!$aParams['oxnews__oxdate']) {
            $aParams['oxnews__oxdate'] = "";
        }

        $oConvObject = new oxField();
        $oConvObject->fldmax_length = 0;
        $oConvObject->fldtype = "date";
        $oConvObject->value = $aParams['oxnews__oxdate'];
        $aParams['oxnews__oxdate'] = oxRegistry::get("oxUtilsDate")->convertDBDate($oConvObject, true);

        $oNews = oxNew("oxnews");

        if ($soxId != "-1") {
            $oNews->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxnews__oxid'] = null;
        }


        //$aParams = $oNews->ConvertNameArray2Idx( $aParams);
        $oNews->setLanguage(0);
        $oNews->assign($aParams);

        // apply new language
        $oNews->setLanguage(oxRegistry::getConfig()->getRequestParameter("new_lang"));
        $oNews->save();

        // set oxid if inserted
        $this->setEditObjectId($oNews->getId());
    }
}
