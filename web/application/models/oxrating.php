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
 * Article rate manager.
 * Performs loading, updating, inserting of article rates.
 *
 */
class oxRating extends oxBase
{

    /**
     * Shop control variable
     *
     * @var string
     */
    protected $_blDisableShopCheck = true;

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxrating';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxratings');
    }

    /**
     * Checks if user can rate product.
     *
     * @param string $sUserId   user id
     * @param string $sType     object type
     * @param string $sObjectId object id
     *
     * @return bool
     */
    public function allowRating($sUserId, $sType, $sObjectId)
    {
        $oDb = oxDb::getDb();
        $myConfig = $this->getConfig();

        if ($iRatingLogsTimeout = $myConfig->getConfigParam('iRatingLogsTimeout')) {
            $sExpDate = date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime() - $iRatingLogsTimeout * 24 * 60 * 60);
            $oDb->execute("delete from oxratings where oxtimestamp < '$sExpDate'");
        }
        $sSelect = "select oxid from oxratings where oxuserid = " . $oDb->quote($sUserId) . " and oxtype=" . $oDb->quote($sType) . " and oxobjectid = " . $oDb->quote($sObjectId);
        if ($oDb->getOne($sSelect)) {
            return false;
        }

        return true;
    }


    /**
     * calculates and return objects rating
     *
     * @param string $sObjectId           object id
     * @param string $sType               object type
     * @param array  $aIncludedObjectsIds array of ids
     *
     * @return float
     */
    public function getRatingAverage($sObjectId, $sType, $aIncludedObjectsIds = null)
    {
        $oDb = oxDb::getDb();

        $sQuerySnipet = '';
        if (is_array($aIncludedObjectsIds) && count($aIncludedObjectsIds) > 0) {
            $sQuerySnipet = " AND ( `oxobjectid` = " . $oDb->quote($sObjectId) . " OR `oxobjectid` in ('" . implode("', '", $aIncludedObjectsIds) . "') )";
        } else {
            $sQuerySnipet = " AND `oxobjectid` = " . $oDb->quote($sObjectId);
        }

        $sSelect = "
            SELECT
                AVG(`oxrating`)
            FROM `oxreviews`
            WHERE `oxrating` > 0
                 AND `oxtype` = " . $oDb->quote($sType)
                   . $sQuerySnipet . "
            LIMIT 1";

        $fRating = 0;
        if ($fRating = $oDb->getOne($sSelect, false, false)) {
            $fRating = round($fRating, 1);
        }

        return $fRating;
    }

    /**
     * calculates and return objects rating count
     *
     * @param string $sObjectId           object id
     * @param string $sType               object type
     * @param array  $aIncludedObjectsIds array of ids
     *
     * @return integer
     */
    public function getRatingCount($sObjectId, $sType, $aIncludedObjectsIds = null)
    {
        $oDb = oxDb::getDb();

        $sQuerySnipet = '';
        if (is_array($aIncludedObjectsIds) && count($aIncludedObjectsIds) > 0) {
            $sQuerySnipet = " AND ( `oxobjectid` = " . $oDb->quote($sObjectId) . " OR `oxobjectid` in ('" . implode("', '", $aIncludedObjectsIds) . "') )";
        } else {
            $sQuerySnipet = " AND `oxobjectid` = " . $oDb->quote($sObjectId);
        }

        $sSelect = "
            SELECT
                COUNT(*)
            FROM `oxreviews`
            WHERE `oxrating` > 0
                AND `oxtype` = " . $oDb->quote($sType)
                   . $sQuerySnipet . "
            LIMIT 1";

        $iCount = 0;
        $iCount = $oDb->getOne($sSelect, false, false);

        return $iCount;
    }

    /**
     * Retuns review object type
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->oxratings__oxtype->value;
    }

    /**
     * Retuns review object id
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->oxratings__oxobjectid->value;
    }

}
