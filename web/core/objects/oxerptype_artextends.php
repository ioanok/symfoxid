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


require_once 'oxerptype.php';

/**
 * Article extends type subclass
 */
class oxERPType_Artextends extends oxERPType
{

    /**
     * class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->_sTableName = 'oxartextends';
    }

    /**
     * prepares object for saving in shop
     * returns true if save can proceed further
     *
     * @param oxBase $oShopObject shop object
     * @param array  $aData       data for importing
     *
     * @return boolean
     */
    protected function _preSaveObject($oShopObject, $aData)
    {
        return true;
    }

    /**
     * saves data by calling object saving
     *
     * @param array $aData               data for saving
     * @param bool  $blAllowCustomShopId allow custom shop id
     *
     * @return string | false
     */
    public function saveObject($aData, $blAllowCustomShopId)
    {
        $oShopObject = oxNew('oxi18n');
        $oShopObject->init('oxartextends');
        $oShopObject->setLanguage(0);
        $oShopObject->setEnableMultilang(false);

        foreach ($aData as $key => $value) {
            // change case to UPPER
            $sUPKey = strtoupper($key);
            if (!isset($aData[$sUPKey])) {
                unset($aData[$key]);
                $aData[$sUPKey] = $value;
            }
        }


        $blLoaded = false;
        if ($aData['OXID']) {
            $blLoaded = $oShopObject->load($aData['OXID']);
        }

        $aData = $this->_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);

        if ($blLoaded) {
            $this->checkWriteAccess($oShopObject, $aData);
        } else {
            $this->checkCreateAccess($aData);
        }

        $oShopObject->assign($aData);

        if ($blAllowCustomShopId) {
            $oShopObject->setIsDerived(false);
        }

        if ($this->_preSaveObject($oShopObject, $aData)) {
            // store
            if ($oShopObject->save()) {
                return $this->_postSaveObject($oShopObject, $aData);
            }
        }

        return false;
    }
}
