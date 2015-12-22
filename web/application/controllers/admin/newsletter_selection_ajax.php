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
 * Class manages newsletter user groups rights
 */
class newsletter_selection_ajax extends ajaxListComponent
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
        // active AJAX component
        $sGroupTable = $this->_getViewName('oxgroups');
        $oDb = oxDb::getDb();
        $sDiscountId = $this->getConfig()->getRequestParameter('oxid');
        $sSynchDiscountId = $this->getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sDiscountId) {
            $sQAdd = " from $sGroupTable where 1 ";
        } else {
            $sQAdd = " from oxobject2group left join $sGroupTable on oxobject2group.oxgroupsid=$sGroupTable.oxid ";
            $sQAdd .= " where oxobject2group.oxobjectid = " . $oDb->quote($sDiscountId);
        }

        if ($sSynchDiscountId && $sSynchDiscountId != $sDiscountId) {
            $sQAdd .= " and $sGroupTable.oxid not in ( ";
            $sQAdd .= " select $sGroupTable.oxid from oxobject2group left join $sGroupTable on oxobject2group.oxgroupsid=$sGroupTable.oxid ";
            $sQAdd .= " where oxobject2group.oxobjectid = " . $oDb->quote($sSynchDiscountId) . " ) ";
        }

        // creating AJAX component
        return $sQAdd;
    }

    /**
     * Removes selected user group(s) from newsletter mailing group.
     */
    public function removeGroupFromNewsletter()
    {
        $aRemoveGroups = $this->_getActionIds('oxobject2group.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2group.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif ($aRemoveGroups && is_array($aRemoveGroups)) {
            $sQ = "delete from oxobject2group where oxobject2group.oxid in (" . implode(", ", oxDb::getInstance()->quoteArray($aRemoveGroups)) . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds selected user group(s) to newsletter mailing group.
     */
    public function addGroupToNewsletter()
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
