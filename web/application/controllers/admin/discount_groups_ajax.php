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
 * Class manages discount groups
 */
class discount_groups_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,  visible, multilanguage, ident
        array('oxtitle', 'oxgroups', 1, 0, 0),
        array('oxid', 'oxgroups', 0, 0, 0),
        array('oxid', 'oxgroups', 0, 0, 1),
    ),
                                 'container2' => array(
                                     array('oxtitle', 'oxgroups', 1, 0, 0),
                                     array('oxid', 'oxgroups', 0, 0, 0),
                                     array('oxid', 'oxobject2discount', 0, 0, 1),
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $oConfig = $this->getConfig();
        // active AJAX component
        $sGroupTable = $this->_getViewName('oxgroups');
        $oDb = oxDb::getDb();
        $sId = $oConfig->getRequestParameter('oxid');
        $sSynchId = $oConfig->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sId) {
            $sQAdd = " from {$sGroupTable} where 1 ";
        } else {
            $sQAdd .= " from oxobject2discount, {$sGroupTable} where {$sGroupTable}.oxid=oxobject2discount.oxobjectid ";
            $sQAdd .= " and oxobject2discount.oxdiscountid = " . $oDb->quote($sId) .
                      " and oxobject2discount.oxtype = 'oxgroups' ";
        }

        if ($sSynchId && $sSynchId != $sId) {
            $sQAdd .= " and {$sGroupTable}.oxid not in ( select {$sGroupTable}.oxid " .
                      "from oxobject2discount, {$sGroupTable} where {$sGroupTable}.oxid=oxobject2discount.oxobjectid " .
                      " and oxobject2discount.oxdiscountid = " . $oDb->quote($sSynchId) .
                      " and oxobject2discount.oxtype = 'oxgroups' ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes user group from discount config
     */
    public function removeDiscGroup()
    {
        $oConfig = $this->getConfig();

        $aRemoveGroups = $this->_getActionIds('oxobject2discount.oxid');
        if ($oConfig->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2discount.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif ($aRemoveGroups && is_array($aRemoveGroups)) {
            $sRemoveGroups = implode(", ", oxDb::getInstance()->quoteArray($aRemoveGroups));
            $sQ = "delete from oxobject2discount where oxobject2discount.oxid in (" . $sRemoveGroups . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds user group to discount config
     */
    public function addDiscGroup()
    {
        $oConfig = $this->getConfig();
        $aChosenCat = $this->_getActionIds('oxgroups.oxid');
        $soxId = $oConfig->getRequestParameter('synchoxid');


        if ($oConfig->getRequestParameter('all')) {
            $sGroupTable = $this->_getViewName('oxgroups');
            $aChosenCat = $this->_getAll($this->_addFilter("select $sGroupTable.oxid " . $this->_getQuery()));
        }
        if ($soxId && $soxId != "-1" && is_array($aChosenCat)) {
            foreach ($aChosenCat as $sChosenCat) {
                $oObject2Discount = oxNew("oxbase");
                $oObject2Discount->init('oxobject2discount');
                $oObject2Discount->oxobject2discount__oxdiscountid = new oxField($soxId);
                $oObject2Discount->oxobject2discount__oxobjectid = new oxField($sChosenCat);
                $oObject2Discount->oxobject2discount__oxtype = new oxField("oxgroups");
                $oObject2Discount->save();
            }
        }
    }
}
