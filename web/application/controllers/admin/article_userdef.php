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
 * Class reserved for extending (for customization - you can add you own fields, etc.).
 */
class Article_Userdef extends oxAdminDetails
{

    /**
     * Loads article data from DB, passes it to Smarty engine, returns name
     * of template file "article_userdef.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oArticle = oxNew("oxarticle");
        $this->_aViewData["edit"] = $oArticle;

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {

            // load object
            $oArticle->load($soxId);
        }

        return "article_userdef.tpl";
    }
}
