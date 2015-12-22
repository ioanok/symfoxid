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
 * Admin Menu: Customer News -> News -> Text.
 */
class News_Text extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxnews object and
     * passes news text to smarty. Returns name of template file "news_text.tpl".
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
            $oNews = oxNew("oxnews");
            $iNewsLang = oxRegistry::getConfig()->getRequestParameter("newslang");

            if (!isset($iNewsLang)) {
                $iNewsLang = $this->_iEditLang;
            }

            $this->_aViewData["newslang"] = $iNewsLang;
            $oNews->loadInLang($iNewsLang, $soxId);

            foreach (oxRegistry::getLang()->getLanguageNames() as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }


            $this->_aViewData["edit"] = $oNews;


        }
        $this->_aViewData["editor"] = $this->_generateTextEditor("100%", 255, $oNews, "oxnews__oxlongdesc", "news.tpl.css");

        return "news_text.tpl";
    }

    /**
     * Saves news text.
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        $myConfig = $this->getConfig();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        $oNews = oxNew("oxnews");

        $iNewsLang = oxRegistry::getConfig()->getRequestParameter("newslang");

        if (!isset($iNewsLang)) {
            $iNewsLang = $this->_iEditLang;
        }

        if ($soxId != "-1") {
            $oNews->loadInLang($iNewsLang, $soxId);
        } else {
            $aParams['oxnews__oxid'] = null;
        }


        //$aParams = $oNews->ConvertNameArray2Idx( $aParams);

        $oNews->setLanguage(0);
        $oNews->assign($aParams);
        $oNews->setLanguage($iNewsLang);

        $oNews->save();
        // set oxid if inserted
        $this->setEditObjectId($oNews->getId());
    }
}
