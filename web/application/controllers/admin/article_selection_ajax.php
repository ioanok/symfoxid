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
 * Class controls article assignment to selection lists
 */
class article_selection_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxtitle', 'oxselectlist', 1, 1, 0),
        array('oxident', 'oxselectlist', 1, 0, 0),
        array('oxvaldesc', 'oxselectlist', 1, 0, 0),
        array('oxid', 'oxselectlist', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxtitle', 'oxselectlist', 1, 1, 0),
                                     array('oxident', 'oxselectlist', 1, 0, 0),
                                     array('oxvaldesc', 'oxselectlist', 1, 0, 0),
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
        $sSLViewName = $this->_getViewName('oxselectlist');
        $sArtViewName = $this->_getViewName('oxarticles');
        $oDb = oxDb::getDb();

        $sArtId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchArtId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        $sOxid = ($sArtId) ? $sArtId : $sSynchArtId;
        $sQ = "select oxparentid from {$sArtViewName} where oxid = " . $oDb->quote($sOxid) . " and oxparentid != '' ";
        $sQ .= "and (select count(oxobjectid) from oxobject2selectlist " .
               "where oxobjectid = " . $oDb->quote($sOxid) . ") = 0";
        $sParentId = oxDb::getDb()->getOne($sQ, false, false);

        // all selectlists article is in
        $sQAdd = " from oxobject2selectlist left join {$sSLViewName} " .
                 "on {$sSLViewName}.oxid=oxobject2selectlist.oxselnid  " .
                 "where oxobject2selectlist.oxobjectid = " . $oDb->quote($sOxid) . " ";
        if ($sParentId) {
            $sQAdd .= "or oxobject2selectlist.oxobjectid = " . $oDb->quote($sParentId) . " ";
        }
        // all not assigned selectlists
        if ($sSynchArtId) {
            $sQAdd = " from {$sSLViewName}  " .
                     "where {$sSLViewName}.oxid not in ( select oxobject2selectlist.oxselnid {$sQAdd} ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes article selection lists.
     */
    public function removeSel()
    {
        $aChosenArt = $this->_getActionIds('oxobject2selectlist.oxid');
        if (oxRegistry::getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxobject2selectlist.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);
        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", oxDb::getInstance()->quoteArray($aChosenArt));
            $sQ = "delete from oxobject2selectlist " .
                  "where oxobject2selectlist.oxid in (" . $sChosenArticles . ") ";
            oxDb::getDb()->Execute($sQ);
        }

    }

    /**
     * Adds selection lists to article.
     */
    public function addSel()
    {
        $aAddSel = $this->_getActionIds('oxselectlist.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // adding
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sSLViewName = $this->_getViewName('oxselectlist');
            $aAddSel = $this->_getAll($this->_addFilter("select $sSLViewName.oxid " . $this->_getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aAddSel)) {
            $oDb = oxDb::getDb();
            foreach ($aAddSel as $sAdd) {
                $oNew = oxNew("oxbase");
                $oNew->init("oxobject2selectlist");
                $sObjectIdField = 'oxobject2selectlist__oxobjectid';
                $sSelectetionIdField = 'oxobject2selectlist__oxselnid';
                $sOxSortField = 'oxobject2selectlist__oxsort';
                $oNew->$sObjectIdField = new oxField($soxId);
                $oNew->$sSelectetionIdField = new oxField($sAdd);
                $sSql = "select max(oxsort) + 1 from oxobject2selectlist where oxobjectid =  {$oDb->quote($soxId)} ";
                $oNew->$sOxSortField = new oxField(( int ) $oDb->getOne($sSql, false, false));
                $oNew->save();
            }

        }
    }
}
