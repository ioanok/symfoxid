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
 * Admin vendor main screen.
 * Performs collection and updating (on user submit) main item information.
 */
class Vendor_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(),
     * and returns name of template file
     * "vendor_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oVendor = oxNew("oxvendor");
            $oVendor->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $oVendor->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oVendor->loadInLang(key($oOtherLang), $soxId);
            }
            $this->_aViewData["edit"] = $oVendor;

            // category tree
            $this->_createCategoryTree("artcattree");

            //Disable editing for derived articles
            if ($oVendor->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }

            // remove already created languages
            $aLang = array_diff(oxRegistry::getLang()->getLanguageNames(), $oOtherLang);
            if (count($aLang)) {
                $this->_aViewData["posslang"] = $aLang;
            }

            foreach ($oOtherLang as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }

        if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
            $oVendorMainAjax = oxNew('vendor_main_ajax');
            $this->_aViewData['oxajax'] = $oVendorMainAjax->getColumns();

            return "popups/vendor_main.tpl";
        }

        return "vendor_main.tpl";
    }

    /**
     * Saves selection list parameters changes.
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        if (!isset($aParams['oxvendor__oxactive'])) {
            $aParams['oxvendor__oxactive'] = 0;
        }

        // shopid
        $aParams['oxvendor__oxshopid'] = oxRegistry::getSession()->getVariable("actshop");

        $oVendor = oxNew("oxvendor");
        if ($soxId != "-1") {
            $oVendor->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxvendor__oxid'] = null;
        }


        $oVendor->setLanguage(0);
        $oVendor->assign($aParams);
        $oVendor->setLanguage($this->_iEditLang);
        $oVendor = oxRegistry::get("oxUtilsFile")->processFiles($oVendor);
        $oVendor->save();

        // set oxid if inserted
        $this->setEditObjectId($oVendor->getId());
    }

    /**
     * Saves selection list parameters changes in different language (eg. english).
     *
     * @return mixed
     */
    public function saveinnlang()
    {
        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        if (!isset($aParams['oxvendor__oxactive'])) {
            $aParams['oxvendor__oxactive'] = 0;
        }

        // shopid
        $aParams['oxvendor__oxshopid'] = oxRegistry::getSession()->getVariable("actshop");

        $oVendor = oxNew("oxvendor");

        if ($soxId != "-1") {
            $oVendor->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxvendor__oxid'] = null;
        }


        $oVendor->setLanguage(0);
        $oVendor->assign($aParams);
        $oVendor->setLanguage($this->_iEditLang);
        $oVendor = oxRegistry::get("oxUtilsFile")->processFiles($oVendor);
        $oVendor->save();

        // set oxid if inserted
        $this->setEditObjectId($oVendor->getId());
    }
}
