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
 * Class manages delivery categories
 */
class delivery_categories_ajax extends ajaxListComponent
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
                                     array('oxid', 'oxobject2delivery', 0, 0, 1),
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
        // looking for table/view
        $sCatTable = $this->_getViewName('oxcategories');
        $oDb = oxDb::getDb();
        $sDelId = $this->getConfig()->getRequestParameter('oxid');
        $sSynchDelId = $this->getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sDelId) {
            $sQAdd = " from {$sCatTable} ";
        } else {
            $sQAdd = " from oxobject2delivery left join {$sCatTable} " .
                     "on {$sCatTable}.oxid=oxobject2delivery.oxobjectid " .
                     " where oxobject2delivery.oxdeliveryid = " . $oDb->quote($sDelId) .
                     " and oxobject2delivery.oxtype = 'oxcategories' ";
        }

        if ($sSynchDelId && $sSynchDelId != $sDelId) {
            // performance
            $sSubSelect = " select {$sCatTable}.oxid from oxobject2delivery left join {$sCatTable} " .
                          "on {$sCatTable}.oxid=oxobject2delivery.oxobjectid " .
                          " where oxobject2delivery.oxdeliveryid = " . $oDb->quote($sSynchDelId) .
                          " and oxobject2delivery.oxtype = 'oxcategories' ";
            if (stristr($sQAdd, 'where') === false) {
                $sQAdd .= ' where ';
            } else {
                $sQAdd .= ' and ';
            }
            $sQAdd .= " {$sCatTable}.oxid not in ( $sSubSelect ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes category from delivery configuration
     */
    public function removeCatFromDel()
    {
        $aChosenCat = $this->_getActionIds('oxobject2delivery.oxid');

        // removing all
        if ($this->getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2delivery.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenCat)) {
            $sChosenCategoriess = implode(", ", oxDb::getInstance()->quoteArray($aChosenCat));
            $sQ = "delete from oxobject2delivery where oxobject2delivery.oxid in (" . $sChosenCategoriess . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds category to delivery configuration
     */
    public function addCatToDel()
    {
        $aChosenCat = $this->_getActionIds('oxcategories.oxid');
        $soxId = $this->getConfig()->getRequestParameter('synchoxid');

        // adding
        if ($this->getConfig()->getRequestParameter('all')) {
            $sCatTable = $this->_getViewName('oxcategories');
            $aChosenCat = $this->_getAll($this->_addFilter("select $sCatTable.oxid " . $this->_getQuery()));
        }

        if (isset($soxId) && $soxId != "-1" && isset($aChosenCat) && $aChosenCat) {
            foreach ($aChosenCat as $sChosenCat) {
                $oObject2Delivery = oxNew('oxbase');
                $oObject2Delivery->init('oxobject2delivery');
                $oObject2Delivery->oxobject2delivery__oxdeliveryid = new oxField($soxId);
                $oObject2Delivery->oxobject2delivery__oxobjectid = new oxField($sChosenCat);
                $oObject2Delivery->oxobject2delivery__oxtype = new oxField("oxcategories");
                $oObject2Delivery->save();
            }
        }
    }
}
