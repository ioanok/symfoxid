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
class Delivery_Users extends oxAdminDetails
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
        $sSelGroup = oxRegistry::getConfig()->getRequestParameter("selgroup");

        $sViewName = getViewName("oxgroups", $this->_iEditLang);
        // all usergroups
        $oGroups = oxNew("oxlist");
        $oGroups->init('oxgroups');
        $oGroups->selectString("select * from {$sViewName}");

        $oRoot = new oxGroups();
        $oRoot->oxgroups__oxid = new oxField("");
        $oRoot->oxgroups__oxtitle = new oxField("-- ");
        // rebuild list as we need the "no value" entry at the first position
        $aNewList = array();
        $aNewList[] = $oRoot;

        foreach ($oGroups as $val) {
            $aNewList[$val->oxgroups__oxid->value] = new oxGroups();
            $aNewList[$val->oxgroups__oxid->value]->oxgroups__oxid = new oxField($val->oxgroups__oxid->value);
            $aNewList[$val->oxgroups__oxid->value]->oxgroups__oxtitle = new oxField($val->oxgroups__oxtitle->value);
        }

        $oGroups = $aNewList;

        if (isset($soxId) && $soxId != "-") {
            $oDelivery = oxNew("oxdelivery");
            $oDelivery->load($soxId);

            //Disable editing for derived articles
            if ($oDelivery->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }

        $this->_aViewData["allgroups2"] = $oGroups;

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oDeliveryUsersAjax = oxNew('delivery_users_ajax');
            $this->_aViewData['oxajax'] = $oDeliveryUsersAjax->getColumns();

            return "popups/delivery_users.tpl";
        } elseif ($iAoc == 2) {
            $oDeliveryGroupsAjax = oxNew('delivery_groups_ajax');
            $this->_aViewData['oxajax'] = $oDeliveryGroupsAjax->getColumns();

            return "popups/delivery_groups.tpl";
        }

        return "delivery_users.tpl";
    }
}
