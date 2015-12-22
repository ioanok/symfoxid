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

if (!defined('OXTAGCLOUD_MINFONT')) {
    define('OXTAGCLOUD_MINTAGLENGTH', 4);
    define('OXTAGCLOUD_STARTPAGECOUNT', 20);
    define('OXTAGCLOUD_EXTENDEDCOUNT', 200);
}
/**
 * Class dedicated to article tags handling.
 * Is responsible for saving, returning and adding tags for given article.
 *
 */
class oxTagList extends oxI18n implements oxITagList
{

    /**
     * Tags array.
     *
     * @var array
     */
    protected $_oTagSet = null;

    /**
     * Extended mode
     *
     * @var bool
     */
    protected $_blExtended = false;

    /**
     * Instantiates oxtagset object
     */
    public function __construct()
    {
        parent::__construct();
        $this->_oTagSet = oxNew('oxtagset');
    }

    /**
     * Returns cache id
     *
     * @return string
     */
    public function getCacheId()
    {
        return 'tag_list_' . $this->getLanguage();
    }

    /**
     * Loads all articles tags list.
     *
     * @return bool
     */
    public function loadList()
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $iLang = $this->getLanguage();

        $sArtView = getViewName('oxarticles', $iLang);
        $sViewName = getViewName('oxartextends', $iLang);

        // check if article is still active
        $oArticle = oxNew('oxarticle');
        $oArticle->setLanguage($iLang);
        $sArtActive = $oArticle->getActiveCheckQuery(true);

        $sQ = "SELECT {$sViewName}.`oxtags` AS `oxtags`
            FROM {$sArtView} AS `oxarticles`
                LEFT JOIN {$sViewName} ON `oxarticles`.`oxid` = {$sViewName}.`oxid`
            WHERE `oxarticles`.`oxactive` = 1 AND $sArtActive";

        $oDb->setFetchMode(oxDb::FETCH_MODE_ASSOC);
        $oRs = $oDb->select($sQ);

        $this->get()->clear();
        while ($oRs && $oRs->recordCount() && !$oRs->EOF) {
            $this->_addTagsFromDb($oRs->fields['oxtags']);
            $oRs->moveNext();
        }

        return $this->_isLoaded = true;
    }

    /**
     * Returns oxTagSet list
     *
     * @return oxTagSet
     */
    public function get()
    {
        return $this->_oTagSet;
    }

    /**
     * Adds tag to list
     *
     * @param string $mTag tag as string or as oxTag object
     */
    public function addTag($mTag)
    {
        $this->_oTagSet->addTag($mTag);
    }

    /**
     * Adds record from database to tagset
     *
     * @param string $sTags tags string to add
     *
     * @return void
     */
    protected function _addTagsFromDb($sTags)
    {
        if (empty($sTags)) {
            return;
        }
        $sSeparator = $this->get()->getSeparator();
        $aTags = explode($sSeparator, $sTags);
        foreach ($aTags as $sTag) {
            $oTag = oxNew("oxtag");
            $oTag->set($sTag, false);
            $oTag->removeUnderscores();
            $this->addTag($oTag);
        }
    }
}
