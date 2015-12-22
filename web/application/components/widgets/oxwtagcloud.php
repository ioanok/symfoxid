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
 * Tag cloud.
 * Shop starter, manages starting visible articles, etc.
 */
class oxwTagCloud extends oxWidget
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/sidebar/tags.tpl';

    /**
     * Checks if tags list should be displayed in separate box
     *
     * @return bool
     */
    public function displayInBox()
    {
        return (bool) $this->getViewParameter("blShowBox");
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

        return $oTagCloud;
    }

    /**
     * Template variable getter. Returns true
     *
     * @return bool
     */
    public function isMoreTagsVisible()
    {
        return true;
    }
}
