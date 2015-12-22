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
 * User list manager.
 *
 */
class oxUserList extends oxList
{

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct('oxuser');
    }


    /**
     * Load searched user list with wishlist
     *
     * @param string $sSearchStr Search string
     *
     * @return null;
     */
    public function loadWishlistUsers($sSearchStr)
    {
        $sSearchStr = oxDb::getInstance()->escapeString($sSearchStr);
        if (!$sSearchStr) {
            return;
        }

        $sSelect = "select oxuser.oxid, oxuser.oxfname, oxuser.oxlname from oxuser ";
        $sSelect .= "left join oxuserbaskets on oxuserbaskets.oxuserid = oxuser.oxid ";
        $sSelect .= "where oxuserbaskets.oxid is not null and oxuserbaskets.oxtitle = 'wishlist' ";
        $sSelect .= "and oxuserbaskets.oxpublic = 1 ";
        $sSelect .= "and ( oxuser.oxusername like '%$sSearchStr%' or oxuser.oxlname like '%$sSearchStr%')";
        $sSelect .= "and ( select 1 from oxuserbasketitems where oxuserbasketitems.oxbasketid = oxuserbaskets.oxid limit 1)";

        $this->selectString($sSelect);
    }
}
