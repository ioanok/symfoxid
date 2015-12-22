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
 * Order delivery set manager.
 *
 */
class oxDeliverySet extends oxI18n
{

    /**
     * Current object class name
     *
     * @var string
     */
    protected $_sClassName = 'oxdeliveryset';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxdeliveryset');
    }

    /**
     * Delete this object from the database, returns true on success.
     *
     * @param string $sOxId Object ID(default null)
     *
     * @return bool
     */
    public function delete($sOxId = null)
    {
        if (!$sOxId) {
            $sOxId = $this->getId();
        }
        if (!$sOxId) {
            return false;
        }


        $oDb = oxDb::getDb();

        $sOxIdQuoted = $oDb->quote($sOxId);
        $oDb->execute('delete from oxobject2payment where oxobjectid = ' . $sOxIdQuoted);
        $oDb->execute('delete from oxobject2delivery where oxdeliveryid = ' . $sOxIdQuoted);
        $oDb->execute('delete from oxdel2delset where oxdelsetid = ' . $sOxIdQuoted);

        return parent::delete($sOxId);
    }

    /**
     * returns delivery set id
     *
     * @param string $sTitle delivery name
     *
     * @return string
     */
    public function getIdByName($sTitle)
    {
        $oDb = oxDb::getDb();
        $sQ = "SELECT `oxid` FROM `" . getViewName('oxdeliveryset') . "` WHERE  `oxtitle` = " . $oDb->quote($sTitle);
        $sId = $oDb->getOne($sQ);

        return $sId;
    }
}
