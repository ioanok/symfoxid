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
class Discount_Users extends oxAdminDetails
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
        $sSelGroup = oxRegistry::getConfig()->getRequestParameter("selgroup");

        // all usergroups
        $oGroups = oxNew('oxlist');
        $oGroups->init('oxgroups');
        $oGroups->selectString("select * from " . getViewName("oxgroups", $this->_iEditLang));

        $oRoot = new stdClass();
        $oRoot->oxgroups__oxid = new oxField("");
        $oRoot->oxgroups__oxtitle = new oxField("-- ");
        // rebuild list as we need the "no value" entry at the first position
        $aNewList = array();
        $aNewList[] = $oRoot;

        foreach ($oGroups as $val) {
            $aNewList[$val->oxgroups__oxid->value] = new stdClass();
            $aNewList[$val->oxgroups__oxid->value]->oxgroups__oxid = new oxField($val->oxgroups__oxid->value);
            $aNewList[$val->oxgroups__oxid->value]->oxgroups__oxtitle = new oxField($val->oxgroups__oxtitle->value);
        }

        $this->_aViewData["allgroups2"] = $aNewList;

        if (isset($soxId) && $soxId != "-") {
            $oDiscount = oxNew("oxdiscount");
            $oDiscount->load($soxId);

            if ($oDiscount->isDerived()) {
                $this->_aViewData["readonly"] = true;
            }
        }

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oDiscountGroupsAjax = oxNew('discount_groups_ajax');
            $this->_aViewData['oxajax'] = $oDiscountGroupsAjax->getColumns();

            return "popups/discount_groups.tpl";
        } elseif ($iAoc == 2) {
            $oDiscountUsersAjax = oxNew('discount_users_ajax');
            $this->_aViewData['oxajax'] = $oDiscountUsersAjax->getColumns();

            return "popups/discount_users.tpl";
        }

        return "discount_users.tpl";
    }
}
