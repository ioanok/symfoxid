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
 * Shop RSS page.
 */
class Rss extends oxUBase
{

    /**
     * current rss object
     *
     * @var oxRssFeed
     */
    protected $_oRss = null;

    /**
     * Current rss channel
     *
     * @var object
     */
    protected $_oChannel = null;

    /**
     * Xml start and end definition
     *
     * @var array
     */
    protected $_aXmlDef = null;


    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/rss.tpl';

    /**
     * get oxRssFeed
     *
     * @return oxRssFeed
     */
    protected function _getRssFeed()
    {
        if (!$this->_oRss) {
            $this->_oRss = oxNew('oxRssFeed');
        }

        return $this->_oRss;
    }

    /**
     * Renders requested RSS feed
     *
     * Template variables:
     * <b>rss</b>
     */
    public function render()
    {
        parent::render();

        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();

        // #2873: In demoshop for RSS we set php_handling to SMARTY_PHP_PASSTHRU
        // as SMARTY_PHP_REMOVE removes not only php tags, but also xml
        if ($this->getConfig()->isDemoShop()) {
            $oSmarty->php_handling = SMARTY_PHP_PASSTHRU;
        }

        foreach (array_keys($this->_aViewData) as $sViewName) {
            $oSmarty->assign_by_ref($sViewName, $this->_aViewData[$sViewName]);
        }

        // return rss xml, no further processing
        $sCharset = oxRegistry::getLang()->translateString("charset");
        oxRegistry::getUtils()->setHeader("Content-Type: text/xml; charset=" . $sCharset);
        oxRegistry::getUtils()->showMessageAndExit(
            $this->_processOutput(
                $oSmarty->fetch($this->_sThisTemplate, $this->getViewId())
            )
        );
    }

    /**
     * Processes xml before outputting to user
     *
     * @param string $sInput input to process
     *
     * @return string
     */
    protected function _processOutput($sInput)
    {
        return getStr()->recodeEntities($sInput);
    }

    /**
     * getTopShop loads top shop articles to rss
     *
     * @access public
     */
    public function topshop()
    {
        if ($this->getConfig()->getConfigParam('bl_rssTopShop')) {
            $this->_getRssFeed()->loadTopInShop();
        } else {
            error_404_handler();
        }
    }

    /**
     * loads newest shop articles
     *
     * @access public
     */
    public function newarts()
    {
        if ($this->getConfig()->getConfigParam('bl_rssNewest')) {
            $this->_getRssFeed()->loadNewestArticles();
        } else {
            error_404_handler();
        }
    }

    /**
     * loads category articles
     *
     * @access public
     */
    public function catarts()
    {
        if ($this->getConfig()->getConfigParam('bl_rssCategories')) {
            $oCat = oxNew('oxCategory');
            if ($oCat->load(oxRegistry::getConfig()->getRequestParameter('cat'))) {
                $this->_getRssFeed()->loadCategoryArticles($oCat);
            }
        } else {
            error_404_handler();
        }
    }

    /**
     * loads search articles
     *
     * @access public
     */
    public function searcharts()
    {
        if ($this->getConfig()->getConfigParam('bl_rssSearch')) {
            $sSearchParameter = oxRegistry::getConfig()->getRequestParameter('searchparam', true);
            $sCatId = oxRegistry::getConfig()->getRequestParameter('searchcnid');
            $sVendorId = oxRegistry::getConfig()->getRequestParameter('searchvendor');
            $sManufacturerId = oxRegistry::getConfig()->getRequestParameter('searchmanufacturer');

            $this->_getRssFeed()->loadSearchArticles($sSearchParameter, $sCatId, $sVendorId, $sManufacturerId);
        } else {
            error_404_handler();
        }
    }

    /**
     * loads recommendation lists
     *
     * @access public
     * @return void
     */
    public function recommlists()
    {
        if ($this->getViewConfig()->getShowListmania() && $this->getConfig()->getConfigParam('bl_rssRecommLists')) {
            $oArticle = oxNew('oxarticle');
            if ($oArticle->load(oxRegistry::getConfig()->getRequestParameter('anid'))) {
                $this->_getRssFeed()->loadRecommLists($oArticle);

                return;
            }
        }
        error_404_handler();
    }

    /**
     * loads recommendation list articles
     *
     * @access public
     * @return void
     */
    public function recommlistarts()
    {
        if ($this->getConfig()->getConfigParam('bl_rssRecommListArts')) {
            $oRecommList = oxNew('oxrecommlist');
            if ($oRecommList->load(oxRegistry::getConfig()->getRequestParameter('recommid'))) {
                $this->_getRssFeed()->loadRecommListArticles($oRecommList);

                return;
            }
        }
        error_404_handler();
    }

    /**
     * getBargain loads top shop articles to rss
     *
     * @access public
     */
    public function bargain()
    {
        if ($this->getConfig()->getConfigParam('bl_rssBargain')) {
            $this->_getRssFeed()->loadBargain();
        } else {
            error_404_handler();
        }
    }

    /**
     * Template variable getter. Returns rss channel
     *
     * @return object
     */
    public function getChannel()
    {
        if ($this->_oChannel === null) {
            $this->_oChannel = $this->_getRssFeed()->getChannel();
        }

        return $this->_oChannel;
    }

    /**
     * Returns if view should be cached
     *
     * @return bool
     */
    public function getCacheLifeTime()
    {
        return $this->_getRssFeed()->getCacheTtl();
    }
}
