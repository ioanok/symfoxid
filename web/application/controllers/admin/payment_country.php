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
 * Admin article main payment manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Shop Settings -> Payment Methods -> Main.
 */
class Payment_Country extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxlist object,
     * passes it's data to Smarty engine and retutns name of template
     * file "payment_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        // remove itm from list
        unset($this->_aViewData["sumtype"][2]);

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oPayment = oxNew("oxpayment");
            $oPayment->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $oPayment->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oPayment->loadInLang(key($oOtherLang), $soxId);
            }
            $this->_aViewData["edit"] = $oPayment;

            // remove already created languages
            $aLang = array_diff(oxRegistry::getLang()->getLanguageNames(), $oOtherLang);
            if (count($aLang)) {
                $this->_aViewData["posslang"] = $aLang;
            }

            foreach ($oOtherLang as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }

        if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
            $oPaymentCountryAjax = oxNew('payment_country_ajax');
            $this->_aViewData['oxajax'] = $oPaymentCountryAjax->getColumns();

            return "popups/payment_country.tpl";
        }

        return "payment_country.tpl";
    }

    /**
     * Adds chosen user group (groups) to delivery list
     */
    public function addcountry()
    {
        $sOxId = $this->getEditObjectId();
        $aChosenCntr = oxRegistry::getConfig()->getRequestParameter("allcountries");
        if (isset($sOxId) && $sOxId != "-1" && is_array($aChosenCntr)) {
            foreach ($aChosenCntr as $sChosenCntr) {
                $oObject2Payment = oxNew('oxbase');
                $oObject2Payment->init('oxobject2payment');
                $oObject2Payment->oxobject2payment__oxpaymentid = new oxField($sOxId);
                $oObject2Payment->oxobject2payment__oxobjectid = new oxField($sChosenCntr);
                $oObject2Payment->oxobject2payment__oxtype = new oxField("oxcountry");
                $oObject2Payment->save();
            }
        }
    }

    /**
     * Removes chosen user group (groups) from delivery list
     */
    public function removecountry()
    {
        $sOxId = $this->getEditObjectId();
        $aChosenCntr = oxRegistry::getConfig()->getRequestParameter("countries");
        if (isset($sOxId) && $sOxId != "-1" && is_array($aChosenCntr)) {
            foreach ($aChosenCntr as $sChosenCntr) {
                $oObject2Payment = oxNew('oxbase');
                $oObject2Payment->init('oxobject2payment');
                $oObject2Payment->delete($sChosenCntr);
            }
        }
    }
}
