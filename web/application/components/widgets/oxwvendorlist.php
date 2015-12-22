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
 * Vendor list widget.
 * Forms vendor list.
 */
class oxwVendorList extends oxWidget
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/footer/vendorlist.tpl';

    /**
     * Template variable getter. Returns vendorlist for search
     *
     * @return array
     */
    public function getVendorlist()
    {
        if ($this->_aVendorlist === null) {
            $oVendorTree = oxNew('oxvendorlist');
            $oVendorTree->buildVendorTree('vendorlist', null, $this->getConfig()->getShopHomeURL());
            $this->_aVendorlist = $oVendorTree;
        }

        return $this->_aVendorlist;
    }
}
