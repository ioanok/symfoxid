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
 * Group manager.
 * Base class for user groups. Does nothing special yet.
 *
 */
class oxGroups extends oxI18n
{

    /**
     * Name of current class
     *
     * @var string
     */
    protected $_sClassName = 'oxgroups';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxgroups');
    }


    /**
     * Deletes user group from database. Returns true/false, according to deleting status.
     *
     * @param string $sOXID Object ID (default null)
     *
     * @return bool
     */
    public function delete($sOXID = null)
    {
        if (!$sOXID) {
            $sOXID = $this->getId();
        }
        if (!$sOXID) {
            return false;
        }



        parent::delete($sOXID);

        $oDb = oxDb::getDb();


        // deleting related data records
        $sDelete = 'delete from oxobject2group where oxobject2group.oxgroupsid = ' . $oDb->quote($sOXID);
        $rs = $oDb->execute($sDelete);

        $sDelete = 'delete from oxobject2delivery where oxobject2delivery.oxobjectid = ' . $oDb->quote($sOXID);
        $rs = $oDb->execute($sDelete);

        $sDelete = 'delete from oxobject2discount where oxobject2discount.oxobjectid = ' . $oDb->quote($sOXID);
        $rs = $oDb->execute($sDelete);

        $sDelete = 'delete from oxobject2payment where oxobject2payment.oxobjectid = ' . $oDb->quote($sOXID);
        $rs = $oDb->execute($sDelete);

        return $rs->EOF;
    }

}
