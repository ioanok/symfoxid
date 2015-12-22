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
 * Current user order history review.
 * When user is logged in order review fulfils history about user
 * submitted orders. There is some details information, such as
 * ordering date, number, recipient, order status, some base
 * ordered articles information, button to add article to basket.
 * OXID eShop -> MY ACCOUNT -> Newsletter.
 */
class Account_Order extends Account
{

    /**
     * Count of all articles in list.
     *
     * @var integer
     */
    protected $_iAllArtCnt = 0;

    /**
     * Number of possible pages.
     *
     * @var integer
     */
    protected $_iCntPages = null;

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/account/order.tpl';

    /**
     * collecting orders
     *
     * @var array
     */
    protected $_aOrderList = null;

    /**
     * collecting article which ordered
     *
     * @var array
     */
    protected $_aArticlesList = null;

    /**
     * If user is not logged in - returns name of template account_order::_sThisLoginTemplate,
     * or if user is allready logged in - returns name of template
     * account_order::_sThisTemplate
     *
     * @return string $_sThisTemplate current template file name
     */
    public function render()
    {
        parent::render();

        // is logged in ?
        $oUser = $this->getUser();
        if (!$oUser) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        return $this->_sThisTemplate;
    }

    /**
     * Template variable getter. Returns orders
     *
     * @return array
     */
    public function getOrderList()
    {
        if ($this->_aOrderList === null) {
            $this->_aOrderList = array();

            // Load user Orderlist
            if ($oUser = $this->getUser()) {
                $iNrofCatArticles = (int) $this->getConfig()->getConfigParam('iNrofCatArticles');
                $iNrofCatArticles = $iNrofCatArticles ? $iNrofCatArticles : 1;
                $this->_iAllArtCnt = $oUser->getOrderCount();
                if ($this->_iAllArtCnt && $this->_iAllArtCnt > 0) {
                    $this->_aOrderList = $oUser->getOrders($iNrofCatArticles, $this->getActPage());
                    $this->_iCntPages = round($this->_iAllArtCnt / $iNrofCatArticles + 0.49);
                }
            }
        }

        return $this->_aOrderList;
    }

    /**
     * Template variable getter. Returns ordered articles
     *
     * @return oxarticlelist | false
     */
    public function getOrderArticleList()
    {
        if ($this->_aArticlesList === null) {

            // marking as set
            $this->_aArticlesList = false;
            $oOrdersList = $this->getOrderList();
            if ($oOrdersList && $oOrdersList->count()) {
                $this->_aArticlesList = oxNew('oxarticlelist');
                $this->_aArticlesList->loadOrderArticles($oOrdersList);
            }
        }

        return $this->_aArticlesList;
    }

    /**
     * Template variable getter. Returns page navigation
     *
     * @return object
     */
    public function getPageNavigation()
    {
        if ($this->_oPageNavigation === null) {
            $this->_oPageNavigation = $this->generatePageNavigation();
        }

        return $this->_oPageNavigation;
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
        $iBaseLanguage = oxRegistry::getLang()->getBaseLanguage();
        $sSelfLink = $this->getViewConfig()->getSelfLink();

        $aPath['title'] = oxRegistry::getLang()->translateString('MY_ACCOUNT', $iBaseLanguage, false);
        $aPath['link'] = oxRegistry::get("oxSeoEncoder")->getStaticUrl($sSelfLink . 'cl=account');
        $aPaths[] = $aPath;

        $aPath['title'] = oxRegistry::getLang()->translateString('ORDER_HISTORY', $iBaseLanguage, false);
        $aPath['link'] = $this->getLink();
        $aPaths[] = $aPath;

        return $aPaths;
    }
}
