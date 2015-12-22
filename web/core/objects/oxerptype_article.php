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

$sArticleClass = oxUtilsObject::getInstance()->getClassName('oxarticle');


eval("class oxErpArticle450_parent extends $sArticleClass {};");

/**
 * article class, used inside erp for 4.5.0 eShop version
 * hotfixe for article long description saving (bug#0002741)
 */
class oxErpArticle450 extends oxErpArticle450_parent
{

    /**
     * Sets article parameter
     *
     * @param string $sName  name of parameter to set
     * @param mixed  $sValue parameter value
     *
     * @return null
     */
    public function __set($sName, $sValue)
    {
        if (strpos($sName, 'oxarticles__oxlongdesc') === 0) {
            if ($this->_blEmployMultilanguage) {
                return parent::__set($sName, $sValue);
            }
            $this->$sName = $sValue;
        } else {
            parent::__set($sName, $sValue);
        }
    }

    /**
     * inserts article long description to artextends table
     *
     * @return null
     */
    protected function _saveArtLongDesc()
    {
        if ($this->_blEmployMultilanguage) {
            return parent::_saveArtLongDesc();
        }


        $oArtExt = oxNew('oxi18n');
        $oArtExt->setEnableMultilang(false);
        $oArtExt->init('oxartextends');
        $aObjFields = $oArtExt->_getAllFields(true);
        if (!$oArtExt->load($this->getId())) {
            $oArtExt->setId($this->getId());
        }

        foreach ($aObjFields as $sKey => $sValue) {
            if (preg_match('/^oxlongdesc(_(\d{1,2}))?$/', $sKey)) {
                $sField = $this->_getFieldLongName($sKey);
                if (isset($this->$sField)) {
                    $sLongDesc = null;
                    if ($this->$sField instanceof oxField) {
                        $sLongDesc = $this->$sField->getRawValue();
                    } elseif (is_object($this->$sField)) {
                        $sLongDesc = $this->$sField->value;
                    }
                    if (isset($sLongDesc)) {
                        $sAEField = $oArtExt->_getFieldLongName($sKey);
                        $oArtExt->$sAEField = new oxField($sLongDesc, oxField::T_RAW);
                    }
                }
            }
        }

        $oArtExt->save();
    }
}

$sArticleClass = 'oxErpArticle450';

eval("class oxErpArticle_parent extends $sArticleClass {};");


/**
 * article class, used inside erp
 * includes variants loading disabling functionality
 */
class oxErpArticle extends oxErpArticle_parent
{

    /**
     * disable variant loading
     *
     * @var bool
     */
    protected $_blLoadVariants = false;
}


/**
 * article type subclass
 */
class oxERPType_Article extends oxERPType
{

    /**
     * class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();

        $this->_sTableName = 'oxarticles';
        $this->_sShopObjectName = 'oxErpArticle';
    }

    /**
     * issued before saving an object. can modify aData for saving
     *
     * @param oxBase $oShopObject         shop object
     * @param array  $aData               data to prepare
     * @param bool   $blAllowCustomShopId if allow custom shop id
     *
     * @return array
     */
    protected function _preAssignObject($oShopObject, $aData, $blAllowCustomShopId)
    {
        if (!isset($aData['OXSTOCKFLAG'])) {
            if (!$aData['OXID'] || !$oShopObject->exists($aData['OXID'])) {
                // default value is 1 according to eShop admin functionality
                $aData['OXSTOCKFLAG'] = 1;
            }
        }

        $aData = parent::_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);

        return $aData;
    }

    /**
     * post saving hook. can finish transactions if needed or ajust related data
     *
     * @param oxBase $oShopObject shop object
     * @param data   $aData       data to save
     *
     * @return mixed data to return
     */
    protected function _postSaveObject($oShopObject, $aData)
    {
        $sOXID = $oShopObject->getId();

        $oShopObject->onChange(null, $sOXID, $sOXID);

        // returning ID on success
        return $sOXID;
    }

    /**
     * Basic access check for writing data. For oxarticle we allow super admin to change
     * subshop oxarticle fields discribed in config option aMultishopArticleFields.
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

    }
}
