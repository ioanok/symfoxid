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
 * Admin article main deliveryset manager.
 * There is possibility to change deliveryset name, article, user
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling -> Main Sets.
 */
class DeliverySet_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates deliveryset category tree,
     * passes data to Smarty engine and returns name of template file "deliveryset_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $odeliveryset = oxNew("oxdeliveryset");
            $odeliveryset->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $odeliveryset->getAvailableInLangs();

            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $odeliveryset->loadInLang(key($oOtherLang), $soxId);
            }

            $this->_aViewData["edit"] = $odeliveryset;


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
            $oDeliverysetMainAjax = oxNew('deliveryset_main_ajax');
            $this->_aViewData['oxajax'] = $oDeliverysetMainAjax->getColumns();

            return "popups/deliveryset_main.tpl";
        }

        return "deliveryset_main.tpl";
    }

    /**
     * Saves deliveryset information changes.
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // shopid
        $sShopID = oxRegistry::getSession()->getVariable("actshop");
        $aParams['oxdeliveryset__oxshopid'] = $sShopID;
        $oDelSet = oxNew("oxdeliveryset");

        if ($soxId != "-1") {
            $oDelSet->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxdeliveryset__oxid'] = null;
        }

        // checkbox handling
        if (!isset($aParams['oxdeliveryset__oxactive'])) {
            $aParams['oxdeliveryset__oxactive'] = 0;
        }


        //$aParams = $oDelSet->ConvertNameArray2Idx( $aParams);
        $oDelSet->setLanguage(0);
        $oDelSet->assign($aParams);
        $oDelSet->setLanguage($this->_iEditLang);
        $oDelSet = oxRegistry::get("oxUtilsFile")->processFiles($oDelSet);
        $oDelSet->save();

        // set oxid if inserted
        $this->setEditObjectId($oDelSet->getId());
    }

    /**
     * Saves deliveryset data to different language (eg. english).
     *
     * @return null
     */
    public function saveinnlang()
    {
        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        // checkbox handling
        if (!isset($aParams['oxdeliveryset__oxactive'])) {
            $aParams['oxdeliveryset__oxactive'] = 0;
        }

        // shopid
        $sShopID = oxRegistry::getSession()->getVariable("actshop");
        $aParams['oxdeliveryset__oxshopid'] = $sShopID;
        $oDelSet = oxNew("oxdeliveryset");

        if ($soxId != "-1") {
            $oDelSet->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxdeliveryset__oxid'] = null;
            //$aParams = $oDelSet->ConvertNameArray2Idx( $aParams);
        }

        $oDelSet->setLanguage(0);
        $oDelSet->assign($aParams);


        // apply new language
        $oDelSet->setLanguage(oxRegistry::getConfig()->getRequestParameter("new_lang"));
        $oDelSet->save();

        // set oxid if inserted
        $this->setEditObjectId($oDelSet->getId());
    }
}
