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
 * Admin dyn trusted manager.
 *
 * @subpackage dyn
 */
class dyn_trusted_ratings extends Shop_Config
{

    /**
     * Config parameter which sould not be converted to multiline string
     *
     * @var array
     */
    protected $_aSkipMultiline = array('aTsLangIds', 'aHomeCountry', 'aTsActiveLangIds');

    /**
     * Creates shop object, passes shop data to Smarty engine and returns name of
     * template file "dyn_trusted.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['oxid'] = $this->getConfig()->getShopId();
        $this->_aViewData["alllang"] = oxRegistry::getLang()->getLanguageArray();

        return "dyn_trusted_ratings.tpl";
    }

    /**
     * Saves changed shop configuration parameters.
     */
    public function save()
    {
        $myConfig = $this->getConfig();
        $sOxId = $this->getEditObjectId();

        // base parameters
        $aConfStrs = oxRegistry::getConfig()->getRequestParameter("confstrs");
        $aConfAArs = oxRegistry::getConfig()->getRequestParameter("confaarrs");
        $aConfBools = oxRegistry::getConfig()->getRequestParameter("confbools");

        // validating language Ids
        if (is_array($aConfAArs['aTsLangIds'])) {

            $blActive = (isset($aConfBools["blTsWidget"]) && $aConfBools["blTsWidget"] == "true") ? true : false;
            $sPkg = "OXID_ESALES";

            $aActiveLangs = array();
            foreach ($aConfAArs['aTsLangIds'] as $sLangId => $sId) {
                $aActiveLangs[$sLangId] = false;
                if ($sId) {
                    $sTsUser = $myConfig->getConfigParam('sTsUser');
                    $sTsPass = $myConfig->getConfigParam('sTsPass');
                    // validating and switching on/off
                    $sResult = $this->_validateId($sId, (bool) $blActive, $sTsUser, $sTsPass, $sPkg);

                    // keeping activation state
                    $aActiveLangs[$sLangId] = $sResult == "OK" ? true : false;

                    // error message
                    if ($sResult && $sResult != "OK") {
                        $this->_aViewData["errorsaving"] = "DYN_TRUSTED_RATINGS_ERR_{$sResult}";
                    }
                }
            }

            $myConfig->saveShopConfVar("arr", "aTsActiveLangIds", $aActiveLangs, $sOxId);
        }

        parent::save();
    }

    /**
     * Returns service wsdl url (test|regular) according to configuration
     *
     * @return string
     */
    protected function _getServiceWsdl()
    {
        $sWsdl = false;
        $oConfig = $this->getConfig();
        $aTsConfig = $oConfig->getConfigParam("aTsConfig");
        if (is_array($aTsConfig)) {
            $sWsdl = $aTsConfig["blTestMode"] ? $oConfig->getConfigParam("sTsServiceTestWsdl") : $oConfig->getConfigParam("sTsServiceWsdl");
        }

        return $sWsdl;
    }

    /**
     * Validates Ts language id and returns validatsion status message
     *
     * @param string $sId      Trusted Shops Id
     * @param string $blActive Widget mode - active or not
     * @param string $sUser    Trusted Shops User name
     * @param string $sPass    Trusted Shops Password
     * @param string $sPkg     Package Name
     *
     * @return string | bool
     */
    protected function _validateId($sId, $blActive, $sUser, $sPass, $sPkg)
    {
        $sReturn = false;
        if (($sWsdl = $this->_getServiceWsdl())) {
            try {
                $oClient = new SoapClient($sWsdl);
                $sReturn = $oClient->updateRatingWidgetState($sId, (int) $blActive, $sUser, $sPass, $sPkg);
            } catch (SoapFault $oFault) {
                $sReturn = $oFault->faultstring;
            }
        }

        return $sReturn;
    }

    /**
     * Returns view id ('dyn_interface')
     *
     * @return string
     */
    public function getViewId()
    {
        return 'dyn_interface';
    }

    /**
     * Converts Multiline text to simple array. Returns this array.
     *
     * @param string $sMultiline Multiline text or array
     *
     * @return array
     */
    protected function _multilineToArray($sMultiline)
    {
        $aArr = $sMultiline;
        if (!is_array($aArr)) {
            $aArr = parent::_multilineToArray($aArr);
        }

        return $aArr;
    }

    /**
     * Converts Multiline text to associative array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToAarray($sMultiline)
    {
        $aArr = $sMultiline;
        if (!is_array($aArr)) {
            $aArr = parent::_multilineToAarray($aArr);
        }

        return $aArr;
    }
}
