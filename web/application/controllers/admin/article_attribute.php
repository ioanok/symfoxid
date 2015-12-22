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
 * Admin article attributes/selections lists manager.
 * Collects available attributes/selections lists for chosen article, may add
 * or remove any of them to article, etc.
 * Admin Menu: Manage Products -> Articles -> Selection.
 */
class Article_Attribute extends oxAdminDetails
{

    /**
     * Collects article attributes and selection lists, passes them to Smarty engine,
     * returns name of template file "article_attribute.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['edit'] = $oArticle = oxNew('oxarticle');

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oArticle->load($soxId);

            if ($oArticle->isDerived()) {
                $this->_aViewData["readonly"] = true;
            }
        }

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oArticleAttributeAjax = oxNew('article_attribute_ajax');
            $this->_aViewData['oxajax'] = $oArticleAttributeAjax->getColumns();

            return "popups/article_attribute.tpl";
        } elseif ($iAoc == 2) {
            $oArticleSelectionAjax = oxNew('article_selection_ajax');
            $this->_aViewData['oxajax'] = $oArticleSelectionAjax->getColumns();

            return "popups/article_selection.tpl";
        }

        return "article_attribute.tpl";
    }
}
