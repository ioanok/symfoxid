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
 * Admin deliveryset payment manager.
 * There is possibility to assign set to payment method
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling Set -> Payment
 */
class DeliverySet_Payment extends oxAdminDetails
{

    /**
     * Executes parent method parent::render()
     * passes data to Smarty engine and returns name of template file "deliveryset_payment.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $odeliveryset = oxNew("oxdeliveryset");
            $odeliveryset->setLanguage($this->_iEditLang);
            $odeliveryset->load($soxId);

            $oOtherLang = $odeliveryset->getAvailableInLangs();

            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $odeliveryset->setLanguage(key($oOtherLang));
                $odeliveryset->load($soxId);
            }

            $this->_aViewData["edit"] = $odeliveryset;

            //Disable editing for derived articles
            if ($odeliveryset->isDerived()) {
                $this->_aViewData['readonly'] = true;
            }
        }

        $iAoc = oxRegistry::getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oDeliverysetPaymentAjax = oxNew('deliveryset_payment_ajax');
            $this->_aViewData['oxajax'] = $oDeliverysetPaymentAjax->getColumns();

            return "popups/deliveryset_payment.tpl";
        } elseif ($iAoc == 2) {
            $oDeliverysetCountryAjax = oxNew('deliveryset_country_ajax');
            $this->_aViewData['oxajax'] = $oDeliverysetCountryAjax->getColumns();

            return "popups/deliveryset_country.tpl";
        }

        return "deliveryset_payment.tpl";
    }
}
