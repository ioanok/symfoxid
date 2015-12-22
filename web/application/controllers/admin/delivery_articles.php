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
 * Admin article main delivery manager.
 * There is possibility to change delivery name, article, user
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling -> Main.
 */
class Delivery_Articles extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates delivery category tree,
     * passes data to Smarty engine and returns name of template file "delivery_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();

        if ($soxId != "-1" && isset($soxId)) {
            $this->_createCategoryTree("artcattree");

            // load object
            $oDelivery = oxNew("oxdelivery");
            $oDelivery->load($soxId);
            $this->_aViewData["edit"] = $oDelivery;

            //Disable editing for derived articles
            if ($oDelivery->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oDeliveryArticlesAjax = oxNew('delivery_articles_ajax');
            $this->_aViewData['oxajax'] = $oDeliveryArticlesAjax->getColumns();

            return "popups/delivery_articles.tpl";
        } elseif ($iAoc == 2) {
            $oDeliveryCategoriesAjax = oxNew('delivery_categories_ajax');
            $this->_aViewData['oxajax'] = $oDeliveryCategoriesAjax->getColumns();

            return "popups/delivery_categories.tpl";
        }

        return "delivery_articles.tpl";
    }
}
