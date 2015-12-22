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
 * Order payment comment db gateway class.
 */
class oePayPalOrderPaymentCommentDbGateway extends oePayPalModelDbGateway
{
    /**
     * Save PayPal order payment comment data to database.
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

        $sSql = 'INSERT INTO `oepaypal_orderpaymentcomments` SET ';
        $sSql .= implode(', ', $aSql);
        $sSql .= ' ON DUPLICATE KEY UPDATE ';
        $sSql .= ' `oepaypal_commentid`=LAST_INSERT_ID(`oepaypal_commentid`), ';
        $sSql .= implode(', ', $aSql);
        $oDb->execute($sSql);

        $iCommentId = $aData['oepaypal_commentid'];
        if (empty($iCommentId)) {
            $iCommentId = $oDb->getOne('SELECT LAST_INSERT_ID()');
        }

        return $iCommentId;
    }

    /**
     * Load PayPal order payment comment data from Db.
     *
     * @param string $sPaymentId order id
     *
     * @return array
     */
    public function getList($sPaymentId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getAll('SELECT * FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_paymentid` = ' . $oDb->quote($sPaymentId) . ' ORDER BY `oepaypal_date` DESC');

        return $aData;
    }

    /**
     * Load PayPal order payment comment data from Db.
     *
     * @param string $sCommentId Order id.
     *
     * @return array
     */
    public function load($sCommentId)
    {
        $oDb = $this->_getDb();
        $aData = $oDb->getRow('SELECT * FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_commentid` = ' . $oDb->quote($sCommentId));

        return $aData;
    }

    /**
     * Delete PayPal order payment comment data from database.
     *
     * @param string $sCommentId Order id.
     *
     * @return bool
     */
    public function delete($sCommentId)
    {
        $oDb = $this->_getDb();
        $oDb->startTransaction();

        $blDeleteResult = $oDb->execute('DELETE FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_commentid` = ' . $oDb->quote($sCommentId));

        $blResult = ($blDeleteResult !== false);

        if ($blResult) {
            $oDb->commitTransaction();
        } else {
            $oDb->rollbackTransaction();
        }

        return $blResult;
    }
}
