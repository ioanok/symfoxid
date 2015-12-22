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
 * Admin article RDFa deliveryset manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Shop Settings -> Shipping & Handling -> RDFa.
 */
class deliveryset_rdfa extends payment_rdfa
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = "deliveryset_rdfa.tpl";

    /**
     * Predefined delivery methods
     *
     * @var array
     */
    protected $_aRDFaDeliveries = array(
        "DeliveryModeDirectDownload" => 0,
        "DeliveryModeFreight"        => 0,
        "DeliveryModeMail"           => 0,
        "DeliveryModeOwnFleet"       => 0,
        "DeliveryModePickUp"         => 0,
        "DHL"                        => 1,
        "FederalExpress"             => 1,
        "UPS"                        => 1
    );

    /**
     * Saves changed mapping configurations
     */
    public function save()
    {
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        $aRDFaDeliveries = (array) oxRegistry::getConfig()->getRequestParameter("ardfadeliveries");

        // Delete old mappings
        $oDb = oxDb::getDb();
        $sOxIdParameter = oxRegistry::getConfig()->getRequestParameter("oxid");
        $sSql = "DELETE FROM oxobject2delivery WHERE oxdeliveryid = '{$sOxIdParameter}' AND OXTYPE = 'rdfadeliveryset'";
        $oDb->execute($sSql);

        // Save new mappings
        foreach ($aRDFaDeliveries as $sDelivery) {
            $oMapping = oxNew("oxbase");
            $oMapping->init("oxobject2delivery");
            $oMapping->assign($aParams);
            $oMapping->oxobject2delivery__oxobjectid = new oxField($sDelivery);
            $oMapping->save();
        }
    }

    /**
     * Returns an array including all available RDFa deliveries.
     *
     * @return array
     */
    public function getAllRDFaDeliveries()
    {
        $aRDFaDeliveries = array();
        $aAssignedRDFaDeliveries = $this->getAssignedRDFaDeliveries();
        foreach ($this->_aRDFaDeliveries as $sName => $iType) {
            $oDelivery = new stdClass();
            $oDelivery->name = $sName;
            $oDelivery->type = $iType;
            $oDelivery->checked = in_array($sName, $aAssignedRDFaDeliveries);
            $aRDFaDeliveries[] = $oDelivery;
        }

        return $aRDFaDeliveries;
    }

    /**
     * Returns array of RDFa deliveries which are assigned to current delivery
     *
     * @return array
     */
    public function getAssignedRDFaDeliveries()
    {
        $oDb = oxDb::getDb();
        $aRDFaDeliveries = array();
        $sSelect = 'select oxobjectid from oxobject2delivery where oxdeliveryid=' . $oDb->quote(oxRegistry::getConfig()->getRequestParameter("oxid")) . ' and oxtype = "rdfadeliveryset" ';
        $rs = $oDb->execute($sSelect);
        if ($rs && $rs->recordCount()) {
            while (!$rs->EOF) {
                $aRDFaDeliveries[] = $rs->fields[0];
                $rs->moveNext();
            }
        }

        return $aRDFaDeliveries;
    }
}
