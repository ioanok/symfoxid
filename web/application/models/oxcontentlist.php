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
 * Content list manager.
 * Collects list of content
 *
 */
class oxContentList extends oxList
{

    /**
     * Information content type
     *
     * @var int
     */
    const TYPE_INFORMATION_CONTENTS = 0;

    /**
     * Main menu list type
     *
     * @var int
     */
    const TYPE_MAIN_MENU_LIST = 1;

    /**
     * Main menu list type
     *
     * @var int
     */
    const TYPE_CATEGORY_MENU = 2;

    /**
     * Service list.
     *
     * @var int
     */
    const TYPE_SERVICE_LIST = 3;

    /**
     * List of services.
     *
     * @var array
     */
    protected $_aServiceKeys = array('oximpressum', 'oxagb', 'oxsecurityinfo', 'oxdeliveryinfo', 'oxrightofwithdrawal', 'oxorderinfo', 'oxcredits');

    /**
     * Sets service keys.
     *
     * @param array $aServiceKeys
     */
    public function setServiceKeys($aServiceKeys)
    {
        $this->_aServiceKeys = $aServiceKeys;
    }

    /**
     * Gets services keys.
     *
     * @return array
     */
    public function getServiceKeys()
    {
        return $this->_aServiceKeys;
    }

    /**
     * Class constructor, initiates parent constructor (parent::oxList()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct('oxcontent');
    }

    /**
     * Loads main menue entries and generates list with links
     */
    public function loadMainMenulist()
    {
        $this->_load(self::TYPE_MAIN_MENU_LIST);
    }

    /**
     * Load Array of Menue items and change keys of aList to catid
     */
    public function loadCatMenues()
    {
        $this->_load(self::TYPE_CATEGORY_MENU);
        $aArray = array();

        if ($this->count()) {
            foreach ($this as $oContent) {
                // add into category tree
                if (!isset($aArray[$oContent->getCategoryId()])) {
                    $aArray[$oContent->getCategoryId()] = array();
                }

                $aArray[$oContent->oxcontents__oxcatid->value][] = $oContent;
            }
        }

        $this->_aArray = $aArray;
    }


    /**
     * Get data from db
     *
     * @param integer $iType - type of content
     *
     * @return array
     */
    protected function _loadFromDb($iType)
    {
        $sSql = $this->_getSQLByType($iType);
        $aData = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($sSql);

        return $aData;
    }

    /**
     * Load category list data
     *
     * @param integer $iType - type of content
     */
    protected function _load($iType)
    {

        $aData = $this->_loadFromDb($iType);

        $this->assignArray($aData);
    }


    /**
     * Load category list data.
     */
    public function loadServices()
    {
        $this->_load(self::TYPE_SERVICE_LIST);
        $this->_extractListToArray();
    }

    /**
     * Extract oxContentList object to associative array with oxloadid as keys.
     */
    protected function _extractListToArray()
    {
        $aExtractedContents = array();
        foreach ($this as $oContent) {
            $aExtractedContents[$oContent->getLoadId()] = $oContent;
        }

        $this->_aArray = $aExtractedContents;
    }

    /**
     * Creates SQL by type.
     *
     * @param integer $iType type.
     *
     * @return string
     */
    protected function _getSQLByType($iType)
    {
        $sSQLAdd = '';
        $oDb = oxDb::getDb();
        $sSQLType = " AND `oxtype` = " . $oDb->quote($iType);

        if ($iType == self::TYPE_CATEGORY_MENU) {
            $sSQLAdd = " AND `oxcatid` IS NOT NULL AND `oxsnippet` = '0'";
        }

        if ($iType == self::TYPE_SERVICE_LIST) {
            $sIdents = implode(", ", oxDb::getInstance()->quoteArray($this->getServiceKeys()));
            $sSQLAdd = " AND OXLOADID IN (" . $sIdents . ")";
            $sSQLType = '';
        }
        $sViewName = $this->getBaseObject()->getViewName();
        $sSql = "SELECT * FROM {$sViewName} WHERE `oxactive` = '1' $sSQLType AND `oxshopid` = " . $oDb->quote($this->_sShopID) . " $sSQLAdd ORDER BY `oxloadid`";

        return $sSql;
    }
}
