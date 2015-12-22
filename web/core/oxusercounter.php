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
 * Class used for counting users depending on given attributes.
 */
class oxUserCounter
{

    /**
     * Returns count of admins (mall and subshops). Only counts active admins.
     *
     * @return int
     */
    public function getAdminCount()
    {
        $oDb = oxDb::getDb();

        $sQuery = "SELECT COUNT(1) FROM oxuser WHERE oxrights != 'user'";

        return (int) $oDb->getOne($sQuery);
    }

    /**
     * Returns count of admins (mall and subshops). Only counts active admins.
     *
     * @return int
     */
    public function getActiveAdminCount()
    {
        $oDb = oxDb::getDb();

        $sQuery = "SELECT COUNT(1) FROM oxuser WHERE oxrights != 'user' AND oxactive = 1 ";

        return (int) $oDb->getOne($sQuery);
    }
}
