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
 * Shop list manager.
 * Organizes list of shop objects.
 *
 */
class oxShopList extends oxList
{

    /**
     * Calls parent constructor
     *
     * @return null
     */
    public function __construct()
    {
        return parent::__construct('oxshop');
    }

    /**
     * Loads all shops to list
     */
    public function getAll()
    {
        $this->selectString('SELECT `oxshops`.* FROM `oxshops`');
    }

    /**
     * Gets shop list into object
     */
    public function getIdTitleList()
    {
        $this->setBaseObject(oxNew('oxListObject', 'oxshops'));
        $this->selectString('SELECT `OXID`, `OXNAME` FROM `oxshops`');
    }

}
