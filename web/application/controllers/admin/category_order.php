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
 * Admin article categories order manager.
 * There is possibility to change category sorting.
 * Admin Menu: Manage Products -> Categories -> Order.
 */
class Category_Order extends oxAdminDetails
{

    /**
     * Loads article category ordering info, passes it to Smarty
     * engine and returns name of template file "category_order.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['edit'] = $oCategory = oxNew('oxcategory');

        // resetting
        oxRegistry::getSession()->setVariable('neworder_sess', null);

        $soxId = $this->getEditObjectId();

        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oCategory->load($soxId);

            //Disable editing for derived items
            if ($oCategory->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }
        if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
            $oCategoryOrderAjax = oxNew('category_order_ajax');
            $this->_aViewData['oxajax'] = $oCategoryOrderAjax->getColumns();

            return "popups/category_order.tpl";
        }

        return "category_order.tpl";
    }
}
