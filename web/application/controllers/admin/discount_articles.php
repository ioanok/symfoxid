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
 * Admin article main discount manager.
 * There is possibility to change discount name, article, user
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling -> Main.
 */
class Discount_Articles extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates discount category tree,
     * passes data to Smarty engine and returns name of template file "discount_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ($soxId != '-1' && isset($soxId)) {
            // load object
            $oDiscount = oxNew('oxdiscount');
            $oDiscount->load($soxId);
            $this->_aViewData['edit'] = $oDiscount;

            //disabling derived items
            if ($oDiscount->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }

            // generating category tree for artikel choose select list
            $this->_createCategoryTree("artcattree");
        }

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oDiscountArticlesAjax = oxNew('discount_articles_ajax');
            $this->_aViewData['oxajax'] = $oDiscountArticlesAjax->getColumns();

            return "popups/discount_articles.tpl";
        } elseif ($iAoc == 2) {
            $oDiscountCategoriesAjax = oxNew('discount_categories_ajax');
            $this->_aViewData['oxajax'] = $oDiscountCategoriesAjax->getColumns();

            return "popups/discount_categories.tpl";
        }

        return 'discount_articles.tpl';
    }
}
