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
 * Admin statistics service setting manager.
 * Collects statistics service settings, updates it on user submit, etc.
 * Admin Menu: Statistics -> Show -> Clear Log.
 */
class Statistic_Service extends oxAdminDetails
{

    /**
     * Executes parent method parent::render() and returns name of template
     * file "statistic_service.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $sSql = "select count(*) from oxlogs where oxshopid = '" . $this->getConfig()->getShopId() . "'";
        $this->_aViewData['iLogCount'] = oxDb::getDb()->getOne($sSql, false, false);

        return "statistic_service.tpl";
    }

    /**
     * Performs cleanup of statistic data for selected period.
     */
    public function cleanup()
    {
        $iTimeFrame = oxRegistry::getConfig()->getRequestParameter("timeframe");
        $dNow = time();
        $iTimestamp = mktime(
            date("H", $dNow),
            date("i", $dNow),
            date("s", $dNow),
            date("m", $dNow),
            date("d", $dNow) - $iTimeFrame,
            date("Y", $dNow)
        );
        $sDeleteFrom = date("Y-m-d H:i:s", $iTimestamp);

        $oDb = oxDb::getDb();
        $oDb->Execute("delete from oxlogs where oxtime < " . $oDb->quote($sDeleteFrom));
    }
}
