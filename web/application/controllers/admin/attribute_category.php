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
 * Admin category main attributes manager.
 * There is possibility to change attribute description, assign categories to
 * this attribute, etc.
 * Admin Menu: Manage Products -> Attributes -> Gruppen.
 */
class Attribute_Category extends oxAdminDetails
{

    /**
     * Loads Attribute categories info, passes it to Smarty engine and
     * returns name of template file "attribute_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();

        $aListAllIn = array();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oAttr = oxNew("oxattribute");
            $oAttr->load($soxId);
            $this->_aViewData["edit"] = $oAttr;
        }

        if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
            $oAttributeCategoryAjax = oxNew('attribute_category_ajax');
            $this->_aViewData['oxajax'] = $oAttributeCategoryAjax->getColumns();

            return "popups/attribute_category.tpl";
        }

        return "attribute_category.tpl";
    }
}
