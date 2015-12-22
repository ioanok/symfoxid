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

require_once 'oxerptype.php';

/**
 * article2category relation type subclass
 */
class oxERPType_Article2Category extends oxERPType
{

    /**
     * class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();

        $this->_sTableName = 'oxobject2category';

        $this->_aKeyFieldList = array(
            'OXOBJECTID' => 'OXOBJECTID',
            'OXCATNID'   => 'OXCATNID',
            'OXSHOPID'   => 'OXSHOPID'
        );

        unset($this->_aKeyFieldList['OXSHOPID']);
    }
}
