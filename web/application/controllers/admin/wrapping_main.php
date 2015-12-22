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
 * Admin wrapping main manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: System Administration -> Wrapping -> Main.
 */
class Wrapping_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxwrapping, oxshops and oxlist
     * objects, passes data to Smarty engine and returns name of template
     * file "wrapping_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oWrapping = oxNew("oxwrapping");
            $oWrapping->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $oWrapping->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oWrapping->loadInLang(key($oOtherLang), $soxId);
            }
            $this->_aViewData["edit"] = $oWrapping;


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

        return "wrapping_main.tpl";
    }

    /**
     * Saves main wrapping parameters.
     *
     * @return null
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // checkbox handling
        if (!isset($aParams['oxwrapping__oxactive'])) {
            $aParams['oxwrapping__oxactive'] = 0;
        }

        // shopid
        $aParams['oxwrapping__oxshopid'] = oxRegistry::getSession()->getVariable("actshop");

        $oWrapping = oxNew("oxwrapping");

        if ($soxId != "-1") {
            $oWrapping->loadInLang($this->_iEditLang, $soxId);
            // #1173M - not all pic are deleted, after article is removed
            oxRegistry::get("oxUtilsPic")->overwritePic($oWrapping, 'oxwrapping', 'oxpic', 'WP', '0', $aParams, $this->getConfig()->getPictureDir(false));
        } else {
            $aParams['oxwrapping__oxid'] = null;
            //$aParams = $oWrapping->ConvertNameArray2Idx( $aParams);
        }


        $oWrapping->setLanguage(0);
        $oWrapping->assign($aParams);
        $oWrapping->setLanguage($this->_iEditLang);

        $oWrapping = oxRegistry::get("oxUtilsFile")->processFiles($oWrapping);
        $oWrapping->save();

        // set oxid if inserted
        $this->setEditObjectId($oWrapping->getId());
    }

    /**
     * Saves main wrapping parameters.
     *
     * @return null
     */
    public function saveinnlang()
    {
        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // checkbox handling
        if (!isset($aParams['oxwrapping__oxactive'])) {
            $aParams['oxwrapping__oxactive'] = 0;
        }

        // shopid
        $aParams['oxwrapping__oxshopid'] = oxRegistry::getSession()->getVariable("actshop");

        $oWrapping = oxNew("oxwrapping");

        if ($soxId != "-1") {
            $oWrapping->load($soxId);
        } else {
            $aParams['oxwrapping__oxid'] = null;
            //$aParams = $oWrapping->ConvertNameArray2Idx( $aParams);
        }


        $oWrapping->setLanguage(0);
        $oWrapping->assign($aParams);
        $oWrapping->setLanguage($this->_iEditLang);

        $oWrapping = oxRegistry::get("oxUtilsFile")->processFiles($oWrapping);
        $oWrapping->save();

        // set oxid if inserted
        $this->setEditObjectId($oWrapping->getId());
    }
}
