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
 * Admin shop system RDFa manager.
 * Collects shop system settings, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> RDFa.
 *
 */
class shop_rdfa extends Shop_Config
{

    /**
     * Template name
     *
     * @var array
     */
    protected $_sThisTemplate = 'shop_rdfa.tpl';

    /**
     * Predefined customer types
     *
     * @var array
     */
    protected $_aCustomers = array("Enduser"           => 0,
                                   "Reseller"          => 0,
                                   "Business"          => 0,
                                   "PublicInstitution" => 0);

    /**
     * Gets list of content pages which could be used for embedding
     * business entity, price specification, and delivery specification data
     *
     * @return oxContentList
     */
    public function getContentList()
    {
        $oContentList = oxNew("oxcontentlist");
        $sTable = getViewName("oxcontents", $this->_iEditLang);
        $oContentList->selectString(
            "SELECT * FROM {$sTable} WHERE OXACTIVE = 1 AND OXTYPE = 0
                                    AND OXLOADID IN ('oxagb', 'oxdeliveryinfo', 'oximpressum', 'oxrightofwithdrawal')
                                    AND OXSHOPID = '" . oxRegistry::getConfig()->getRequestParameter("oxid") . "'"
        ); // $this->getEditObjectId()
        return $oContentList;
    }

    /**
     * Handles and returns customer array
     *
     * @return array
     */
    public function getCustomers()
    {
        $aCustomersConf = $this->getConfig()->getShopConfVar("aRDFaCustomers");
        if (isset($aCustomersConf)) {
            foreach ($this->_aCustomers as $sCustomer => $iValue) {
                $aCustomers[$sCustomer] = (in_array($sCustomer, $aCustomersConf)) ? 1 : 0;
            }
        } else {
            $aCustomers = array();
        }

        return $aCustomers;
    }

    /**
     * Submits shop main page to web search engines
     */
    public function submitUrl()
    {
        $aParams = oxRegistry::getConfig()->getRequestParameter("aSubmitUrl");
        if ($aParams['url']) {
            $sNotificationUrl = "http://gr-notify.appspot.com/submit?uri=" . urlencode($aParams['url']) . "&agent=oxid";
            if ($aParams['email']) {
                $sNotificationUrl .= "&contact=" . urlencode($aParams['email']);
            }
            $aHeaders = $this->getHttpResponseCode($sNotificationUrl);
            if (substr($aHeaders[2], -4) === "True") {
                $this->_aViewData["submitMessage"] = 'SHOP_RDFA_SUBMITED_SUCCESSFULLY';
            } else {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay(substr($aHeaders[3], strpos($aHeaders[3], ":") + 2));
            }
        } else {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay('SHOP_RDFA_MESSAGE_NOURL');
        }
    }

    /**
     * Returns an array with the headers
     *
     * @param string $sURL target URL
     *
     * @return array
     */
    public function getHttpResponseCode($sURL)
    {
        return get_headers($sURL);
    }
}
