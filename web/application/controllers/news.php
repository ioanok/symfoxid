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
 * Shop news window.
 * Arranges news texts. OXID eShop -> (click on News box on left side).
 */
class News extends oxUBase
{

    /**
     * Newslist
     *
     * @var object
     */
    protected $_oNewsList = null;
    /**
     * Current class login template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/info/news.tpl';

    /**
     * Sign if to load and show bargain action
     *
     * @var bool
     */
    protected $_blBargainAction = true;


    /**
     * Page navigation
     *
     * @var object
     */
    protected $_oPageNavigation = null;

    /**
     * Number of possible pages.
     *
     * @var integer
     */
    protected $_iCntPages = null;

    /**
     * Template variable getter. Returns newslist
     *
     * @return object
     */
    public function getNews()
    {
        if ($this->_oNewsList === null) {
            $this->_oNewsList = false;

            $iPerPage = (int) $this->getConfig()->getConfigParam('iNrofCatArticles');
            $iPerPage = $iPerPage ? $iPerPage : 10;

            $oActNews = oxNew('oxnewslist');

            if ($iCnt = $oActNews->getCount()) {

                $this->_iCntPages = round($iCnt / $iPerPage + 0.49);

                $oActNews->loadNews($this->getActPage() * $iPerPage, $iPerPage);
                $this->_oNewsList = $oActNews;
            }
        }

        return $this->_oNewsList;
    }


    /**
     * Returns Bread Crumb - you are here page1/page2/page3...
     *
     * @return array
     */
    public function getBreadCrumb()
    {
        $aPaths = array();
        $aPath = array();

        $oLang = oxRegistry::getLang();
        $iBaseLanguage = $oLang->getBaseLanguage();
        $sTranslatedString = $oLang->translateString('LATEST_NEWS_AND_UPDATES_AT', $iBaseLanguage, false);

        $aPath['title'] = $sTranslatedString . ' ' . $this->getConfig()->getActiveShop()->oxshops__oxname->value;
        $aPath['link'] = $this->getLink();

        $aPaths[] = $aPath;

        return $aPaths;
    }

    /**
     * Template variable getter. Returns page navigation
     *
     * @return object
     */
    public function getPageNavigation()
    {
        if ($this->_oPageNavigation === null) {
            $this->_oPageNavigation = false;
            $this->_oPageNavigation = $this->generatePageNavigation();
        }

        return $this->_oPageNavigation;
    }

    /**
     * Page title
     *
     * @return string
     */
    public function getTitle()
    {
        $oLang = oxRegistry::getLang();
        $iBaseLanguage = $oLang->getBaseLanguage();
        $sTranslatedString = $oLang->translateString('LATEST_NEWS_AND_UPDATES_AT', $iBaseLanguage, false);

        return $sTranslatedString . ' ' . $this->getConfig()->getActiveShop()->oxshops__oxname->value;
    }
}
