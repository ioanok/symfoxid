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
 * Admin user articles setting manager.
 * Collects user articles settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> Articles.
 */
class User_Article extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxlist object and returns name
     * of template file "user_article.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ($soxId && $soxId != '-1') {
            // load object
            $oArticlelist = oxNew('oxorderarticlelist');
            $oArticlelist->loadOrderArticlesForUser($soxId);

            $this->_aViewData['oArticlelist'] = $oArticlelist;
        }

        return 'user_article.tpl';
    }
}
