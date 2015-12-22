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
 * Abstract PayPal Dispatcher class
 */
abstract class oePayPalDispatcher extends oePayPalController
{
    /**
     * Service type identifier - Standard Checkout = 1
     *
     * @var int
     */
    protected $_iServiceType = 1;

    /**
     * PayPal checkout service
     *
     * @var oePayPalCheckoutService
     */
    protected $_oPayPalCheckoutService;

    /**
     * Default user action for checkout process
     *
     * @var string
     */
    protected $_sUserAction = "continue";

    /**
     * Executes "GetExpressCheckoutDetails" and on SUCCESS response - saves
     * user information and redirects to order page, on failure - sets error
     * message and redirects to basket page
     *
     * @return string
     */
    abstract public function getExpressCheckoutDetails();

    /**
     * Sets PayPal checkout service.
     *
     * @param oePayPalService $oPayPalCheckoutService
     */
    public function setPayPalCheckoutService($oPayPalCheckoutService)
    {
        $this->_oPayPalCheckoutService = $oPayPalCheckoutService;
    }

    /**
     * Returns PayPal service
     *
     * @return oePayPalService
     */
    public function getPayPalCheckoutService()
    {
        if ($this->_oPayPalCheckoutService === null) {
            $this->_oPayPalCheckoutService = oxNew("oePayPalService");
        }

        return $this->_oPayPalCheckoutService;
    }

    /**
     * Returns oxUtilsView instance
     *
     * @return oxUtilsView
     */
    protected function _getUtilsView()
    {
        return oxRegistry::get("oxUtilsView");
    }

    /**
     * Formats given float/int value into PayPal friendly form
     *
     * @param float $fIn value to format
     *
     * @return string
     */
    protected function _formatFloat($fIn)
    {
        return sprintf("%.2f", $fIn);
    }

    /**
     * Returns oxUtils instance
     *
     * @return oxUtils
     */
    protected function _getUtils()
    {
        return oxRegistry::getUtils();
    }

    /**
     * Returns base url, which is used to construct Callback, Return and Cancel Urls
     *
     * @return string
     */
    protected function _getBaseUrl()
    {
        $oSession = $this->getSession();
        $sUrl = $this->getConfig()->getSslShopUrl() . "index.php?lang=" . oxRegistry::getLang()->getBaseLanguage() . "&sid=" . $oSession->getId() . "&rtoken=" . $oSession->getRemoteAccessToken();
        $sUrl .= "&shp=" . $this->getConfig()->getShopId();

        return $sUrl;
    }

    /**
     * Returns PayPal order object
     *
     * @return oxOrder
     */
    protected function _getPayPalOrder()
    {
        $oOrder = oxNew("oxOrder");
        if ($oOrder->loadPayPalOrder()) {
            return $oOrder;
        }
    }

    /**
     * Returns PayPal payment object
     *
     * @return oxPayment
     */
    protected function _getPayPalPayment()
    {
        if (($oOrder = $this->_getPayPalOrder())) {
            $oUserPayment = oxNew('oxUserPayment');
            $oUserPayment->load($oOrder->oxorder__oxpaymentid->value);

            return $oUserPayment;
        }
    }
}
