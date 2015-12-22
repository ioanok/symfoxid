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
 */

/**
 * Basket component
 */
class oePayPalOxcmp_Basket extends oePayPalOxcmp_Basket_parent
{
    /**
     * Show ECS PopUp
     *
     * @var bool
     */
    protected $_blShopPopUp = false;

    /**
     * Method returns URL to checkout products OR to show popup.
     *
     * @return string
     */
    public function actionExpressCheckoutFromDetailsPage()
    {
        $oValidator = $this->_getValidator();
        $oCurrentArticle = $this->_getCurrentArticle();
        $oValidator->setItemToValidate($oCurrentArticle);
        $oValidator->setBasket($this->getSession()->getBasket());
        if ($oValidator->isArticleValid()) {
            //Make express checkout
            $sRes = $this->actionAddToBasketAndGoToCheckout();
        } else {
            $sRes = $this->_getRedirectUrl();
            //if amount is more than 0, do not redirect, show ESC popup instead
            if ($oCurrentArticle->getArticleAmount() > 0) {
                $this->_blShopPopUp = true;
                $sRes = null;
            }
        }

        return $sRes;
    }

    /**
     * Returns whether ECS popup should be shown
     *
     * @return bool
     */
    public function shopECSPopUp()
    {
        return $this->_blShopPopUp;
    }

    /**
     * Action method to add product to basket and return checkout URL.
     *
     * @return string
     */
    public function actionAddToBasketAndGoToCheckout()
    {
        parent::tobasket();

        return $this->_getExpressCheckoutUrl();
    }

    /**
     * Action method to return checkout URL.
     *
     * @return string
     */
    public function actionNotAddToBasketAndGoToCheckout()
    {
        return $this->_getExpressCheckoutUrl();
    }

    /**
     * Returns express checkout URL
     *
     * @return string
     */
    protected function _getExpressCheckoutUrl()
    {
        return 'oePayPalExpressCheckoutDispatcher&fnc=setExpressCheckout&displayCartInPayPal=' . (int) $this->_getRequest()->getPostParameter('displayCartInPayPal') . '&oePayPalCancelURL=' . $this->getPayPalCancelURL();
    }

    /**
     * Method returns serialized current article params.
     *
     * @return string
     */
    public function getCurrentArticleInfo()
    {
        $aProducts = $this->_getItems();
        $sCurrentArticleId = $this->getConfig()->getRequestParameter('aid');
        $aParams = null;
        if (!is_null($aProducts[$sCurrentArticleId])) {
            $aParams = $aProducts[$sCurrentArticleId];
        }

        return $aParams;
    }

    /**
     * Method sets params for article and returns it's object.
     *
     * @return oePayPalArticleToExpressCheckoutCurrentItem
     */
    protected function _getCurrentArticle()
    {
        $oCurrentItem = oxNew('oePayPalArticleToExpressCheckoutCurrentItem');
        $sCurrentArticleId = $this->_getRequest()->getPostParameter('aid');
        $aProducts = $this->_getItems();
        $aProductInfo = $aProducts[$sCurrentArticleId];
        $oCurrentItem->setArticleId($sCurrentArticleId);
        $oCurrentItem->setSelectList($aProductInfo['sel']);
        $oCurrentItem->setPersistParam($aProductInfo['persparam']);
        $oCurrentItem->setArticleAmount($aProductInfo['am']);

        return $oCurrentItem;
    }

    /**
     * Method returns request object.
     *
     * @return oePayPalRequest
     */
    protected function _getRequest()
    {
        return oxNew('oePayPalRequest');
    }

    /**
     * Method sets params for validator and returns it's object.
     *
     * @return oePayPalArticleToExpressCheckoutValidator
     */
    protected function _getValidator()
    {
        $oValidator = oxNew('oePayPalArticleToExpressCheckoutValidator');

        return $oValidator;
    }

    /**
     * Changes oePayPalCancelURL by changing popup showing parameter.
     *
     * @return string
     */
    public function getPayPalCancelURL()
    {
        $sUrl = $this->_formatUrl($this->_getRedirectUrl());
        $sReplacedURL = str_replace('showECSPopup=1', 'showECSPopup=0', $sUrl);

        return urlencode($sReplacedURL);
    }

    /**
     * Formats Redirect URL to normal url
     *
     * @param string $sUnformedUrl
     *
     * @return string
     */
    protected function _formatUrl($sUnformedUrl)
    {
        $myConfig = $this->getConfig();
        $aParams = explode('?', $sUnformedUrl);
        $sPageParams = isset($aParams[1]) ? $aParams[1] : null;
        $aParams = explode('/', $aParams[0]);
        $sClassName = $aParams[0];

        $sHeader = ($sClassName) ? "cl=$sClassName&" : '';  // adding view name
        $sHeader .= ($sPageParams) ? "$sPageParams&" : '';   // adding page params
        $sHeader .= $this->getSession()->sid();            // adding session Id

        $sUrl = $myConfig->getCurrentShopUrl($this->isAdmin());

        $sUrl = "{$sUrl}index.php?{$sHeader}";

        $sUrl = oxRegistry::get("oxUtilsUrl")->processUrl($sUrl);

        if (oxRegistry::getUtils()->seoIsActive() && $sSeoUrl = oxRegistry::get("oxSeoEncoder")->getStaticUrl($sUrl)) {
            $sUrl = $sSeoUrl;
        }

        return $sUrl;
    }
}
