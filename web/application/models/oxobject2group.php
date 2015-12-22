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
 * Manages object (users, discounts, deliveries...) assignment to groups.
 */
class oxObject2Group extends oxBase
{
    /**
     * Load the relation even if from other shop
     *
     * @var boolean
     */
    protected $_blDisableShopCheck = true;

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxobject2group';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxobject2group');
        $this->oxobject2group__oxshopid = new oxField($this->getConfig()->getShopId(), oxField::T_RAW);
    }

    /**
     * Extends the default save method.
     * Saves only if this kind of entry do not exists.
     *
     * @return bool
     */
    public function save()
    {
        $oDb = oxDb::getDb();
        $sQ = "select 1 from oxobject2group where oxgroupsid = " . $oDb->quote($this->oxobject2group__oxgroupsid->value);
        $sQ .= " and oxobjectid = " . $oDb->quote($this->oxobject2group__oxobjectid->value);

        // does not exist
        if (!$oDb->getOne($sQ, false, false)) {
            return parent::save();
        }
    }
}
