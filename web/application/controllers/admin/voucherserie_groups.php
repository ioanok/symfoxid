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
 * Admin voucherserie groups manager.
 * Collects and manages information about user groups, added to one or another
 * serie of vouchers.
 * Admin Menu: Shop Settings -> Vouchers -> Groups.
 */
class VoucherSerie_Groups extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxlist and oxvoucherserie
     * objects, passes it's data to Smarty engine and returns name of template
     * file "voucherserie_groups.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oVoucherSerie = oxNew("oxvoucherserie");
            $oVoucherSerie->load($soxId);
            $oVoucherSerie->setUserGroups();
            $this->_aViewData["edit"] = $oVoucherSerie;

            //Disable editing for derived items
            if ($oVoucherSerie->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }
        if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
            $oVoucherSerieGroupsAjax = oxNew('voucherserie_groups_ajax');
            $this->_aViewData['oxajax'] = $oVoucherSerieGroupsAjax->getColumns();

            return "popups/voucherserie_groups.tpl";
        }

        return "voucherserie_groups.tpl";
    }
}
