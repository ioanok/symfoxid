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
 * Class manages article select lists configuration
 */
class selectlist_main_ajax extends ajaxListComponent
{

    /**
     * If true extended column selection will be build
     *
     * @var bool
     */
    protected $_blAllowExtColumns = true;

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxartnum', 'oxarticles', 1, 0, 0),
        array('oxtitle', 'oxarticles', 1, 1, 0),
        array('oxean', 'oxarticles', 0, 0, 0),
        array('oxmpn', 'oxarticles', 0, 0, 0),
        array('oxprice', 'oxarticles', 0, 0, 0),
        array('oxstock', 'oxarticles', 0, 0, 0),
        array('oxid', 'oxarticles', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxartnum', 'oxarticles', 1, 0, 0),
                                     array('oxtitle', 'oxarticles', 1, 1, 0),
                                     array('oxean', 'oxarticles', 0, 0, 0),
                                     array('oxmpn', 'oxarticles', 0, 0, 0),
                                     array('oxprice', 'oxarticles', 0, 0, 0),
                                     array('oxstock', 'oxarticles', 0, 0, 0),
                                     array('oxid', 'oxobject2selectlist', 0, 0, 1),
                                     array('oxid', 'oxarticles', 0, 0, 1)
                                 ),
                                 'container3' => array(
                                     array('oxtitle', 'oxselectlist', 1, 1, 0),
                                     array('oxsort', 'oxobject2selectlist', 1, 0, 0),
                                     array('oxident', 'oxselectlist', 0, 0, 0),
                                     array('oxvaldesc', 'oxselectlist', 0, 0, 0),
                                     array('oxid', 'oxselectlist', 0, 0, 1)
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $myConfig = $this->getConfig();

        // looking for table/view
        $sArtTable = $this->_getViewName('oxarticles');
        $sCatTable = $this->_getViewName('oxcategories');
        $sO2CView = $this->_getViewName('oxobject2category');
        $oDb = oxDb::getDb();
        $sSelId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchSelId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sSelId) {
            // performance
            $sQAdd = " from $sArtTable where 1 ";
            $sQAdd .= $myConfig->getConfigParam('blVariantsSelection') ? '' : " and $sArtTable.oxparentid = '' ";
        } else {
            // selected category ?
            if ($sSynchSelId && $sSelId != $sSynchSelId) {
                $sQAdd = " from $sO2CView as oxobject2category left join $sArtTable on ";
                $sQAdd .= $myConfig->getConfigParam('blVariantsSelection') ? " ( $sArtTable.oxid=oxobject2category.oxobjectid or $sArtTable.oxparentid=oxobject2category.oxobjectid ) " : " $sArtTable.oxid=oxobject2category.oxobjectid ";
                $sQAdd .= " where oxobject2category.oxcatnid = " . $oDb->quote($sSelId);
            } else {
                $sQAdd = " from $sArtTable left join oxobject2selectlist on $sArtTable.oxid=oxobject2selectlist.oxobjectid ";
                $sQAdd .= " where oxobject2selectlist.oxselnid = " . $oDb->quote($sSelId);
            }
        }

        if ($sSynchSelId && $sSynchSelId != $sSelId) {
            // performance
            $sQAdd .= " and $sArtTable.oxid not in ( select oxobject2selectlist.oxobjectid from oxobject2selectlist ";
            $sQAdd .= " where oxobject2selectlist.oxselnid = " . $oDb->quote($sSynchSelId) . " ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes article from Selection list
     */
    public function removeArtFromSel()
    {
        $aChosenArt = $this->_getActionIds('oxobject2selectlist.oxid');




        if (oxRegistry::getConfig()->getRequestParameter('all')) {

            $sQ = parent::_addFilter("delete oxobject2selectlist.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenArt)) {
            $sQ = "delete from oxobject2selectlist where oxobject2selectlist.oxid in (" . implode(", ", oxDb::getInstance()->quoteArray($aChosenArt)) . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds article to Selection list
     */
    public function addArtToSel()
    {
        $aAddArticle = $this->_getActionIds('oxarticles.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sArtTable = $this->_getViewName('oxarticles');
            $aAddArticle = $this->_getAll(parent::_addFilter("select $sArtTable.oxid " . $this->_getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aAddArticle)) {
            $oDb = oxDb::getDb();
            foreach ($aAddArticle as $sAdd) {
                $oNewGroup = oxNew("oxbase");
                $oNewGroup->init("oxobject2selectlist");
                $oNewGroup->oxobject2selectlist__oxobjectid = new oxField($sAdd);
                $oNewGroup->oxobject2selectlist__oxselnid = new oxField($soxId);
                $oNewGroup->oxobject2selectlist__oxsort = new oxField(( int ) $oDb->getOne("select max(oxsort) + 1 from oxobject2selectlist where oxobjectid =  " . $oDb->quote($sAdd) . " ", false, false));
                $oNewGroup->save();

            }
        }


    }
}
