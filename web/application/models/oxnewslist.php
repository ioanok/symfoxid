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
 * News list manager.
 * Creates news objects, fetches its data.
 *
 */
class oxNewslist extends oxList
{

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'oxnews';

    /**
     * Ref. to user object
     */
    protected $_oUser = null;

    /**
     * Loads news stored in DB, filtered by user groups, returns array, filled with
     * objects, that keeps news data.
     *
     * @param integer $iFrom  number from which start selecting
     * @param integer $iLimit Limit of records to fetch from DB(default 0)
     */
    public function loadNews($iFrom = 0, $iLimit = 10)
    {
        if ($iLimit) {
            $this->setSqlLimit($iFrom, $iLimit);
        }

        $sNewsViewName = getViewName('oxnews');
        $oBaseObject = $this->getBaseObject();
        $sSelectFields = $oBaseObject->getSelectFields();

        if ($oUser = $this->getUser()) {
            // performance - only join if user is logged in
            $sSelect = "select $sSelectFields from $sNewsViewName ";
            $sSelect .= "left join oxobject2group on oxobject2group.oxobjectid=$sNewsViewName.oxid where ";
            $sSelect .= "oxobject2group.oxgroupsid in ( select oxgroupsid from oxobject2group where oxobjectid='" . $oUser->getId() . "' ) or ";
            $sSelect .= "( oxobject2group.oxgroupsid is null ) ";
        } else {
            $sSelect = "select $sSelectFields, oxobject2group.oxgroupsid from $sNewsViewName ";
            $sSelect .= "left join oxobject2group on oxobject2group.oxobjectid=$sNewsViewName.oxid where oxobject2group.oxgroupsid is null ";
        }

        $sSelect .= " and " . $oBaseObject->getSqlActiveSnippet();
        $sSelect .= " and $sNewsViewName.oxshortdesc <> '' ";
        $sSelect .= " group by $sNewsViewName.oxid order by $sNewsViewName.oxdate desc ";

        $this->selectString($sSelect);
    }

    /**
     * Returns count of all entries.
     *
     * @return integer $iRecCnt
     */
    public function getCount()
    {
        $myConfig = $this->getConfig();
        $oDb = oxDb::getDb();

        $sNewsViewName = getViewName('oxnews');
        $oBaseObject = $this->getBaseObject();
        //$sSelectFields = $oBaseObject->getSelectFields();

        if ($oUser = $this->getUser()) {
            // performance - only join if user is logged in
            $sSelect = "select COUNT($sNewsViewName.`oxid`) from $sNewsViewName ";
            $sSelect .= "left join oxobject2group on oxobject2group.oxobjectid=$sNewsViewName.oxid where ";
            $sSelect .= "oxobject2group.oxgroupsid in ( select oxgroupsid from oxobject2group where oxobjectid='" . $oUser->getId() . "' ) or ";
            $sSelect .= "( oxobject2group.oxgroupsid is null ) ";
        } else {
            $sSelect = "select COUNT($sNewsViewName.`oxid`) from $sNewsViewName ";
            $sSelect .= "left join oxobject2group on oxobject2group.oxobjectid=$sNewsViewName.oxid where oxobject2group.oxgroupsid is null ";
        }

        $sSelect .= " and " . $oBaseObject->getSqlActiveSnippet();

        // loading only if there is some data
        $iRecCnt = (int) $oDb->getOne($sSelect);

        return $iRecCnt;
    }

    /**
     * News list user setter
     *
     * @param oxuser $oUser user object
     */
    public function setUser($oUser)
    {
        $this->_oUser = $oUser;
    }

    /**
     * News list user getter
     *
     * @return oxuser
     */
    public function getUser()
    {
        if ($this->_oUser == null) {
            $this->_oUser = parent::getUser();
        }

        return $this->_oUser;
    }
}
