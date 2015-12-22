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
 * Article amount price list
 *
 */
class oxAmountPriceList extends oxList
{

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'oxprice2article';

    /**
     * oxArticle object
     *
     * @var oxArticle
     */
    protected $_oArticle = null;

    /**
     *  Article getter
     *
     * @return oxArticle $_oArticle
     */
    public function getArticle()
    {
        return $this->_oArticle;
    }

    /**
     * Article setter
     *
     * @param oxArticle $oArticle Article
     */
    public function setArticle($oArticle)
    {
        $this->_oArticle = $oArticle;
    }

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct('oxbase');
        $this->init('oxbase', 'oxprice2article');
    }

    /**
     * Get data from db
     *
     * @return array
     */
    protected function _loadFromDb()
    {
        $sArticleId = $this->getArticle()->getId();

        if (!$this->isAdmin() && $this->getConfig()->getConfigParam('blVariantInheritAmountPrice') && $this->getArticle()->getParentId()) {
            $sArticleId = $this->getArticle()->getParentId();
        }

        if ($this->getConfig()->getConfigParam('blMallInterchangeArticles')) {
            $sShopSelect = '1';
        } else {
            $sShopSelect = " `oxshopid` = " . oxDb::getDb()->quote($this->getConfig()->getShopId()) . " ";
        }

        $sSql = "SELECT * FROM `oxprice2article` WHERE `oxartid` = " . oxDb::getDb()->quote($sArticleId) . " AND $sShopSelect ORDER BY `oxamount` ";

        $aData = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->getAll($sSql);

        return $aData;
    }


    /**
     * Load category list data
     *
     * @param oxArticle $oArticle Article
     */
    public function load($oArticle)
    {
        $this->setArticle($oArticle);


        $aData = $this->_loadFromDb();

        $this->assignArray($aData);
    }
}
