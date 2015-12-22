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
 * Shop view validator.
 * checks which views are valid / invalid
 *
 */
class oxShopViewValidator
{

    protected $_aMultiLangTables = array();

    protected $_aMultiShopTables = array();

    protected $_aLanguages = array();

    protected $_aAllShopLanguages = array();

    protected $_iShopId = null;

    protected $_aAllViews = array();

    protected $_aShopViews = array();

    protected $_aValidShopViews = array();

    /**
     * Sets multi language tables.
     *
     * @param null $aMultiLangTables
     */
    public function setMultiLangTables($aMultiLangTables)
    {
        $this->_aMultiLangTables = $aMultiLangTables;
    }

    /**
     * Returns multi lang tables
     *
     * @return array
     */
    public function getMultiLangTables()
    {
        return $this->_aMultiLangTables;
    }


    /**
     * Sets multi shop tables.
     *
     * @param array $aMultiShopTables
     */
    public function setMultiShopTables($aMultiShopTables)
    {
        $this->_aMultiShopTables = $aMultiShopTables;
    }

    /**
     * Returns multi shop tables
     *
     * @return array
     */
    public function getMultiShopTables()
    {
        return $this->_aMultiShopTables;
    }

    /**
     * Returns list of active languages in shop
     *
     * @param array $aLanguages
     */
    public function setLanguages($aLanguages)
    {
        $this->_aLanguages = $aLanguages;
    }

    /**
     * Gets languages.
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->_aLanguages;
    }

    /**
     * Returns list of active languages in shop
     *
     * @param array $aAllShopLanguages
     */
    public function setAllShopLanguages($aAllShopLanguages)
    {
        $this->_aAllShopLanguages = $aAllShopLanguages;
    }

    /**
     * Gets all shop languages.
     *
     * @return array
     */
    public function getAllShopLanguages()
    {
        return $this->_aAllShopLanguages;
    }


    /**
     * Sets shop id.
     *
     * @param integer $iShopId
     */
    public function setShopId($iShopId)
    {
        $this->_iShopId = $iShopId;
    }

    /**
     * Returns list of available shops
     *
     * @return integer
     */
    public function getShopId()
    {
        return $this->_iShopId;
    }

    /**
     * Returns list of all shop views
     *
     * @return array
     */
    protected function _getAllViews()
    {
        if (empty($this->_aAllViews)) {
            $this->_aAllViews = oxDb::getDb()->getCol("SHOW TABLES LIKE  'oxv_%'");
        }

        return $this->_aAllViews;
    }

    /**
     * Checks if given view name belongs to current subshop or is general view
     *
     * @param string $sViewName View name
     *
     * @return bool
     */
    protected function _isCurrentShopView($sViewName)
    {
        $blResult = false;

        $blEndsWithShopId = preg_match("/[_]([0-9]+)$/", $sViewName, $aMatchEndsWithShopId);
        $blContainsShopId = preg_match("/[_]([0-9]+)[_]/", $sViewName, $aMatchContainsShopId);

        if ((!$blEndsWithShopId && !$blContainsShopId) ||
            ($blEndsWithShopId && $aMatchEndsWithShopId[1] == $this->getShopId()) ||
            ($blContainsShopId && $aMatchContainsShopId[1] == $this->getShopId())
        ) {

            $blResult = true;
        }

        return $blResult;
    }


    /**
     * Returns list of shop specific views currently in database
     *
     * @return array
     */
    protected function _getShopViews()
    {
        if (empty($this->_aShopViews)) {

            $this->_aShopViews = array();
            $aAllViews = $this->_getAllViews();

            foreach ($aAllViews as $sView) {

                if ($this->_isCurrentShopView($sView)) {
                    $this->_aShopViews[] = $sView;
                }
            }
        }

        return $this->_aShopViews;
    }

    /**
     * Returns list of valid shop views
     *
     * @return array
     */
    protected function _getValidShopViews()
    {
        if (empty($this->_aValidShopViews)) {

            $aTables = $this->getMultilangTables();


            $this->_aValidShopViews = array();

            foreach ($aTables as $sTable) {
                $this->_aValidShopViews[] = 'oxv_' . $sTable;

                if (in_array($sTable, $this->getMultiLangTables())) {
                    foreach ($this->getAllShopLanguages() as $sLang) {
                        $this->_aValidShopViews[] = 'oxv_' . $sTable . '_' . $sLang;
                    }
                }

            }
        }

        return $this->_aValidShopViews;
    }

    /**
     * Checks if view name is valid according to current config
     *
     * @param string $sViewName View name
     *
     * @return bool
     */
    protected function _isViewValid($sViewName)
    {
        return in_array($sViewName, $this->_getValidShopViews());
    }

    /**
     * Returns list of invalid views
     *
     * @return array
     */
    public function getInvalidViews()
    {
        $aInvalidViews = array();
        $aShopViews = $this->_getShopViews();

        foreach ($aShopViews as $sView) {
            if (!$this->_isViewValid($sView)) {
                $aInvalidViews[] = $sView;
            }
        }

        return $aInvalidViews;
    }
}
