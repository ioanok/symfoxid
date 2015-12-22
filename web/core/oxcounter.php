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
 * Counter class
 *
 */
class oxCounter
{

    /**
     * Returns next counter value
     *
     * @param string $sIdent counter ident
     *
     * @return int
     */
    public function getNext($sIdent)
    {
        $oDb = oxDb::getDb();
        $oDb->startTransaction();

        $sQ = "SELECT `oxcount` FROM `oxcounters` WHERE `oxident` = " . $oDb->quote($sIdent) . " FOR UPDATE";

        if (($iCnt = $oDb->getOne($sQ, false, false)) === false) {
            $sQ = "INSERT INTO `oxcounters` (`oxident`, `oxcount`) VALUES (?, '0')";
            $oDb->execute($sQ, array($sIdent));
        }

        $iCnt = ((int) $iCnt) + 1;
        $sQ = "UPDATE `oxcounters` SET `oxcount` = ? WHERE `oxident` = ?";
        $oDb->execute($sQ, array($iCnt, $sIdent));

        $oDb->commitTransaction();

        return $iCnt;
    }

    /**
     * update counter value, only when it is greater than old one,
     * if counter ident not exist creates counter and sets value
     *
     * @param string  $sIdent counter ident
     * @param integer $iCount value
     *
     * @return int
     */
    public function update($sIdent, $iCount)
    {
        $oDb = oxDb::getDb();
        $oDb->startTransaction();

        $sQ = "SELECT `oxcount` FROM `oxcounters` WHERE `oxident` = " . $oDb->quote($sIdent) . " FOR UPDATE";

        if (($iCnt = $oDb->getOne($sQ, false, false)) === false) {
            $sQ = "INSERT INTO `oxcounters` (`oxident`, `oxcount`) VALUES (?, ?)";
            $blResult = $oDb->execute($sQ, array($sIdent, $iCount));
        } else {
            $sQ = "UPDATE `oxcounters` SET `oxcount` = ? WHERE `oxident` = ? AND `oxcount` < ?";
            $blResult = $oDb->execute($sQ, array($iCount, $sIdent, $iCount));
        }

        $oDb->commitTransaction();

        return $blResult;
    }
}
