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
 * Class manages article select lists sorting
 */
class selectlist_order_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array(
        array('oxtitle', 'oxselectlist', 1, 1, 0),
        array('oxsort', 'oxobject2selectlist', 1, 0, 0),
        array('oxident', 'oxselectlist', 0, 0, 0),
        array('oxvaldesc', 'oxselectlist', 0, 0, 0),
        array('oxid', 'oxobject2selectlist', 0, 0, 1)
    )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $sSelTable = $this->_getViewName('oxselectlist');
        $sArtId = oxRegistry::getConfig()->getRequestParameter('oxid');

        $sQAdd = " from $sSelTable left join oxobject2selectlist on oxobject2selectlist.oxselnid = $sSelTable.oxid " .
                 "where oxobjectid = '$sArtId' ";

        return $sQAdd;
    }

    /**
     * Returns SQL query addon for sorting
     *
     * @return string
     */
    protected function _getSorting()
    {
        return 'order by oxobject2selectlist.oxsort ';
    }

    /**
     * Applies sorting for selection lists
     */
    public function setSorting()
    {
        $sSelId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSelect = "select * from oxobject2selectlist where oxobjectid='$sSelId' order by oxsort";

        $oList = oxNew("oxlist");
        $oList->init("oxbase", "oxobject2selectlist");
        $oList->selectString($sSelect);

        // fixing indexes
        $iSelCnt = 0;
        $aIdx2Id = array();
        foreach ($oList as $sKey => $oSel) {

            if ($oSel->oxobject2selectlist__oxsort->value != $iSelCnt) {
                $oSel->oxobject2selectlist__oxsort->setValue($iSelCnt);

                // saving new index
                $oSel->save();
            }
            $aIdx2Id[$iSelCnt] = $sKey;
            $iSelCnt++;
        }

        //
        if (($iKey = array_search(oxRegistry::getConfig()->getRequestParameter('sortoxid'), $aIdx2Id)) !== false) {
            $iDir = (oxRegistry::getConfig()->getRequestParameter('direction') == 'up') ? ($iKey - 1) : ($iKey + 1);
            if (isset($aIdx2Id[$iDir])) {
                // exchanging indexes
                $oDir1 = $oList->offsetGet($aIdx2Id[$iDir]);
                $oDir2 = $oList->offsetGet($aIdx2Id[$iKey]);

                $iCopy = $oDir1->oxobject2selectlist__oxsort->value;
                $oDir1->oxobject2selectlist__oxsort->setValue($oDir2->oxobject2selectlist__oxsort->value);
                $oDir2->oxobject2selectlist__oxsort->setValue($iCopy);

                $oDir1->save();
                $oDir2->save();
            }
        }

        $sQAdd = $this->_getQuery();

        $sQ = 'select ' . $this->_getQueryCols() . $sQAdd;
        $sCountQ = 'select count( * ) ' . $sQAdd;

        $this->_outputResponse($this->_getData($sCountQ, $sQ));
    }
}
