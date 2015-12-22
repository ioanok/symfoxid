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
 * Managing Gift Wrapping
 */
class Wrapping extends oxUBase
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/checkout/wrapping.tpl';

    /**
     * Basket items array
     *
     * @var array
     */
    protected $_aBasketItemList = null;

    /**
     * Wrapping objects list
     *
     * @var oxlist
     */
    protected $_oWrappings = null;

    /**
     * Card objects list
     *
     * @var oxlist
     */
    protected $_oCards = null;


    /**
     * Returns array of shopping basket articles
     *
     * @return array
     */
    public function getBasketItems()
    {
        if ($this->_aBasketItemList === null) {
            $this->_aBasketItemList = false;

            // passing basket articles
            if ($oBasket = $this->getSession()->getBasket()) {
                $this->_aBasketItemList = $oBasket->getBasketArticles();
            }
        }

        return $this->_aBasketItemList;
    }

    /**
     * Return basket wrappings list if available
     *
     * @return oxlist
     */
    public function getWrappingList()
    {
        if ($this->_oWrappings === null) {
            $this->_oWrappings = new oxlist();

            // load wrapping papers
            if ($this->getViewConfig()->getShowGiftWrapping()) {
                $this->_oWrappings = oxNew('oxwrapping')->getWrappingList('WRAP');
            }
        }

        return $this->_oWrappings;
    }

    /**
     * Returns greeting cards list if available
     *
     * @return oxlist
     */
    public function getCardList()
    {
        if ($this->_oCards === null) {
            $this->_oCards = new oxlist();

            // load gift cards
            if ($this->getViewConfig()->getShowGiftWrapping()) {
                $this->_oCards = oxNew('oxwrapping')->getWrappingList('CARD');
            }
        }

        return $this->_oCards;
    }

    /**
     * Updates wrapping data in session basket object
     * (oxsession::getBasket()) - adds wrapping info to
     * each article in basket (if possible). Plus adds
     * gift message and chosen card ( takes from GET/POST/session;
     * oBasket::giftmessage, oBasket::chosencard). Then sets
     * basket back to session (oxsession::setBasket()). Returns
     * "order" to redirect to order confirmation secreen.
     *
     * @return string
     */
    public function changeWrapping()
    {
        $aWrapping = oxRegistry::getConfig()->getRequestParameter('wrapping');

        if ($this->getViewConfig()->getShowGiftWrapping()) {
            $oBasket = $this->getSession()->getBasket();
            // setting wrapping info
            if (is_array($aWrapping) && count($aWrapping)) {
                foreach ($oBasket->getContents() as $sKey => $oBasketItem) {
                    // wrapping ?
                    if (isset($aWrapping[$sKey])) {
                        $oBasketItem->setWrapping($aWrapping[$sKey]);
                    }
                }
            }

            $oBasket->setCardMessage(oxRegistry::getConfig()->getRequestParameter('giftmessage'));
            $oBasket->setCardId(oxRegistry::getConfig()->getRequestParameter('chosencard'));
            $oBasket->onUpdate();

        }

        return 'order';
    }
}
