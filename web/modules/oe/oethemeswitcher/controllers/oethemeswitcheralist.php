<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.

 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 */

/**
 * List of articles for a selected product group.
 * Collects list of articles, according to it generates links for list gallery,
 * meta tags (for search engines). Result - "list.tpl" template.
 * OXID eShop -> (Any selected shop product category).
 */
class oeThemeSwitcherAList extends oeThemeSwitcherAList_parent
{
    /**
     * If filter should be displayed
     *
     * @var bool
     */
    protected $_blShowFilter = null;

    /**
     * Check if filter was selected
     *
     * @return bool
     */
    public function getShowFilter()
    {
        if ($this->_blShowFilter == null) {
            $this->_blShowFilter = false;
            if ($this->getConfig()->getRequestParameter('showFilter') == 'true') {
                $this->_blShowFilter = true;
            }
        }

        return $this->_blShowFilter;
    }

    /**
     * Returns view ID (for template engine caching).
     *
     * @return string   $this->_sViewId view id
     */
    public function getViewId()
    {
        $sViewId = parent::getViewId();
        $sViewId .= $this->getConfig()->oeThemeSwitcherGetActiveThemeId();

        return $sViewId;
    }
}
