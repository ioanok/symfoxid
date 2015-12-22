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
 * Account article file download page.
 *
 */
class Account_Downloads extends Account
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/account/downloads.tpl';

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * @var oxOrderFileList
     */
    protected $_oOrderFilesList = null;


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
        /** @var oxSeoEncoder $oSeoEncoder */
        $oSeoEncoder = oxRegistry::get("oxSeoEncoder");
        $aPath['title'] = oxRegistry::getLang()->translateString('MY_ACCOUNT', $iBaseLanguage, false);
        $aPath['link'] = $oSeoEncoder->getStaticUrl($this->getViewConfig()->getSelfLink() . "cl=account");
        $aPaths[] = $aPath;

        $aPath['title'] = oxRegistry::getLang()->translateString('MY_DOWNLOADS', $iBaseLanguage, false);
        $aPath['link'] = $this->getLink();
        $aPaths[] = $aPath;

        return $aPaths;
    }

    /**
     * Returns article list which was ordered and has downloadable files
     *
     * @return null|oxArticleList
     */
    public function getOrderFilesList()
    {
        if ($this->_oOrderFilesList !== null) {
            return $this->_oOrderFilesList;
        }

        $oOrderFileList = oxNew('oxOrderFileList');
        $oOrderFileList->loadUserFiles($this->getUser()->getId());

        $this->_oOrderFilesList = $this->_prepareForTemplate($oOrderFileList);

        return $this->_oOrderFilesList;
    }

    /**
     * Returns prepared orders files list
     *
     * @param oxorderfilelist $oOrderFileList - list or orderfiles
     *
     * @return array
     */
    protected function _prepareForTemplate($oOrderFileList)
    {
        $oOrderArticles = array();

        foreach ($oOrderFileList as $oOrderFile) {
            $sOrderArticleIdField = 'oxorderfiles__oxorderarticleid';
            $sOrderNumberField = 'oxorderfiles__oxordernr';
            $sOrderDateField = 'oxorderfiles__oxorderdate';
            $sOrderTitleField = 'oxorderfiles__oxarticletitle';
            $sOrderArticleId = $oOrderFile->$sOrderArticleIdField->value;
            $oOrderArticles[$sOrderArticleId]['oxordernr'] = $oOrderFile->$sOrderNumberField->value;
            $oOrderArticles[$sOrderArticleId]['oxorderdate'] = substr($oOrderFile->$sOrderDateField->value, 0, 16);
            $oOrderArticles[$sOrderArticleId]['oxarticletitle'] = $oOrderFile->$sOrderTitleField->value;
            $oOrderArticles[$sOrderArticleId]['oxorderfiles'][] = $oOrderFile;
        }

        return $oOrderArticles;
    }

    /**
     * Returns error code.
     *
     * @return int
     */
    public function getDownloadError()
    {
        return $this->getConfig()->getRequestParameter('download_error');
    }
}
