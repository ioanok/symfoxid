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
 * Admin article main usergroup manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: User Administration -> User Groups -> Main.
 */
class UserGroup_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxgroups object,
     * passes data to Smarty engine and returns name of template file
     * "usergroup_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oGroup = oxNew("oxgroups");
            $oGroup->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $oGroup->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oGroup->loadInLang(key($oOtherLang), $soxId);
            }

            $this->_aViewData["edit"] = $oGroup;

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
            $oUsergroupMainAjax = oxNew('usergroup_main_ajax');
            $this->_aViewData['oxajax'] = $oUsergroupMainAjax->getColumns();

            return "popups/usergroup_main.tpl";
        }

        return "usergroup_main.tpl";
    }

    /**
     * Saves changed usergroup parameters.
     *
     * @return mixed
     */
    public function save()
    {

        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        // checkbox handling
        if (!isset($aParams['oxgroups__oxactive'])) {
            $aParams['oxgroups__oxactive'] = 0;
        }

        $oGroup = oxNew("oxgroups");
        if ($soxId != "-1") {
            $oGroup->load($soxId);
        } else {
            $aParams['oxgroups__oxid'] = null;
        }

        $oGroup->setLanguage(0);
        $oGroup->assign($aParams);
        $oGroup->setLanguage($this->_iEditLang);
        $oGroup->save();

        // set oxid if inserted
        $this->setEditObjectId($oGroup->getId());
    }

    /**
     * Saves changed selected group parameters in different language.
     */
    public function saveinnlang()
    {
        $this->save();
    }
}
