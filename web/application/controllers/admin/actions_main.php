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
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 */
class Actions_Main extends oxAdminDetails
{

    /**
     * Loads article actionss info, passes it to Smarty engine and
     * returns name of template file "actions_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // check if we right now saved a new entry
        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oAction = oxNew("oxactions");
            $oAction->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $oAction->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oAction->loadInLang(key($oOtherLang), $soxId);
            }

            $this->_aViewData["edit"] = $oAction;

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
            // generating category tree for select list
            $this->_createCategoryTree("artcattree", $soxId);

            $oActionsMainAjax = oxNew('actions_main_ajax');
            $this->_aViewData['oxajax'] = $oActionsMainAjax->getColumns();

            return "popups/actions_main.tpl";
        }


        if (($oPromotion = $this->getViewDataElement("edit"))) {
            if (($oPromotion->oxactions__oxtype->value == 2) || ($oPromotion->oxactions__oxtype->value == 3)) {
                if ($iAoc = oxRegistry::getConfig()->getRequestParameter("oxpromotionaoc")) {
                    $sPopup = false;
                    switch ($iAoc) {
                        case 'article':
                            // generating category tree for select list
                            $this->_createCategoryTree("artcattree", $soxId);

                            if ($oArticle = $oPromotion->getBannerArticle()) {
                                $this->_aViewData['actionarticle_artnum'] = $oArticle->oxarticles__oxartnum->value;
                                $this->_aViewData['actionarticle_title'] = $oArticle->oxarticles__oxtitle->value;
                            }

                            $sPopup = 'actions_article';
                            break;
                        case 'groups':
                            $sPopup = 'actions_groups';
                            break;
                    }

                    if ($sPopup) {
                        $aColumns = array();
                        $oActionsArticleAjax = oxNew($sPopup . '_ajax');
                        $this->_aViewData['oxajax'] = $oActionsArticleAjax->getColumns();

                        return "popups/{$sPopup}.tpl";
                    }
                } else {
                    if ($oPromotion->oxactions__oxtype->value == 2) {
                        $this->_aViewData["editor"] = $this->_generateTextEditor(
                            "100%",
                            300,
                            $oPromotion,
                            "oxactions__oxlongdesc",
                            "details.tpl.css"
                        );
                    }
                }
            }
        }

        return "actions_main.tpl";
    }


    /**
     * Saves Promotions
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig = $this->getConfig();


        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        $oPromotion = oxNew("oxactions");
        if ($soxId != "-1") {
            $oPromotion->load($soxId);
        } else {
            $aParams['oxactions__oxid'] = null;
        }

        if (!$aParams['oxactions__oxactive']) {
            $aParams['oxactions__oxactive'] = 0;
        }

        $oPromotion->setLanguage(0);
        $oPromotion->assign($aParams);
        $oPromotion->setLanguage($this->_iEditLang);
        $oPromotion = oxRegistry::get("oxUtilsFile")->processFiles($oPromotion);
        $oPromotion->save();

        // set oxid if inserted
        $this->setEditObjectId($oPromotion->getId());
    }

    /**
     * Saves changed selected action parameters in different language.
     */
    public function saveinnlang()
    {
        $this->save();
    }
}
