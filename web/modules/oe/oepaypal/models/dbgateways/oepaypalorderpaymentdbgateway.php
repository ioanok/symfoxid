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
 * Order payment db gateway class
 */
class oePayPalOrderPaymentDbGateway extends oePayPalModelDbGateway
{
    /**
     * Save PayPal order payment data to database.
     *
     * @param array $aData
     *
     * @return int
     */
    public function save($aData)
    {
        $oDb = $this->_getDb();

        foreach ($aData as $sField => $sData) {
            $aSql[] = '`' . $sField . '` = ' . $oDb->quote($sData);
        }

        $sSql = 'INSERT INTO `oepaypal_orderpayments` SET ';
        $sSql .= implode(', ', $aSql);
        $sSql .= ' ON DUPLICATE KEY UPDATE ';
        $sSql .= ' `oepaypal_paymentid`=LAST_INSERT_ID(`oepaypal_paymentid`), ';
        $sSql .= implode(', ', $aSql);
        $oDb->execute($sSql);

        $iId = $aData['oepaypal_paymentid'];
        if (empty($iId)) {
            $iId = $oDb->getOne('SELECT LAST_INSERT_ID()');
        }

        return $iId;
    }

    /**
     * Load PayPal order payment data from Db.
     *
     * @param string $sPaymentId Order id.
     *
     * @return array
     */
    public function load($sPaymentId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getRow('SELECT * FROM `oepaypal_orderpayments` WHERE `oepaypal_paymentid` = ' . $oDb->quote($sPaymentId));

        return $aData;
    }

    /**
     * Load PayPal order payment data from Db.
     *
     * @param string $sTransactionId Order id.
     *
     * @return array
     */
    public function loadByTransactionId($sTransactionId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getRow('SELECT * FROM `oepaypal_orderpayments` WHERE `oepaypal_transactionid` = ' . $oDb->quote($sTransactionId));

        return $aData;
    }

    /**
     * Delete PayPal order payment data from database.
     *
     * @param string $sPaymentId Order id.
     *
     * @return bool
     */
    public function delete($sPaymentId)
    {
        $oDb = $this->_getDb();
        $oDb->startTransaction();

        $blDeleteResult = $oDb->execute('DELETE FROM `oepaypal_orderpayments` WHERE `oepaypal_paymentid` = ' . $oDb->quote($sPaymentId));
        $blDeleteCommentResult = $oDb->execute('DELETE FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_paymentid` = ' . $oDb->quote($sPaymentId));

        $blResult = ($blDeleteResult !== false) || ($blDeleteCommentResult !== false);

        if ($blResult) {
            $oDb->commitTransaction();
        } else {
            $oDb->rollbackTransaction();
        }

        return $blResult;
    }


    /**
     * Load PayPal order payment data from Db.
     *
     * @param string $sOrderId Order id.
     *
     * @return array
     */
    public function getList($sOrderId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getAll('SELECT * FROM `oepaypal_orderpayments` WHERE `oepaypal_orderid` = ' . $oDb->quote($sOrderId) . ' ORDER BY `oepaypal_date` DESC');

        return $aData;
    }
}
