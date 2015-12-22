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
 * Article box widget
 */
class oePayPalOxwArticleDetails extends oePayPalOxwArticleDetails_parent
{
    /**
     * Returns products amount to .tpl pages.
     *
     * @return int
     */
    public function oePayPalGetArticleAmount()
    {
        $aArticle = $this->_oePayPalGetECSArticle();

        return isset($aArticle['am']) ? (int) $aArticle['am'] : 1;
    }

    /**
     * Returns persistent parameter.
     *
     * @return string
     */
    public function oePayPalGetPersistentParam()
    {
        $aArticle = $this->_oePayPalGetECSArticle();

        return $aArticle['persparam']['details'];
    }

    /**
     * Returns selections array.
     *
     * @return array
     */
    public function oePayPalGetSelection()
    {
        $aArticle = $this->_oePayPalGetECSArticle();

        return $aArticle['sel'];
    }

    /**
     * Checks if showECSPopup parameter was passed.
     *
     * @return bool
     */
    public function oePayPalShowECSPopup()
    {
        return $this->getComponent('oxcmp_basket')->shopECSPopUp();
    }

    /**
     * Checks if showECSPopup parameter was passed.
     *
     * @return bool
     */
    public function oePayPalGetCancelUrl()
    {
        return $this->getComponent('oxcmp_basket')->getPayPalCancelURL();
    }

    /**
     * Checks if displayCartInPayPal parameter was passed.
     *
     * @return bool
     */
    public function oePayPalDisplayCartInPayPal()
    {
        $blDisplayCartInPayPal = false;
        if ($this->_oePayPalGetRequest()->getPostParameter('displayCartInPayPal')) {
            $blDisplayCartInPayPal = true;
        }

        return $blDisplayCartInPayPal;
    }

    /**
     * Method returns request object.
     *
     * @return oePayPalRequest
     */
    protected function _oePayPalGetRequest()
    {
        return oxNew('oePayPalRequest');
    }

    /**
     * Gets ECSArticle, unserializes and returns it.
     *
     * @return array
     */
    protected function _oePayPalGetECSArticle()
    {
        $aProducts = $this->getComponent('oxcmp_basket')->getCurrentArticleInfo();

        return $aProducts;
    }
}
