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
 * Order article list manager.
 *
 */
class oxOrderArticleList extends oxList
{

    /**
     * Class constructor, initiates class constructor (parent::oxbase()).
     */
    public function __construct()
    {
        parent::__construct('oxorderarticle');
    }

    /**
     * Copies passed to method product into $this.
     *
     * @param string $sOxId object id
     *
     * @return null
     */
    public function loadOrderArticlesForUser($sOxId)
    {
        if (!$sOxId) {
            $this->clear();

            return;
        }

        $sSelect = "SELECT oxorderarticles.* FROM oxorder ";
        $sSelect .= "left join oxorderarticles on oxorderarticles.oxorderid = oxorder.oxid ";
        $sSelect .= "left join oxarticles on oxorderarticles.oxartid = oxarticles.oxid ";
        $sSelect .= "WHERE oxorder.oxuserid = " . oxDb::getDb()->quote($sOxId);

        $this->selectString($sSelect);

    }
}
