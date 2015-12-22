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
 * Class manages promotion groups
 */
class actions_groups_ajax extends ajaxListComponent
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
                                     array('oxid', 'oxobject2action', 0, 0, 1),
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        // active AJAX component
        $sGroupTable = $this->_getViewName('oxgroups');
        $oDb = oxDb::getDb();

        $sId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sId) {
            $sQAdd = " from {$sGroupTable} where 1 ";
        } else {
            $sQAdd .= " from oxobject2action, {$sGroupTable} where {$sGroupTable}.oxid=oxobject2action.oxobjectid " .
                      " and oxobject2action.oxactionid = " . $oDb->quote($sId) .
                      " and oxobject2action.oxclass = 'oxgroups' ";
        }

        if ($sSynchId && $sSynchId != $sId) {
            $sQAdd .= " and {$sGroupTable}.oxid not in ( select {$sGroupTable}.oxid " .
                      "from oxobject2action, {$sGroupTable} where $sGroupTable.oxid=oxobject2action.oxobjectid " .
                      " and oxobject2action.oxactionid = " . $oDb->quote($sSynchId) .
                      " and oxobject2action.oxclass = 'oxgroups' ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes user group from promotion
     */
    public function removePromotionGroup()
    {
        $aRemoveGroups = $this->_getActionIds('oxobject2action.oxid');
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sQ = $this->_addFilter("delete oxobject2action.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);
        } elseif ($aRemoveGroups && is_array($aRemoveGroups)) {
            $sRemoveGroups = implode(", ", oxDb::getInstance()->quoteArray($aRemoveGroups));
            $sQ = "delete from oxobject2action where oxobject2action.oxid in (" . $sRemoveGroups . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds user group to promotion
     */
    public function addPromotionGroup()
    {
        $aChosenGroup = $this->_getActionIds('oxgroups.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sGroupTable = $this->_getViewName('oxgroups');
            $aChosenGroup = $this->_getAll($this->_addFilter("select $sGroupTable.oxid " . $this->_getQuery()));
        }
        if ($soxId && $soxId != "-1" && is_array($aChosenGroup)) {
            foreach ($aChosenGroup as $sChosenGroup) {
                $oObject2Promotion = oxNew("oxbase");
                $oObject2Promotion->init('oxobject2action');
                $oObject2Promotion->oxobject2action__oxactionid = new oxField($soxId);
                $oObject2Promotion->oxobject2action__oxobjectid = new oxField($sChosenGroup);
                $oObject2Promotion->oxobject2action__oxclass = new oxField("oxgroups");
                $oObject2Promotion->save();
            }
        }
    }
}
