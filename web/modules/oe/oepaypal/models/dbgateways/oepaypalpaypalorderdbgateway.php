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
 * Order db gateway class
 */
class oePayPalPayPalOrderDbGateway extends oePayPalModelDbGateway
{
    /**
     * Save PayPal order data to database.
     *
     * @param array $aData
     *
     * @return bool
     */
    public function save($aData)
    {
        $oDb = $this->_getDb();

        foreach ($aData as $sField => $sData) {
            $aSql[] = '`' . $sField . '` = ' . $oDb->quote($sData);
        }

        $sSql = 'INSERT INTO `oepaypal_order` SET ';
        $sSql .= implode(', ', $aSql);
        $sSql .= ' ON DUPLICATE KEY UPDATE ';
        $sSql .= ' `oepaypal_orderid`=LAST_INSERT_ID(`oepaypal_orderid`), ';
        $sSql .= implode(', ', $aSql);

        $oDb->execute($sSql);

        $iId = $aData['oepaypal_orderid'];
        if (empty($iId)) {
            $iId = $oDb->getOne('SELECT LAST_INSERT_ID()');
        }

        return $iId;
    }

    /**
     * Load PayPal order data from Db.
     *
     * @param string $sOrderId Order id.
     *
     * @return array
     */
    public function load($sOrderId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getRow('SELECT * FROM `oepaypal_order` WHERE `oepaypal_orderid` = ' . $oDb->quote($sOrderId));

        return $aData;
    }

    /**
     * Delete PayPal order data from database.
     *
     * @param string $sOrderId Order id.
     *
     * @return bool
     */
    public function delete($sOrderId)
    {
        $oDb = $this->_getDb();
        $oDb->startTransaction();

        $blDeleteCommentsResult = $oDb->execute(
            'DELETE
                `oepaypal_orderpaymentcomments`
            FROM `oepaypal_orderpaymentcomments`
                INNER JOIN `oepaypal_orderpayments` ON `oepaypal_orderpayments`.`oepaypal_paymentid` = `oepaypal_orderpaymentcomments`.`oepaypal_paymentid`
            WHERE `oepaypal_orderpayments`.`oepaypal_orderid` = ' . $oDb->quote($sOrderId)
        );
        $blDeleteOrderPaymentResult = $oDb->execute('DELETE FROM `oepaypal_orderpayments` WHERE `oepaypal_orderid` = ' . $oDb->quote($sOrderId));
        $blDeleteOrderResult = $oDb->execute('DELETE FROM `oepaypal_order` WHERE `oepaypal_orderid` = ' . $oDb->quote($sOrderId));

        $blResult = ($blDeleteOrderResult !== false) || ($blDeleteOrderPaymentResult !== false) || ($blDeleteCommentsResult !== false);

        if ($blResult) {
            $oDb->commitTransaction();
        } else {
            $oDb->rollbackTransaction();
        }

        return $blResult;
    }
}
