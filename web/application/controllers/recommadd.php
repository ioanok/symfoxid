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
 * Handles adding article to recommendation list process.
 * Due to possibility of external modules we recommned to extend the vews from oxUBase view.
 * However expreimentally we extend RecommAdd from Details view here.
 */
class RecommAdd extends Details
{

    /**
     * Template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/account/recommendationadd.tpl';

    /**
     * User recommendation lists
     *
     * @var array
     */
    protected $_aUserRecommList = null;

    /**
     * Renders the view
     *
     * @return unknown
     */
    public function render()
    {
        oxUBase::render();

        return $this->_sThisTemplate;
    }

    /**
     * Returns user recommlists
     *
     * @return array
     */
    public function getRecommLists()
    {
        if ($this->_aUserRecommList === null) {
            $oUser = $this->getUser();
            if ($oUser) {
                $this->_aUserRecommList = $oUser->getUserRecommLists();
            }
        }

        return $this->_aUserRecommList;
    }

    /**
     * Returns the title of the product added to the recommendation list.
     *
     * @return string
     */
    public function getTitle()
    {
        $oProduct = $this->getProduct();

        return $oProduct->oxarticles__oxtitle->value . ' ' . $oProduct->oxarticles__oxvarselect->value;
    }
}
