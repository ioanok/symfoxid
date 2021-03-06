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

if (!class_exists("report_searchstrings")) {
    /**
     * Search strings reports class
     */
    class Report_searchstrings extends report_base
    {

        /**
         * Name of template to render
         *
         * @return string
         */
        protected $_sThisTemplate = "report_searchstrings.tpl";

        /**
         * Current month top search strings report
         *
         * @return null
         */
        public function render()
        {
            $oDb = oxDb::getDb();

            $aDataX = array();
            $aDataY = array();

            $oSmarty = $this->getSmarty();
            $sTimeFrom = $oDb->quote(date("Y-m-d H:i:s", strtotime($oSmarty->_tpl_vars['time_from'])));
            $sTimeTo = $oDb->quote(date("Y-m-d H:i:s", strtotime($oSmarty->_tpl_vars['time_to'])));

            $sSQL = "select count(*) as nrof, oxparameter from oxlogs where oxclass = 'search' and " .
                    "oxtime >= $sTimeFrom and oxtime <= $sTimeTo group by oxparameter order by nrof desc";
            $rs = $oDb->execute($sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if ($rs->fields[1]) {
                        $aDataX[] = $rs->fields[0];
                        $aDataY[] = $rs->fields[1];
                    }
                    $rs->moveNext();
                }
            }
            $iMax = 0;
            for ($iCtr = 0; $iCtr < count($aDataX); $iCtr++) {
                if ($iMax < $aDataX[$iCtr]) {
                    $iMax = $aDataX[$iCtr];
                }
            }

            $aPoints = array();
            $aPoints["0"] = 0;
            $aAligns["0"] = 'report_searchstrings_scale_aligns_left"';
            $iTenth = strlen($iMax) - 1;
            if ($iTenth < 1) {
                $iScaleMax = $iMax;
                $aPoints["" . (round(($iMax / 2))) . ""] = $iMax / 2;
                $aAligns["" . (round(($iMax / 2))) . ""] = 'report_searchstrings_scale_aligns_center" width="' . (720 / 3) . '"';
                $aPoints["" . $iMax . ""] = $iMax;
                $aAligns["" . $iMax . ""] = 'report_searchstrings_scale_aligns_right" width="' . (720 / 3) . '"';
            } else {
                $iDeg = bcpow(10, $iTenth);
                //$iScaleMax = $iDeg * (round($iMax/$iDeg));
                $iScaleMax = $iMax;
                $ctr = 0;
                for ($iCtr = 10; $iCtr > 0; $iCtr--) {
                    $aPoints["" . (round(($ctr))) . ""] = $ctr += $iScaleMax / 10;
                    $aAligns["" . (round(($ctr))) . ""] = 'report_searchstrings_scale_aligns_center" width="' . (720 / 10) . '"';
                }
                $aAligns["" . (round(($ctr))) . ""] = 'report_searchstrings_scale_aligns_right" width="' . (720 / 10) . '"';
            }

            $aAligns["0"] .= ' width="' . (720 / count($aAligns)) . '"';

            for ($iCtr = 0; $iCtr < count($aDataY); $iCtr++) {
                $aDataVals[$aDataY[$iCtr]] = round($aDataX[$iCtr] / $iMax * 100);
            }

            if (count($aDataY) > 0) {
                $oSmarty->assign("drawStat", true);
            } else {
                $oSmarty->assign("drawStat", false);
            }

            $oSmarty->assign("classes", array($aAligns));
            $oSmarty->assign("allCols", count($aAligns));
            $oSmarty->assign("cols", count($aAligns));
            $oSmarty->assign("percents", array($aDataVals));
            $oSmarty->assign("y", $aDataY);

            return parent::render();
        }

        /**
         * Current week top search strings report
         */
        public function graph1()
        {
            $myConfig = $this->getConfig();
            $oDb = oxDb::getDb();

            $aDataX = array();
            $aDataY = array();

            $sTimeFrom = $oDb->quote(date("Y-m-d H:i:s", strtotime(oxRegistry::getConfig()->getRequestParameter("time_from"))));
            $sTimeTo = $oDb->quote(date("Y-m-d H:i:s", strtotime(oxRegistry::getConfig()->getRequestParameter("time_to"))));

            $sSQL = "select count(*) as nrof, oxparameter from oxlogs where oxclass = 'search' and oxtime >= $sTimeFrom and oxtime <= $sTimeTo group by oxparameter order by nrof desc";
            $rs = $oDb->execute($sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if ($rs->fields[1]) {
                        $aDataX[] = $rs->fields[0];
                        $aDataY[] = $rs->fields[1];
                    }
                    $rs->moveNext();
                }
            }

            header("Content-type: image/png");

            // New graph with a drop shadow
            $graph = new Graph(800, max(640, 20 * count($aDataX)));
            $graph->setBackgroundImage($myConfig->getImageDir(true) . "/reportbgrnd.jpg", BGIMG_FILLFRAME);

            // Use a "text" X-scale
            $graph->setScale("textlin");

            $top = 60;
            $bottom = 30;
            $left = 80;
            $right = 30;
            $graph->set90AndMargin($left, $right, $top, $bottom);

            // Label align for X-axis
            $graph->xaxis->setLabelAlign('right', 'center', 'right');

            // Label align for Y-axis
            $graph->yaxis->setLabelAlign('center', 'bottom');

            $graph->setShadow();
            // Description
            $graph->xaxis->setTickLabels($aDataY);

            // Set title and subtitle
            $graph->title->set("Suchw�rter");

            // Use built in font
            $graph->title->setFont(FF_FONT1, FS_BOLD);

            // Create the bar plot
            $bplot = new BarPlot($aDataX);
            $bplot->setFillGradient("navy", "lightsteelblue", GRAD_VER);
            $bplot->setLegend("Hits");

            $graph->add($bplot);

            // Finally output the  image
            $graph->stroke();
        }
    }
}
