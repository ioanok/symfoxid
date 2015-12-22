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
 * Class manages category attributes
 */
class attribute_category_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxtitle', 'oxcategories', 1, 1, 0),
        array('oxdesc', 'oxcategories', 1, 1, 0),
        array('oxid', 'oxcategories', 0, 0, 0),
        array('oxid', 'oxcategories', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxtitle', 'oxcategories', 1, 1, 0),
                                     array('oxdesc', 'oxcategories', 1, 1, 0),
                                     array('oxid', 'oxcategories', 0, 0, 0),
                                     array('oxid', 'oxcategory2attribute', 0, 0, 1),
                                     array('oxid', 'oxcategories', 0, 0, 1)
                                 ),
                                 'container3' => array(
                                     array('oxtitle', 'oxattribute', 1, 1, 0),
                                     array('oxsort', 'oxcategory2attribute', 1, 0, 0),
                                     array('oxid', 'oxcategory2attribute', 0, 0, 1)
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $myConfig = $this->getConfig();
        $oDb = oxDb::getDb();

        $sCatTable = $this->_getViewName('oxcategories');
        $sDiscountId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchDiscountId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sDiscountId) {
            $sQAdd = " from {$sCatTable} where {$sCatTable}.oxshopid = '" . $myConfig->getShopId() . "' ";
            $sQAdd .= " and {$sCatTable}.oxactive = '1' ";
        } else {
            $sQAdd = " from {$sCatTable} left join oxcategory2attribute " .
                     "on {$sCatTable}.oxid=oxcategory2attribute.oxobjectid " .
                     " where oxcategory2attribute.oxattrid = " . $oDb->quote($sDiscountId) .
                     " and {$sCatTable}.oxshopid = '" . $myConfig->getShopId() . "' " .
                     " and {$sCatTable}.oxactive = '1' ";
        }

        if ($sSynchDiscountId && $sSynchDiscountId != $sDiscountId) {
            $sQAdd .= " and {$sCatTable}.oxid not in ( select {$sCatTable}.oxid " .
                      "from {$sCatTable} left join oxcategory2attribute " .
                      "on {$sCatTable}.oxid=oxcategory2attribute.oxobjectid " .
                      " where oxcategory2attribute.oxattrid = " . $oDb->quote($sSynchDiscountId) .
                      " and {$sCatTable}.oxshopid = '" . $myConfig->getShopId() . "' " .
                      " and {$sCatTable}.oxactive = '1' ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes category from Attributes list
     */
    public function removeCatFromAttr()
    {
        $aChosenCat = $this->_getActionIds('oxcategory2attribute.oxid');



        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sQ = $this->_addFilter("delete oxcategory2attribute.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);
        } elseif (is_array($aChosenCat)) {
            $sChosenCategories = implode(", ", oxDb::getInstance()->quoteArray($aChosenCat));
            $sQ = "delete from oxcategory2attribute where oxcategory2attribute.oxid in (" . $sChosenCategories . ") ";
            oxDb::getDb()->Execute($sQ);
        }


        $this->resetContentCache();

    }

    /**
     * Adds category to Attributes list
     */
    public function addCatToAttr()
    {
        $aAddCategory = $this->_getActionIds('oxcategories.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        $oAttribute = oxNew("oxattribute");
        // adding
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sCatTable = $this->_getViewName('oxcategories');
            $aAddCategory = $this->_getAll($this->_addFilter("select $sCatTable.oxid " . $this->_getQuery()));
        }

        if ($oAttribute->load($soxId) && is_array($aAddCategory)) {
            $oDb = oxDb::getDb();
            foreach ($aAddCategory as $sAdd) {
                $oNewGroup = oxNew("oxbase");
                $oNewGroup->init("oxcategory2attribute");
                $sOxSortField = 'oxcategory2attribute__oxsort';
                $sObjectIdField = 'oxcategory2attribute__oxobjectid';
                $sAttributeIdField = 'oxcategory2attribute__oxattrid';
                $sOxIdField = 'oxattribute__oxid';
                $oNewGroup->$sObjectIdField = new oxField($sAdd);
                $oNewGroup->$sAttributeIdField = new oxField($oAttribute->$sOxIdField->value);
                $sSql = "select max(oxsort) + 1 from oxcategory2attribute where oxobjectid = '$sAdd' ";
                $oNewGroup->$sOxSortField = new oxField(( int ) $oDb->getOne($sSql, false, false));
                $oNewGroup->save();
            }
        }

        $this->resetContentCache();
    }

}
