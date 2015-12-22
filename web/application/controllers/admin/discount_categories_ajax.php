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
 * Class manages discount categories
 */
class discount_categories_ajax extends ajaxListComponent
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
                                     array('oxid', 'oxobject2discount', 0, 0, 1),
                                     array('oxid', 'oxcategories', 0, 0, 1)
                                 ),
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $oDb = oxDb::getDb();
        $oConfig = $this->getConfig();
        $sId = $oConfig->getRequestParameter('oxid');
        $sSynchId = $oConfig->getRequestParameter('synchoxid');

        $sCategoryTable = $this->_getViewName('oxcategories');

        // category selected or not ?
        if (!$sId) {
            $sQAdd = " from {$sCategoryTable}";
        } else {
            $sQAdd = " from oxobject2discount, {$sCategoryTable} " .
                     "where {$sCategoryTable}.oxid=oxobject2discount.oxobjectid " .
                     " and oxobject2discount.oxdiscountid = " . $oDb->quote($sId) .
                     " and oxobject2discount.oxtype = 'oxcategories' ";
        }

        if ($sSynchId && $sSynchId != $sId) {
            // performance
            $sSubSelect = " select {$sCategoryTable}.oxid from oxobject2discount, {$sCategoryTable} " .
                          "where {$sCategoryTable}.oxid=oxobject2discount.oxobjectid " .
                          " and oxobject2discount.oxdiscountid = " . $oDb->quote($sSynchId) .
                          " and oxobject2discount.oxtype = 'oxcategories' ";
            if (stristr($sQAdd, 'where') === false) {
                $sQAdd .= ' where ';
            } else {
                $sQAdd .= ' and ';
            }
            $sQAdd .= " {$sCategoryTable}.oxid not in ( $sSubSelect ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes selected category (categories) from discount list
     */
    public function removeDiscCat()
    {
        $oConfig = $this->getConfig();
        $aChosenCat = $this->_getActionIds('oxobject2discount.oxid');


        if ($oConfig->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2discount.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenCat)) {
            $sChosenCategories = implode(", ", oxDb::getInstance()->quoteArray($aChosenCat));
            $sQ = "delete from oxobject2discount where oxobject2discount.oxid in (" . $sChosenCategories . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds selected category (categories) to discount list
     */
    public function addDiscCat()
    {
        $oConfig = $this->getConfig();
        $aChosenCat = $this->_getActionIds('oxcategories.oxid');
        $soxId = $oConfig->getRequestParameter('synchoxid');


        if ($oConfig->getRequestParameter('all')) {
            $sCategoryTable = $this->_getViewName('oxcategories');
            $aChosenCat = $this->_getAll($this->_addFilter("select $sCategoryTable.oxid " . $this->_getQuery()));
        }
        if ($soxId && $soxId != "-1" && is_array($aChosenCat)) {
            foreach ($aChosenCat as $sChosenCat) {
                $oObject2Discount = oxNew("oxbase");
                $oObject2Discount->init('oxobject2discount');
                $oObject2Discount->oxobject2discount__oxdiscountid = new oxField($soxId);
                $oObject2Discount->oxobject2discount__oxobjectid = new oxField($sChosenCat);
                $oObject2Discount->oxobject2discount__oxtype = new oxField("oxcategories");
                $oObject2Discount->save();
            }
        }

    }
}
