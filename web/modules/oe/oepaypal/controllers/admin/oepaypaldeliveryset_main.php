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
 */

/**
 * Adds additional functionality needed for PayPal when managing delivery sets.
 */
class oePayPalDeliverySet_Main extends oePayPalDeliverySet_Main_parent
{
    /**
     * Add default PayPal mobile payment.
     *
     * @return string
     */
    public function render()
    {
        $sTemplate = parent::render();

        $sDeliverySetId = $this->getEditObjectId();
        if ($sDeliverySetId != "-1" && isset($sDeliverySetId)) {
            /** @var oePayPalConfig $oConfig */
            $oConfig = oxNew('oePayPalConfig');

            $blIsPayPalDefaultMobilePayment = ($sDeliverySetId == $oConfig->getMobileECDefaultShippingId());

            $this->_aViewData['blIsPayPalDefaultMobilePayment'] = $blIsPayPalDefaultMobilePayment;
        }

        return $sTemplate;
    }

    /**
     * Saves default PayPal mobile payment.
     */
    public function save()
    {
        parent::save();

        $oConfig = $this->getConfig();
        /** @var oePayPalConfig $oPayPalConfig */
        $oPayPalConfig = oxNew('oePayPalConfig');

        $sDeliverySetId = $this->getEditObjectId();
        $blDeliverySetMarked = (bool) $oConfig->getRequestParameter('isPayPalDefaultMobilePayment');
        $sMobileECDefaultShippingId = $oPayPalConfig->getMobileECDefaultShippingId();

        if ($blDeliverySetMarked && $sDeliverySetId != $sMobileECDefaultShippingId) {
            $this->_saveECDefaultShippingId($oConfig, $sDeliverySetId, $oPayPalConfig);
        } elseif (!$blDeliverySetMarked && $sDeliverySetId == $sMobileECDefaultShippingId) {
            $this->_saveECDefaultShippingId($oConfig, '', $oPayPalConfig);
        }
    }

    /**
     * Save default shipping id.
     *
     * @param oxConfig       $oConfig       Config object to save.
     * @param string         $sShippingId   Shipping id.
     * @param oePayPalConfig $oPayPalConfig PayPal config.
     */
    protected function _saveECDefaultShippingId($oConfig, $sShippingId, $oPayPalConfig)
    {
        $sPayPalModuleId = 'module:' . $oPayPalConfig->getModuleId();
        $oConfig->saveShopConfVar('string', 'sOEPayPalMECDefaultShippingId', $sShippingId, null, $sPayPalModuleId);
    }
}
