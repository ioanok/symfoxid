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
 * Recomendation list.
 * Forms recomendation list.
 */
class oxwServiceMenu extends oxWidget
{

    /**
     * Names of components (classes) that are initiated and executed
     * before any other regular operation.
     * User component used in template.
     *
     * @var array
     */
    protected $_aComponentNames = array('oxcmp_user' => 1);

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/header/servicemenu.tpl';

    /**
     * Template variable getter. Returns article list count in comparison.
     *
     * @return integer
     */
    public function getCompareItemsCnt()
    {
        $oCompare = oxNew("compare");
        $iCompItemsCnt = $oCompare->getCompareItemsCnt();

        return $iCompItemsCnt;
    }

    /**
     * Template variable getter. Returns comparison article list.
     *
     * @param bool $blJson return json encoded array
     *
     * @return array
     */
    public function getCompareItems($blJson = false)
    {
        $oCompare = oxNew("compare");
        $aCompareItems = $oCompare->getCompareItems();

        if ($blJson) {
            $aCompareItems = json_encode($aCompareItems);
        }

        return $aCompareItems;
    }

}
