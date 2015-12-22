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
 * Order Article erp type subclass
 */
class oxERPType_OrderArticle extends oxERPType
{

    /**
     * class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();

        $this->_sTableName = 'oxorderarticles';
        $this->_sShopObjectName = 'oxorderarticle';
    }

    /**
     * returns Sql for export
     *
     * @param string $sWhere    where part of sql
     * @param int    $iLanguage language id
     * @param int    $iShopID   shop id
     *
     * @see objects/oxERPType#getSQL()
     *
     * @return string
     */
    public function getSQL($sWhere, $iLanguage = 0, $iShopID = 1)
    {
        if (strstr($sWhere, 'where')) {
            $sWhere .= ' and ';
        } else {
            $sWhere .= ' where ';
        }

        $sWhere .= 'oxordershopid = \'' . $iShopID . '\'';

        return parent::getSQL($sWhere, $iLanguage, $iShopID);
    }

    /**
     * check for write access for id
     *
     * @param oxBase $oObj  loaded shop object
     * @param array  $aData fields to be written, null for default
     *
     * @throws Exception on now access
     *
     * @return null
     */
    public function checkWriteAccess($oObj, $aData = null)
    {
        return;

        if ($oObj->oxorderarticles__oxordershopid->value != oxRegistry::getConfig()->getShopId()) {
            throw new Exception(oxERPBase::$ERROR_USER_NO_RIGHTS);
        }

        parent::checkWriteAccess($oObj, $aData);
    }

    /**
     * return sql column name of given table column
     *
     * @param string $sField    field name
     * @param int    $iLanguage language id
     * @param int    $iShopID   shop id
     *
     * @return string
     */
    protected function _getSqlFieldName($sField, $iLanguage = 0, $iShopID = 1)
    {
        switch ($sField) {
            case 'OXORDERSHOPID':
                return "'1' as $sField";
        }

        return parent::_getSqlFieldName($sField, $iLanguage, $iShopID);
    }

    /**
     * issued before saving an object. can modify aData for saving
     *
     * @param oxBase $oShopObject         oxBase child for object
     * @param array  $aData               data for object
     * @param bool   $blAllowCustomShopId if true then AllowCustomShopId
     *
     * @return array
     */
    protected function _preAssignObject($oShopObject, $aData, $blAllowCustomShopId)
    {
        $aData = parent::_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);

        // check if data is not serialized
        $aPersVals = @unserialize($aData['OXPERSPARAM']);
        if (!is_array($aPersVals)) {
            // data is a string with | separation, prepare for oxid
            $aPersVals = explode("|", $aData['OXPERSPARAM']);
            $aData['OXPERSPARAM'] = serialize($aPersVals);
        }

        if (isset($aData['OXORDERSHOPID'])) {
            $aData['OXORDERSHOPID'] = 'oxbaseshop';
        }

        return $aData;
    }
}
