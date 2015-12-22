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
 * Class manages deliveryset users
 */
class deliveryset_users_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,  visible, multilanguage, ident
        array('oxusername', 'oxuser', 1, 0, 0),
        array('oxlname', 'oxuser', 0, 0, 0),
        array('oxfname', 'oxuser', 0, 0, 0),
        array('oxstreet', 'oxuser', 0, 0, 0),
        array('oxstreetnr', 'oxuser', 0, 0, 0),
        array('oxcity', 'oxuser', 0, 0, 0),
        array('oxzip', 'oxuser', 0, 0, 0),
        array('oxfon', 'oxuser', 0, 0, 0),
        array('oxbirthdate', 'oxuser', 0, 0, 0),
        array('oxid', 'oxuser', 0, 0, 1),
    ),
                                 'container2' => array(
                                     array('oxusername', 'oxuser', 1, 0, 0),
                                     array('oxlname', 'oxuser', 0, 0, 0),
                                     array('oxfname', 'oxuser', 0, 0, 0),
                                     array('oxstreet', 'oxuser', 0, 0, 0),
                                     array('oxstreetnr', 'oxuser', 0, 0, 0),
                                     array('oxcity', 'oxuser', 0, 0, 0),
                                     array('oxzip', 'oxuser', 0, 0, 0),
                                     array('oxfon', 'oxuser', 0, 0, 0),
                                     array('oxbirthdate', 'oxuser', 0, 0, 0),
                                     array('oxid', 'oxobject2delivery', 0, 0, 1),
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
        $sId = $myConfig->getRequestParameter('oxid');
        $sSynchId = $myConfig->getRequestParameter('synchoxid');

        $sUserTable = $this->_getViewName('oxuser');

        // category selected or not ?
        if (!$sId) {
            $sQAdd = " from $sUserTable where 1 ";
            if (!$myConfig->getConfigParam('blMallUsers')) {
                $sQAdd .= "and $sUserTable.oxshopid = '" . $myConfig->getShopId() . "' ";
            }
        } elseif ($sSynchId && $sSynchId != $sId) {
            // selected group ?
            $sQAdd = " from oxobject2group left join $sUserTable on $sUserTable.oxid = oxobject2group.oxobjectid ";
            $sQAdd .= " where oxobject2group.oxgroupsid = " . $oDb->quote($sId);
            if (!$myConfig->getConfigParam('blMallUsers')) {
                $sQAdd .= "and $sUserTable.oxshopid = '" . $myConfig->getShopId() . "' ";
            }

            // resetting
            $sId = null;
        } else {
            $sQAdd = " from oxobject2delivery, $sUserTable where oxobject2delivery.oxdeliveryid = " . $oDb->quote($sId);
            $sQAdd .= "and oxobject2delivery.oxobjectid = $sUserTable.oxid and oxobject2delivery.oxtype = 'oxdelsetu' ";
        }

        if ($sSynchId && $sSynchId != $sId) {
            $sQAdd .= "and $sUserTable.oxid not in ( select $sUserTable.oxid from oxobject2delivery, $sUserTable where oxobject2delivery.oxdeliveryid = " . $oDb->quote($sSynchId);
            $sQAdd .= "and oxobject2delivery.oxobjectid = $sUserTable.oxid and oxobject2delivery.oxtype = 'oxdelsetu' ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes users for delivery sets config
     */
    public function removeUserFromSet()
    {
        $aRemoveGroups = $this->_getActionIds('oxobject2delivery.oxid');
        if ($this->getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2delivery.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif ($aRemoveGroups && is_array($aRemoveGroups)) {
            $sQ = "delete from oxobject2delivery where oxobject2delivery.oxid in (" . implode(", ", oxDb::getInstance()->quoteArray($aRemoveGroups)) . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds users for delivery sets config
     */
    public function addUserToSet()
    {
        $aChosenUsr = $this->_getActionIds('oxuser.oxid');
        $soxId = $this->getConfig()->getRequestParameter('synchoxid');

        // adding
        if ($this->getConfig()->getRequestParameter('all')) {
            $sUserTable = $this->_getViewName('oxuser');
            $aChosenUsr = $this->_getAll($this->_addFilter("select $sUserTable.oxid " . $this->_getQuery()));
        }
        if ($soxId && $soxId != "-1" && is_array($aChosenUsr)) {
            foreach ($aChosenUsr as $sChosenUsr) {
                $oObject2Delivery = oxNew('oxbase');
                $oObject2Delivery->init('oxobject2delivery');
                $oObject2Delivery->oxobject2delivery__oxdeliveryid = new oxField($soxId);
                $oObject2Delivery->oxobject2delivery__oxobjectid = new oxField($sChosenUsr);
                $oObject2Delivery->oxobject2delivery__oxtype = new oxField("oxdelsetu");
                $oObject2Delivery->save();
            }
        }
    }
}
