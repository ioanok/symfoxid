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
 * Admin deliveryset User manager.
 * There is possibility to add User, groups
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling Sets -> Users.
 */
class DeliverySet_Users extends oxAdminDetails
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

        // all usergroups
        $oGroups = oxNew('oxlist');
        $oGroups->init('oxgroups');
        $oGroups->selectString("select * from " . getViewName("oxgroups", $this->_iEditLang));

        $oRoot = new oxgroups();
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
            $oDelivery = oxNew("oxdeliveryset");
            $oDelivery->load($soxId);

            //Disable editing for derived articles
            if ($oDelivery->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }

        $this->_aViewData["allgroups2"] = $oGroups;

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oDeliverysetGroupsAjax = oxNew('deliveryset_groups_ajax');
            $this->_aViewData['oxajax'] = $oDeliverysetGroupsAjax->getColumns();

            return "popups/deliveryset_groups.tpl";
        } elseif ($iAoc == 2) {
            $oDeliverysetUsersAjax = oxNew('deliveryset_users_ajax');
            $this->_aViewData['oxajax'] = $oDeliverysetUsersAjax->getColumns();

            return "popups/deliveryset_users.tpl";
        }

        return "deliveryset_users.tpl";
    }
}
