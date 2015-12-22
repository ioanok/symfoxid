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
 * Article images gallery popup window.
 * If chosen article has more pictures there is ability to create
 * gallery of pictures.
 */
class MoreDetails extends Details
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'moredetails.tpl';

    /**
     * Current article id
     *
     * @var string
     */
    protected $_sProductId = null;

    /**
     * Active picture id
     *
     * @var string
     */
    protected $_sActPicId = null;

    /**
     * Article zoom pictures
     *
     * @var array
     */
    protected $_aArtZoomPics = null;

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * Template variable getter. Returns current product id
     *
     * @return string
     */
    public function getProductId()
    {
        if ($this->_sProductId === null) {
            $this->_sProductId = $this->getProduct()->getId();
        }

        return $this->_sProductId;
    }

    /**
     * Template variable getter. Returns active picture id
     *
     * @return string
     */
    public function getActPictureId()
    {
        if ($this->_sActPicId === null) {
            $this->_sActPicId = false;
            $aPicGallery = $this->getProduct()->getPictureGallery();

            if ($aPicGallery['ZoomPic']) {
                $sActPicId = oxRegistry::getConfig()->getRequestParameter('actpicid');
                $this->_sActPicId = $sActPicId ? $sActPicId : 1;
            }
        }

        return $this->_sActPicId;
    }

    /**
     * Template variable getter. Returns article zoom pictures
     *
     * @return array
     */
    public function getArtZoomPics()
    {
        if ($this->_aArtZoomPics === null) {
            $this->_aArtZoomPics = false;
            //Get picture gallery
            $aPicGallery = $this->getProduct()->getPictureGallery();
            $blArtPic = $aPicGallery['ZoomPic'];
            $aArtPics = $aPicGallery['ZoomPics'];

            if ($blArtPic) {
                $this->_aArtZoomPics = $aArtPics;
            }
        }

        return $this->_aArtZoomPics;
    }

    /**
     * Template variable getter. Returns active product
     *
     * @return oxArticle
     */
    public function getProduct()
    {
        if ($this->_oProduct === null) {
            $oArticle = oxNew('oxArticle');
            $oArticle->load(oxRegistry::getConfig()->getRequestParameter('anid'));
            $this->_oProduct = $oArticle;
        }

        return $this->_oProduct;
    }
}
