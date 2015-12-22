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
 * Manufacturer list manager.
 * Collects list of manufacturers according to collection rules (activ, etc.).
 *
 */
class oxManufacturerList extends oxList
{

    /**
     * Manufacturer root.
     *
     * @var stdClass
     */
    protected $_oRoot = null;

    /**
     * Manufacturer tree path.
     *
     * @var array
     */
    protected $_aPath = array();

    /**
     * To show manufacturer article count or not
     *
     * @var bool
     */
    protected $_blShowManufacturerArticleCnt = false;

    /**
     * Active manufacturer object
     *
     * @var oxmanufacturer
     */
    protected $_oClickedManufacturer = null;

    /**
     * Calls parent constructor and defines if Article vendor count is shown
     *
     * @return null
     */
    public function __construct()
    {
        $this->setShowManufacturerArticleCnt($this->getConfig()->getConfigParam('bl_perfShowActionCatArticleCnt'));
        parent::__construct('oxmanufacturer');
    }

    /**
     * Enables/disables manufacturer article count calculation
     *
     * @param bool $blShowManufacturerArticleCnt to show article count or not
     */
    public function setShowManufacturerArticleCnt($blShowManufacturerArticleCnt = false)
    {
        $this->_blShowManufacturerArticleCnt = $blShowManufacturerArticleCnt;
    }

    /**
     * Loads simple manufacturer list
     */
    public function loadManufacturerList()
    {
        $oBaseObject = $this->getBaseObject();

        $sFieldList = $oBaseObject->getSelectFields();
        $sViewName = $oBaseObject->getViewName();
        $this->getBaseObject()->setShowArticleCnt($this->_blShowManufacturerArticleCnt);

        $sWhere = '';
        if (!$this->isAdmin()) {
            $sWhere = $oBaseObject->getSqlActiveSnippet();
            $sWhere = $sWhere ? " where $sWhere and " : ' where ';
            $sWhere .= "{$sViewName}.oxtitle != '' ";
        }

        $sSelect = "select {$sFieldList} from {$sViewName} {$sWhere} order by {$sViewName}.oxtitle";
        $this->selectString($sSelect);
    }

    /**
     * Creates fake root for manufacturer tree, and ads category list fileds for each manufacturer item
     *
     * @param string $sLinkTarget  Name of class, responsible for category rendering
     * @param string $sActCat      Active category
     * @param string $sShopHomeUrl base shop url ($myConfig->getShopHomeURL())
     */
    public function buildManufacturerTree($sLinkTarget, $sActCat, $sShopHomeUrl)
    {
        //Load manufacturer list
        $this->loadManufacturerList();


        //Create fake manufacturer root category
        $this->_oRoot = oxNew("oxManufacturer");
        $this->_oRoot->load("root");

        //category fields
        $this->_addCategoryFields($this->_oRoot);
        $this->_aPath[] = $this->_oRoot;

        foreach ($this as $sVndId => $oManufacturer) {

            // storing active manufacturer object
            if ($sVndId == $sActCat) {
                $this->setClickManufacturer($oManufacturer);
            }

            $this->_addCategoryFields($oManufacturer);
            if ($sActCat == $oManufacturer->oxmanufacturers__oxid->value) {
                $this->_aPath[] = $oManufacturer;
            }
        }

        $this->_seoSetManufacturerData();
    }

    /**
     * Root manufacturer list node (which usually is a manually prefilled object) getter
     *
     * @return oxmanufacturer
     */
    public function getRootCat()
    {
        return $this->_oRoot;
    }

    /**
     * Returns manufacturer path array
     *
     * @return array
     */
    public function getPath()
    {
        return $this->_aPath;
    }

    /**
     * Adds category specific fields to manufacturer object
     *
     * @param object $oManufacturer manufacturer object
     */
    protected function _addCategoryFields($oManufacturer)
    {
        $oManufacturer->oxcategories__oxid = new oxField($oManufacturer->oxmanufacturers__oxid->value);
        $oManufacturer->oxcategories__oxicon = $oManufacturer->oxmanufacturers__oxicon;
        $oManufacturer->oxcategories__oxtitle = $oManufacturer->oxmanufacturers__oxtitle;
        $oManufacturer->oxcategories__oxdesc = $oManufacturer->oxmanufacturers__oxshortdesc;

        $oManufacturer->setIsVisible(true);
        $oManufacturer->setHasVisibleSubCats(false);
    }

    /**
     * Sets active (open) manufacturer object
     *
     * @param oxmanufacturer $oManufacturer active manufacturer
     */
    public function setClickManufacturer($oManufacturer)
    {
        $this->_oClickedManufacturer = $oManufacturer;
    }

    /**
     * returns active (open) manufacturer object
     *
     * @return oxmanufacturer
     */
    public function getClickManufacturer()
    {
        return $this->_oClickedManufacturer;
    }

    /**
     * Processes manufacturer category URLs
     */
    protected function _seoSetManufacturerData()
    {
        // only when SEO id on and in front end
        if (oxRegistry::getUtils()->seoIsActive() && !$this->isAdmin()) {

            $oEncoder = oxRegistry::get("oxSeoEncoderManufacturer");

            // preparing root manufacturer category
            if ($this->_oRoot) {
                $oEncoder->getManufacturerUrl($this->_oRoot);
            }

            // encoding manufacturer category
            foreach ($this as $sVndId => $value) {
                $oEncoder->getManufacturerUrl($this->_aArray[$sVndId]);
            }
        }
    }
}
