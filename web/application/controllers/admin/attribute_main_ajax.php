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
 * Class manages article attributes
 */
class attribute_main_ajax extends ajaxListComponent
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
        array('oxean', 'oxarticles', 1, 0, 0),
        array('oxmpn', 'oxarticles', 0, 0, 0),
        array('oxprice', 'oxarticles', 0, 0, 0),
        array('oxstock', 'oxarticles', 0, 0, 0),
        array('oxid', 'oxarticles', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxartnum', 'oxarticles', 1, 0, 0),
                                     array('oxtitle', 'oxarticles', 1, 1, 0),
                                     array('oxean', 'oxarticles', 1, 0, 0),
                                     array('oxmpn', 'oxarticles', 0, 0, 0),
                                     array('oxprice', 'oxarticles', 0, 0, 0),
                                     array('oxstock', 'oxarticles', 0, 0, 0),
                                     array('oxid', 'oxobject2attribute', 0, 0, 1)
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
        $oDb = oxDb::getDb();

        $sArticleTable = $this->_getViewName('oxarticles');
        $sOCatView = $this->_getViewName('oxobject2category');
        $sOAttrView = $this->_getViewName('oxobject2attribute');

        $sDelId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchDelId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$sDelId) {
            // performance
            $sQAdd = " from $sArticleTable where 1 ";
            $sQAdd .= $myConfig->getConfigParam('blVariantsSelection') ? '' : " and $sArticleTable.oxparentid = '' ";
        } elseif ($sSynchDelId && $sDelId != $sSynchDelId) {
            // selected category ?
            $blVariantsSelectionParameter = $myConfig->getConfigParam('blVariantsSelection');
            $sSqlIfTrue = " ( {$sArticleTable}.oxid=oxobject2category.oxobjectid " .
                          "or {$sArticleTable}.oxparentid=oxobject2category.oxobjectid)";
            $sSqlIfFalse = " {$sArticleTable}.oxid=oxobject2category.oxobjectid ";
            $sVariantSelectionSql = $blVariantsSelectionParameter ? $sSqlIfTrue : $sSqlIfFalse;
            $sQAdd = " from {$sOCatView} as oxobject2category left join {$sArticleTable} on {$sVariantSelectionSql}" .
                     " where oxobject2category.oxcatnid = " . $oDb->quote($sDelId) . " ";
        } else {
            $sQAdd = " from {$sOAttrView} left join {$sArticleTable} " .
                     "on {$sArticleTable}.oxid={$sOAttrView}.oxobjectid " .
                     "where {$sOAttrView}.oxattrid = " . $oDb->quote($sDelId) .
                     " and {$sArticleTable}.oxid is not null ";
        }

        if ($sSynchDelId && $sSynchDelId != $sDelId) {
            $sQAdd .= " and {$sArticleTable}.oxid not in ( select {$sOAttrView}.oxobjectid from {$sOAttrView} " .
                      "where {$sOAttrView}.oxattrid = " . $oDb->quote($sSynchDelId) . " ) ";
        }

        return $sQAdd;
    }

    /**
     * Adds filter SQL to current query
     *
     * @param string $sQ query to add filter condition
     *
     * @return string
     */
    protected function _addFilter($sQ)
    {
        $sQ = parent::_addFilter($sQ);

        // display variants or not ?
        if ($this->getConfig()->getConfigParam('blVariantsSelection')) {
            $sQ .= ' group by ' . $this->_getViewName('oxarticles') . '.oxid ';

            $oStr = getStr();
            if ($oStr->strpos($sQ, "select count( * ) ") === 0) {
                $sQ = "select count( * ) from ( {$sQ} ) as _cnttable";
            }
        }

        return $sQ;
    }

    /**
     * Removes article from Attribute list
     */
    public function removeAttrArticle()
    {
        $aChosenCat = $this->_getActionIds('oxobject2attribute.oxid');


        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sO2AttributeView = $this->_getViewName('oxobject2attribute');

            $sQ = parent::_addFilter("delete $sO2AttributeView.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);
        } elseif (is_array($aChosenCat)) {
            $sChosenCategories = implode(", ", oxDb::getInstance()->quoteArray($aChosenCat));
            $sQ = "delete from oxobject2attribute where oxobject2attribute.oxid in (" . $sChosenCategories . ") ";
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds article to Attribute list
     */
    public function addAttrArticle()
    {
        $aAddArticle = $this->_getActionIds('oxarticles.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // adding
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sArticleTable = $this->_getViewName('oxarticles');
            $aAddArticle = $this->_getAll($this->_addFilter("select $sArticleTable.oxid " . $this->_getQuery()));
        }

        $oAttribute = oxNew("oxattribute");

        if ($oAttribute->load($soxId) && is_array($aAddArticle)) {
            foreach ($aAddArticle as $sAdd) {
                $oNewGroup = oxNew("oxbase");
                $oNewGroup->init("oxobject2attribute");
                $oNewGroup->oxobject2attribute__oxobjectid = new oxField($sAdd);
                $oNewGroup->oxobject2attribute__oxattrid = new oxField($oAttribute->oxattribute__oxid->value);
                $oNewGroup->save();

            }
        }
    }
}
