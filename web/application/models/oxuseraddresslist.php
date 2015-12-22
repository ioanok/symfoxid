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
 * Class oxUserAddressList
 */
class oxUserAddressList extends oxList
{

    /**
     * Call parent class constructor
     */
    public function __construct()
    {
        parent::__construct('oxaddress');
    }

    /**
     * Selects and loads all address for particular user.
     *
     * @param string $sUserId user id
     */
    public function load($sUserId)
    {
        $sViewName = getViewName('oxcountry');
        $oBaseObject = $this->getBaseObject();
        $sSelectFields = $oBaseObject->getSelectFields();

        $sSelect = "
                SELECT {$sSelectFields}, `oxcountry`.`oxtitle` AS oxcountry
                FROM oxaddress
                LEFT JOIN {$sViewName} AS oxcountry ON oxaddress.oxcountryid = oxcountry.oxid
                WHERE oxaddress.oxuserid = " . oxDb::getDb()->quote($sUserId);
        $this->selectString($sSelect);
    }
}
