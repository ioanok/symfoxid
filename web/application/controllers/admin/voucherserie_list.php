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
 * Admin voucherserie list manager.
 * Collects voucherserie base information (serie no., discount, valid from, etc.),
 * there is ability to filter them by deiscount, serie no. or delete them.
 * Admin Menu: Shop Settings -> Vouchers.
 */
class VoucherSerie_List extends oxAdminList
{

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxvoucherserie';

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'voucherserie_list.tpl';

    /**
     * Deletes selected Voucherserie.
     */
    public function deleteEntry()
    {
        // first we remove vouchers
        $oVoucherSerie = oxNew("oxvoucherserie");
        $oVoucherSerie->load($this->getEditObjectId());
        $oVoucherSerie->deleteVoucherList();

        parent::deleteEntry();
    }
}
