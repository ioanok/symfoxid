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
 * Class manages statistics configuration
 */
class statistic_main_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxtitle', 'oxstat', 1, 0, 0),
        array('oxid', 'oxstat', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxtitle', 'oxstat', 1, 0, 0),
                                     array('oxid', 'oxstat', 0, 0, 1)
                                 )
    );

    /**
     * Formats and returns statiistics configuration related data array for ajax response
     *
     * @param string $sCountQ this param currently is not used as thsi mathod overrides default function behaviour
     * @param string $sQ      this param currently is not used as thsi mathod overrides default function behaviour
     *
     * @return array
     */
    protected function _getData($sCountQ, $sQ)
    {
        $aResponse['startIndex'] = $this->_getStartIndex();
        $aResponse['sort'] = '_' . $this->_getSortCol();
        $aResponse['dir'] = $this->_getSortDir();

        // all possible reports
        $aReports = oxRegistry::getSession()->getVariable("allstat_reports");
        $sSynchId = oxRegistry::getConfig()->getRequestParameter("synchoxid");
        $sOxId = oxRegistry::getConfig()->getRequestParameter("oxid");

        $sStatId = $sSynchId ? $sSynchId : $sOxId;
        $oStat = oxNew('oxstatistic');
        $oStat->load($sStatId);
        $aStatData = unserialize($oStat->oxstatistics__oxvalue->value);

        $aData = array();
        $iCnt = 0;
        $oStr = getStr();

        // filter data
        $aFilter = oxRegistry::getConfig()->getRequestParameter("aFilter");
        $sFilter = (is_array($aFilter) && isset($aFilter['_0'])) ? $oStr->preg_replace('/^\*/', '%', $aFilter['_0']) : null;

        foreach ($aReports as $oReport) {

            if ($sSynchId) {
                if (is_array($aStatData) && in_array($oReport->filename, $aStatData)) {
                    continue;
                }
            } else {
                if (!is_array($aStatData) || !in_array($oReport->filename, $aStatData)) {
                    continue;
                }
            }

            // checking filter
            if ($sFilter && !$oStr->preg_match("/^" . preg_quote($sFilter) . "/i", $oReport->name)) {
                continue;
            }

            $aData[$iCnt]['_0'] = $oReport->name;
            $aData[$iCnt]['_1'] = $oReport->filename;
            $iCnt++;
        }

        // ordering ...
        if (oxRegistry::getConfig()->getRequestParameter("dir")) {
            if ('asc' == oxRegistry::getConfig()->getRequestParameter("dir")) {
                usort($aData, array($this, "sortAsc"));
            } else {
                usort($aData, array($this, "sortDesc"));
            }
        } else {
            usort($aData, array($this, "sortAsc"));
        }

        $aResponse['records'] = $aData;
        $aResponse['totalRecords'] = count($aReports);

        return $aResponse;


    }

    /**
     * Callback function used to apply ASC sorting
     *
     * @param array $oOne first item to check sorting
     * @param array $oSec second item to check sorting
     *
     * @return int
     */
    public function sortAsc($oOne, $oSec)
    {
        if ($oOne['_0'] == $oSec['_0']) {
            return 0;
        }

        return ($oOne['_0'] < $oSec['_0']) ? -1 : 1;
    }

    /**
     * Callback function used to apply ASC sorting
     *
     * @param array $oOne first item to check sorting
     * @param array $oSec second item to check sorting
     *
     * @return int
     *
     */
    public function sortDesc($oOne, $oSec)
    {
        if ($oOne['_0'] == $oSec['_0']) {
            return 0;
        }

        return ($oOne['_0'] > $oSec['_0']) ? -1 : 1;
    }


    /**
     * Removes selected report(s) from generating list.
     */
    public function removeReportFromList()
    {
        $aReports = oxRegistry::getSession()->getVariable("allstat_reports");
        $soxId = oxRegistry::getConfig()->getRequestParameter('oxid');

        // assigning all items
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $aStats = array();
            foreach ($aReports as $oRep) {
                $aStats[] = $oRep->filename;
            }
        } else {
            $aStats = $this->_getActionIds('oxstat.oxid');
        }

        $oStat = oxNew('oxstatistic');
        if (is_array($aStats) && $oStat->load($soxId)) {
            $aStatData = $oStat->getReports();

            // additional check
            foreach ($aReports as $oRep) {
                if (in_array($oRep->filename, $aStats) && ($iPos = array_search($oRep->filename, $aStatData)) !== false) {
                    unset($aStatData[$iPos]);
                }
            }

            $oStat->setReports($aStatData);
            $oStat->save();
        }
    }

    /**
     * Adds selected report(s) to generating list.
     */
    public function addReportToList()
    {
        $aReports = oxRegistry::getSession()->getVariable("allstat_reports");
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // assigning all items
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $aStats = array();
            foreach ($aReports as $oRep) {
                $aStats[] = $oRep->filename;
            }
        } else {
            $aStats = $this->_getActionIds('oxstat.oxid');
        }

        $oStat = oxNew('oxstatistic');
        if ($oStat->load($soxId)) {
            $aStatData = (array) $oStat->getReports();


            // additional check
            foreach ($aReports as $oRep) {
                if (in_array($oRep->filename, $aStats) && !in_array($oRep->filename, $aStatData)) {
                    $aStatData[] = $oRep->filename;
                }
            }

            $oStat->setReports($aStatData);
            $oStat->save();
        }
    }
}
