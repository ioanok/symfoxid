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
 */

/**
 * Order list class wrapper for PayPal module
 */
class oePayPalOrder_List extends oePayPalOrder_List_parent
{

    /**
     * Executes parent method parent::render() and returns name of template
     * file "order_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $sTemplate = parent::render();

        $sPaymentStatus = oxRegistry::getConfig()->getRequestParameter("paypalpaymentstatus");
        $sPayment = oxRegistry::getConfig()->getRequestParameter("paypalpayment");

        $this->_aViewData["spaypalpaymentstatus"] = $sPaymentStatus ? $sPaymentStatus : -1;
        $this->_aViewData["opaypalpaymentstatuslist"] = new oePayPalOrderPaymentStatusList();

        $this->_aViewData["paypalpayment"] = $sPayment ? $sPayment : -1;

        /** @var oxList $oPaymentList */
        $oPaymentList = oxNew('oxList');
        $oPaymentList->init('oxPayment');

        $this->_aViewData["oPayments"] = $oPaymentList->getList();

        return $sTemplate;
    }

    /**
     * Builds and returns SQL query string. Adds additional order check.
     *
     * @param object $oListObject list main object.
     *
     * @return string
     */
    protected function _buildSelectString($oListObject = null)
    {
        $sSql = parent::_buildSelectString($oListObject);

        $sPaymentTable = getViewName("oxpayments");

        $sQ = ", `oepaypal_order`.`oepaypal_paymentstatus`, `payments`.`oxdesc` as `paymentname` from `oxorder`
        LEFT JOIN `oepaypal_order` ON `oepaypal_order`.`oepaypal_orderid` = `oxorder`.`oxid`
        LEFT JOIN `" . $sPaymentTable . "` AS `payments` on `payments`.oxid=oxorder.oxpaymenttype ";

        $sSql = str_replace('from oxorder', $sQ, $sSql);

        return $sSql;
    }

    /**
     * Adding folder check.
     *
     * @param array  $aWhere  SQL condition array.
     * @param string $sqlFull SQL query string.
     *
     * @return string
     */
    protected function _prepareWhereQuery($aWhere, $sqlFull)
    {
        $oDb = oxDb::getDb();
        $sQ = parent::_prepareWhereQuery($aWhere, $sqlFull);

        $sPaymentStatus = oxRegistry::getConfig()->getRequestParameter("paypalpaymentstatus");
        $oPaymentStatusList = new oePayPalOrderPaymentStatusList();

        if ($sPaymentStatus && $sPaymentStatus != '-1' && in_array($sPaymentStatus, $oPaymentStatusList->getArray())) {
            $sQ .= " AND ( `oepaypal_order`.`oepaypal_paymentstatus` = " . $oDb->quote($sPaymentStatus) . " )";
            $sQ .= " AND ( `oepaypal_order`.`oepaypal_orderid` IS NOT NULL ) ";
        }

        $sPayment = oxRegistry::getConfig()->getRequestParameter("paypalpayment");
        if ($sPayment && $sPayment != '-1') {
            $sQ .= " and ( oxorder.oxpaymenttype = " . $oDb->quote($sPayment) . " )";
        }

        return $sQ;
    }
}