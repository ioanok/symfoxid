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
 * Class manages payment user groups
 */
class payment_main_ajax extends ajaxListComponent
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
                                     array('oxid', 'oxobject2group', 0, 0, 1),
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        // looking for table/view
        $sGroupTable = $this->_getViewName('oxgroups');
        $sGroupId = $this->getConfig()->getRequestParameter('oxid');
        $sSynchGroupId = $this->getConfig()->getRequestParameter('synchoxid');
        $oDb = oxDb::getDb();

        // category selected or not ?
        if (!$sGroupId) {
            $sQAdd = " from {$sGroupTable} ";
        } else {
            $sQAdd = " from {$sGroupTable}, oxobject2group where ";
            $sQAdd .= " oxobject2group.oxobjectid = " . $oDb->quote($sGroupId) .
                      " and oxobject2group.oxgroupsid = {$sGroupTable}.oxid ";
        }

        if (!$sSynchGroupId) {
            $sSynchGroupId = $this->getConfig()->getRequestParameter('oxajax_synchfid');
        }
        if ($sSynchGroupId && $sSynchGroupId != $sGroupId) {
            if (!$sGroupId) {
                $sQAdd .= 'where ';
            } else {
                $sQAdd .= 'and ';
            }
            $sQAdd .= " {$sGroupTable}.oxid not in ( select {$sGroupTable}.oxid from {$sGroupTable}, oxobject2group " .
                      "where  oxobject2group.oxobjectid = " . $oDb->quote($sSynchGroupId) .
                      " and oxobject2group.oxgroupsid = $sGroupTable.oxid ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes group of users that may pay using selected method(s).
     */
    public function removePayGroup()
    {
        $aRemoveGroups = $this->_getActionIds('oxobject2group.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2group.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif ($aRemoveGroups && is_array($aRemoveGroups)) {
            $sRemoveGroups = implode(", ", oxDb::getInstance()->quoteArray($aRemoveGroups));
            $sQ = "delete from oxobject2group where oxobject2group.oxid in (" . $sRemoveGroups . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds group of users that may pay using selected method(s).
     */
    public function addPayGroup()
    {
        $aAddGroups = $this->_getActionIds('oxgroups.oxid');
        $soxId = $this->getConfig()->getRequestParameter('synchoxid');

        if ($this->getConfig()->getRequestParameter('all')) {
            $sGroupTable = $this->_getViewName('oxgroups');
            $aAddGroups = $this->_getAll($this->_addFilter("select $sGroupTable.oxid " . $this->_getQuery()));
        }
        if ($soxId && $soxId != "-1" && is_array($aAddGroups)) {
            foreach ($aAddGroups as $sAddgroup) {
                $oNewGroup = oxNew("oxobject2group");
                $oNewGroup->oxobject2group__oxobjectid = new oxField($soxId);
                $oNewGroup->oxobject2group__oxgroupsid = new oxField($sAddgroup);
                $oNewGroup->save();
            }
        }
    }
}
