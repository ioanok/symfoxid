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
 * Admin article RDFa payment manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Shop Settings -> Payment Methods -> RDFa.
 */
class payment_rdfa extends oxAdminDetails
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = "payment_rdfa.tpl";

    /**
     * Predefined RDFa payment methods
     * 0 value have general payments, 1 have creditcar payments
     *
     * @var array
     */
    protected $_aRDFaPayments = array("ByBankTransferInAdvance" => 0,
                                      "ByInvoice"               => 0,
                                      "Cash"                    => 0,
                                      "CheckInAdvance"          => 0,
                                      "COD"                     => 0,
                                      "DirectDebit"             => 0,
                                      "GoogleCheckout"          => 0,
                                      "PayPal"                  => 0,
                                      "PaySwarm"                => 0,
                                      "AmericanExpress"         => 1,
                                      "DinersClub"              => 1,
                                      "Discover"                => 1,
                                      "JCB"                     => 1,
                                      "MasterCard"              => 1,
                                      "VISA"                    => 1);

    /**
     * Saves changed mapping configurations
     */
    public function save()
    {
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        $aRDFaPayments = (array) oxRegistry::getConfig()->getRequestParameter("ardfapayments");

        // Delete old mappings
        $oDb = oxDb::getDb();
        $oDb->execute("DELETE FROM oxobject2payment WHERE oxpaymentid = '" . oxRegistry::getConfig()->getRequestParameter("oxid") . "' AND OXTYPE = 'rdfapayment'");

        // Save new mappings
        foreach ($aRDFaPayments as $sPayment) {
            $oMapping = oxNew("oxbase");
            $oMapping->init("oxobject2payment");
            $oMapping->assign($aParams);
            $oMapping->oxobject2payment__oxobjectid = new oxField($sPayment);
            $oMapping->save();
        }
    }

    /**
     * Returns an array including all available RDFa payments.
     *
     * @return array
     */
    public function getAllRDFaPayments()
    {
        $aRDFaPayments = array();
        $aAssignedRDFaPayments = $this->getAssignedRDFaPayments();
        foreach ($this->_aRDFaPayments as $sName => $iType) {
            $oPayment = new stdClass();
            $oPayment->name = $sName;
            $oPayment->type = $iType;
            $oPayment->checked = in_array($sName, $aAssignedRDFaPayments);
            $aRDFaPayments[] = $oPayment;
        }

        return $aRDFaPayments;
    }

    /**
     * Returns array of RDFa payments which are assigned to current payment
     *
     * @return array
     */
    public function getAssignedRDFaPayments()
    {
        $oDb = oxDb::getDb();
        $aRDFaPayments = array();
        $sSelect = 'select oxobjectid from oxobject2payment where oxpaymentid=' . $oDb->quote(oxRegistry::getConfig()->getRequestParameter("oxid")) . ' and oxtype = "rdfapayment" ';
        $rs = $oDb->execute($sSelect);
        if ($rs && $rs->recordCount()) {
            while (!$rs->EOF) {
                $aRDFaPayments[] = $rs->fields[0];
                $rs->moveNext();
            }
        }

        return $aRDFaPayments;
    }
}
