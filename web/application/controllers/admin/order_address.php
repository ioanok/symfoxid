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
 * Admin order address manager.
 * Collects order addressing information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders -> Address.
 */
class Order_Address extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxorder object
     * and passes it's data to Smarty engine. Returns name of template
     * file "order_address.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oOrder = oxNew("oxorder");
            $oOrder->load($soxId);

            $this->_aViewData["edit"] = $oOrder;
        }

        $oCountryList = oxNew("oxCountryList");
        $oCountryList->loadActiveCountries(oxRegistry::getLang()->getObjectTplLanguage());

        $this->_aViewData["countrylist"] = $oCountryList;

        return "order_address.tpl";
    }

    /**
     * Iterates through data array, checks if specified fields are filled
     * in, cleanups not needed data
     *
     * @param array  $aData          data to process
     * @param string $sTypeToProcess data type to process e.g. "oxorder__oxdel"
     * @param array  $aIgnore        fields which must be ignored while processing
     *
     * @return null
     */
    protected function _processAddress($aData, $sTypeToProcess, $aIgnore)
    {
        // empty address fields?
        $blEmpty = true;

        // here we will store names of fields which needs to be cleaned up
        $aFields = array();

        foreach ($aData as $sName => $sValue) {

            // if field type matches..
            if (strpos($sName, $sTypeToProcess) !== false) {

                // storing which fields must be unset..
                $aFields[] = $sName;

                // ignoring whats need to be ignored and testing values
                if (!in_array($sName, $aIgnore) && $sValue) {

                    // something was found - means leaving as is..
                    $blEmpty = false;
                    break;
                }
            }
        }

        // cleanup if empty
        if ($blEmpty) {
            foreach ($aFields as $sName) {
                $aData[$sName] = "";
            }
        }

        return $aData;
    }

    /**
     * Saves ordering address information.
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = (array) oxRegistry::getConfig()->getRequestParameter("editval");

        //TODO check if shop id is realy necessary at this place.
        $sShopID = oxRegistry::getSession()->getVariable("actshop");
        $aParams['oxorder__oxshopid'] = $sShopID;

        $oOrder = oxNew("oxorder");
        if ($soxId != "-1") {
            $oOrder->load($soxId);
        } else {
            $aParams['oxorder__oxid'] = null;
        }

        $aParams = $this->_processAddress($aParams, "oxorder__oxdel", array("oxorder__oxdelsal"));
        $oOrder->assign($aParams);
        $oOrder->save();

        // set oxid if inserted
        $this->setEditObjectId($oOrder->getId());
    }
}
