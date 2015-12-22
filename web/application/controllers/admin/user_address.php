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
 * Admin user address setting manager.
 * Collects user address settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> Addresses.
 */
class User_Address extends oxAdminDetails
{

    /**
     * If true, means that address was deleted
     *
     * @var bool
     */
    protected $_blDelete = false;

    /**
     * Executes parent method parent::render(), creates oxuser and oxbase objects,
     * passes data to Smarty engine and returns name of template file
     * "user_address.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oUser = oxNew("oxuser");
            $oUser->load($soxId);

            // load adress
            $sAddressIdParameter = oxRegistry::getConfig()->getRequestParameter("oxaddressid");
            $soxAddressId = isset($this->sSavedOxid) ? $this->sSavedOxid : $sAddressIdParameter;
            if ($soxAddressId != "-1" && isset($soxAddressId)) {
                $oAdress = oxNew("oxaddress");
                $oAdress->load($soxAddressId);
                $this->_aViewData["edit"] = $oAdress;
            }

            $this->_aViewData["oxaddressid"] = $soxAddressId;

            // generate selected
            $oAddressList = $oUser->getUserAddresses();
            foreach ($oAddressList as $oAddress) {
                if ($oAddress->oxaddress__oxid->value == $soxAddressId) {
                    $oAddress->selected = 1;
                    break;
                }
            }

            $this->_aViewData["edituser"] = $oUser;
        }

        $oCountryList = oxNew("oxCountryList");
        $oCountryList->loadActiveCountries(oxRegistry::getLang()->getObjectTplLanguage());

        $this->_aViewData["countrylist"] = $oCountryList;

        if (!$this->_allowAdminEdit($soxId)) {
            $this->_aViewData['readonly'] = true;
        }

        return "user_address.tpl";
    }

    /**
     * Saves user addressing information.
     */
    public function save()
    {
        parent::save();

        if ($this->_allowAdminEdit($this->getEditObjectId())) {
            $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
            $oAdress = oxNew("oxaddress");
            if (isset($aParams['oxaddress__oxid']) && $aParams['oxaddress__oxid'] == "-1") {
                $aParams['oxaddress__oxid'] = null;
            } else {
                $oAdress->load($aParams['oxaddress__oxid']);
            }

            $oAdress->assign($aParams);
            $oAdress->save();

            $this->sSavedOxid = $oAdress->getId();
        }
    }

    /**
     * Deletes user addressing information.
     */
    public function delAddress()
    {
        $this->_blDelete = false;
        if ($this->_allowAdminEdit($this->getEditObjectId())) {
            $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
            if (isset($aParams['oxaddress__oxid']) && $aParams['oxaddress__oxid'] != "-1") {
                $oAdress = oxNew("oxaddress");
                $this->_blDelete = $oAdress->delete($aParams['oxaddress__oxid']);
            }
        }
    }
}
