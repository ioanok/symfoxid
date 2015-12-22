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
 * Admin category list manager.
 * Collects attributes base information (sorting, title, etc.), there is ability to
 * filter them by sorting, title or delete them.
 * Admin Menu: Manage Products -> Categories.
 */
class Category_List extends oxAdminList
{

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxcategory';

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'oxcategorylist';

    /**
     * Returns sorting fields array
     *
     * @return array
     */
    public function getListSorting()
    {
        $sSortParameter = oxRegistry::getConfig()->getRequestParameter('sort');
        if ($this->_aCurrSorting === null && !$sSortParameter && ($oBaseObject = $this->getItemListBaseObject())) {
            $sCatView = $oBaseObject->getCoreTableName();

            $this->_aCurrSorting[$sCatView]["oxrootid"] = "desc";
            $this->_aCurrSorting[$sCatView]["oxleft"] = "asc";

            return $this->_aCurrSorting;
        } else {
            return parent::getListSorting();
        }
    }

    /**
     * Loads category tree, passes data to Smarty and returns name of
     * template file "category_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $oLang = oxRegistry::getLang();
        $iLang = $oLang->getTplLanguage();

        // parent category tree
        $oCatTree = oxNew("oxCategoryList");
        $oCatTree->loadList();

        // add Root as fake category
        // rebuild list as we need the root entry at the first position
        $aNewList = array();
        $oRoot = new stdClass();
        $oRoot->oxcategories__oxid = new oxField(null, oxField::T_RAW);
        $oRoot->oxcategories__oxtitle = new oxField($oLang->translateString("viewAll", $iLang), oxField::T_RAW);
        $aNewList[] = $oRoot;

        $oRoot = new stdClass();
        $oRoot->oxcategories__oxid = new oxField("oxrootid", oxField::T_RAW);
        $oRoot->oxcategories__oxtitle = new oxField("-- " . $oLang->translateString("mainCategory", $iLang) . " --", oxField::T_RAW);
        $aNewList[] = $oRoot;

        foreach ($oCatTree as $oCategory) {
            $aNewList[] = $oCategory;
        }

        $oCatTree->assign($aNewList);
        $aFilter = $this->getListFilter();
        if (is_array($aFilter) && isset($aFilter["oxcategories"]["oxparentid"])) {
            foreach ($oCatTree as $oCategory) {
                if ($oCategory->oxcategories__oxid->value == $aFilter["oxcategories"]["oxparentid"]) {
                    $oCategory->selected = 1;
                    break;
                }
            }
        }

        $this->_aViewData["cattree"] = $oCatTree;

        return "category_list.tpl";
    }
}
