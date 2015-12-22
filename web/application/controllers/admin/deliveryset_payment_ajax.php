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
 * Class manages deliveryset payment
 */
class deliveryset_payment_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxdesc', 'oxpayments', 1, 1, 0),
        array('oxaddsum', 'oxpayments', 1, 0, 0),
        array('oxaddsumtype', 'oxpayments', 0, 0, 0),
        array('oxid', 'oxpayments', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxdesc', 'oxpayments', 1, 1, 0),
                                     array('oxaddsum', 'oxpayments', 1, 0, 0),
                                     array('oxaddsumtype', 'oxpayments', 0, 0, 0),
                                     array('oxid', 'oxobject2payment', 0, 0, 1)
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $oDb = oxDb::getDb();
        $sId = $this->getConfig()->getRequestParameter('oxid');
        $sSynchId = $this->getConfig()->getRequestParameter('synchoxid');

        $sPayTable = $this->_getViewName('oxpayments');

        // category selected or not ?
        if (!$sId) {
            $sQAdd = " from $sPayTable where 1 ";
        } else {
            $sQAdd = " from oxobject2payment, $sPayTable where oxobject2payment.oxobjectid = " . $oDb->quote($sId);
            $sQAdd .= " and oxobject2payment.oxpaymentid = $sPayTable.oxid and oxobject2payment.oxtype = 'oxdelset' ";
        }

        if ($sSynchId && $sSynchId != $sId) {
            $sQAdd .= "and $sPayTable.oxid not in ( select $sPayTable.oxid from oxobject2payment, $sPayTable where oxobject2payment.oxobjectid = " . $oDb->quote($sSynchId);
            $sQAdd .= "and oxobject2payment.oxpaymentid = $sPayTable.oxid and oxobject2payment.oxtype = 'oxdelset' ) ";
        }

        return $sQAdd;
    }

    /**
     * Remove these payments from this set
     */
    public function removePayFromSet()
    {
        $aChosenCntr = $this->_getActionIds('oxobject2payment.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2payment.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenCntr)) {
            $sQ = "delete from oxobject2payment where oxobject2payment.oxid in (" . implode(", ", oxDb::getInstance()->quoteArray($aChosenCntr)) . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds this payments to this set
     */
    public function addPayToSet()
    {
        $aChosenSets = $this->_getActionIds('oxpayments.oxid');
        $soxId = $this->getConfig()->getRequestParameter('synchoxid');

        // adding
        if ($this->getConfig()->getRequestParameter('all')) {
            $sPayTable = $this->_getViewName('oxpayments');
            $aChosenSets = $this->_getAll($this->_addFilter("select $sPayTable.oxid " . $this->_getQuery()));
        }
        if ($soxId && $soxId != "-1" && is_array($aChosenSets)) {
            $oDb = oxDb::getDb();
            foreach ($aChosenSets as $sChosenSet) {
                // check if we have this entry already in
                $sID = $oDb->getOne("select oxid from oxobject2payment where oxpaymentid = " . $oDb->quote($sChosenSet) . "  and oxobjectid = " . $oDb->quote($soxId) . " and oxtype = 'oxdelset'", false, false);
                if (!isset($sID) || !$sID) {
                    $oObject = oxNew('oxbase');
                    $oObject->init('oxobject2payment');
                    $oObject->oxobject2payment__oxpaymentid = new oxField($sChosenSet);
                    $oObject->oxobject2payment__oxobjectid = new oxField($soxId);
                    $oObject->oxobject2payment__oxtype = new oxField("oxdelset");
                    $oObject->save();
                }
            }
        }
    }
}
