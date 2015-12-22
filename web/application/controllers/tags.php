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
 * Shows bigger tag cloud
 */
class Tags extends oxUBase
{

    /**
     * Class template
     *
     * @var string
     */
    protected $_sThisTemplate = "page/tags/tags.tpl";

    /**
     * If tags are ON - returns parent::render() value, else - displays 404
     * page, as tags are off
     *
     * @return string
     */
    public function render()
    {
        // if tags are off - showing 404 page
        if (!$this->showTags()) {
            error_404_handler();
        }

        return parent::render();
    }

    /**
     * Returns tag cloud manager class
     *
     * @return oxTagCloud
     */
    public function getTagCloudManager()
    {
        $oTagList = oxNew("oxTagList");
        //$oTagList->loadList();
        $oTagCloud = oxNew("oxTagCloud");
        $oTagCloud->setTagList($oTagList);
        $oTagCloud->setExtendedMode(true);

        return $oTagCloud;
    }

    /**
     * Returns SEO suffix for page title
     */
    public function getTitleSuffix()
    {
    }

    /**
     * Returns title page suffix used in template
     *
     * @return string
     */
    public function getTitlePageSuffix()
    {
        if (($iPage = $this->getActPage())) {
            return oxRegistry::getLang()->translateString('PAGE') . " " . ($iPage + 1);
        }
    }

    /**
     * Returns Bread Crumb - you are here page1/page2/page3...
     *
     * @return array
     */
    public function getBreadCrumb()
    {
        $aPaths = array();
        $aCatPath = array();

        $iBaseLanguage = oxRegistry::getLang()->getBaseLanguage();
        $aCatPath['title'] = oxRegistry::getLang()->translateString('TAGS', $iBaseLanguage, false);
        $aCatPath['link'] = $this->getLink();
        $aPaths[] = $aCatPath;

        return $aPaths;
    }
}
