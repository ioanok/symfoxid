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
 * News list manager, loads some news informetion.
 *
 * @subpackage oxcmp
 */
class oxcmp_news extends oxView
{

    /**
     * Marking object as component
     *
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Executes parent::render() and loads news list. Returns current
     * news array element (if user in admin sets to show more than 1
     * item in news box - will return whole array).
     *
     * @return array $oActNews a List of news, or null if not configured to load news
     */
    public function render()
    {
        parent::render();

        $myConfig = $this->getConfig();
        $oActView = $myConfig->getActiveView();

        // news loading is disabled
        if (!$myConfig->getConfigParam('bl_perfLoadNews') ||
            ($myConfig->getConfigParam('blDisableNavBars') &&
             $oActView->getIsOrderStep())
        ) {
            return;
        }

        // if news must be displayed only on start page ?
        if ($myConfig->getConfigParam('bl_perfLoadNewsOnlyStart') &&
            $oActView->getClassName() != "start"
        ) {
            return;
        }

        $iNewsToLoad = $myConfig->getConfigParam('sCntOfNewsLoaded');
        $iNewsToLoad = $iNewsToLoad ? $iNewsToLoad : 1;

        $oActNews = oxNew('oxnewslist');
        $oActNews->loadNews(0, $iNewsToLoad);

        return $oActNews;
    }
}
