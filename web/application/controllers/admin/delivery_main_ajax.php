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
 * Class manages delivery countries
 */
class delivery_main_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxtitle', 'oxcountry', 1, 1, 0),
        array('oxisoalpha2', 'oxcountry', 1, 0, 0),
        array('oxisoalpha3', 'oxcountry', 0, 0, 0),
        array('oxunnum3', 'oxcountry', 0, 0, 0),
        array('oxid', 'oxcountry', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxtitle', 'oxcountry', 1, 1, 0),
                                     array('oxisoalpha2', 'oxcountry', 1, 0, 0),
                                     array('oxisoalpha3', 'oxcountry', 0, 0, 0),
                                     array('oxunnum3', 'oxcountry', 0, 0, 0),
                                     array('oxid', 'oxobject2delivery', 0, 0, 1)
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $sCountryTable = $this->_getViewName('oxcountry');
        $oDb = oxDb::getDb();
        $sId = $this->getConfig()->getRequestParameter('oxid');
        $sSynchId = $this->getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sId) {
            $sQAdd = " from {$sCountryTable} where {$sCountryTable}.oxactive = '1' ";
        } else {
            $sQAdd = " from oxobject2delivery left join {$sCountryTable} " .
                     "on {$sCountryTable}.oxid=oxobject2delivery.oxobjectid " .
                     " where oxobject2delivery.oxdeliveryid = " . $oDb->quote($sId) .
                     " and oxobject2delivery.oxtype = 'oxcountry' ";
        }

        if ($sSynchId && $sSynchId != $sId) {
            $sQAdd .= " and {$sCountryTable}.oxid not in ( select {$sCountryTable}.oxid " .
                      "from oxobject2delivery left join {$sCountryTable} " .
                      "on {$sCountryTable}.oxid=oxobject2delivery.oxobjectid " .
                      " where oxobject2delivery.oxdeliveryid = " . $oDb->quote($sSynchId) .
                      " and oxobject2delivery.oxtype = 'oxcountry' ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes chosen countries from delivery list
     */
    public function removeCountryFromDel()
    {
        $aChosenCntr = $this->_getActionIds('oxobject2delivery.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2delivery.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenCntr)) {
            $sQ = "delete from oxobject2delivery where oxobject2delivery.oxid in (" . implode(", ", oxDb::getInstance()->quoteArray($aChosenCntr)) . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds chosen countries to delivery list
     */
    public function addCountryToDel()
    {
        $aChosenCntr = $this->_getActionIds('oxcountry.oxid');
        $soxId = $this->getConfig()->getRequestParameter('synchoxid');

        // adding
        if ($this->getConfig()->getRequestParameter('all')) {
            $sCountryTable = $this->_getViewName('oxcountry');
            $aChosenCntr = $this->_getAll($this->_addFilter("select $sCountryTable.oxid " . $this->_getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aChosenCntr)) {
            foreach ($aChosenCntr as $sChosenCntr) {
                $oObject2Delivery = oxNew('oxbase');
                $oObject2Delivery->init('oxobject2delivery');
                $oObject2Delivery->oxobject2delivery__oxdeliveryid = new oxField($soxId);
                $oObject2Delivery->oxobject2delivery__oxobjectid = new oxField($sChosenCntr);
                $oObject2Delivery->oxobject2delivery__oxtype = new oxField('oxcountry');
                $oObject2Delivery->save();
            }
        }
    }
}
