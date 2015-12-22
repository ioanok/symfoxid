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
 * Class for updating category tree structure in DB.
 */
class Category_Update extends oxAdminView
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = "category_update.tpl";

    /**
     * Category list object
     *
     * @var oxCategoryList
     */
    protected $_oCatList = null;

    /**
     * Returns category list object
     *
     * @return oxCategoryList
     */
    protected function _getCategoryList()
    {
        if ($this->_oCatList == null) {
            $this->_oCatList = oxNew("oxCategoryList");
            $this->_oCatList->updateCategoryTree(false);
        }

        return $this->_oCatList;
    }

    /**
     * Returns category list object
     *
     * @return array
     */
    public function getCatListUpdateInfo()
    {
        return $this->_getCategoryList()->getUpdateInfo();
    }
}
