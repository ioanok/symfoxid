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
 * Class controls article assignment to accessories
 */
class article_accessories_ajax extends ajaxListComponent
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
                                     array('oxid', 'oxaccessoire2article', 0, 0, 1)
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
        $sSelId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchSelId = oxRegistry::getConfig()->getRequestParameter('synchoxid');
        $oDb = oxDb::getDb();

        $sArticleTable = $this->_getViewName('oxarticles');
        $sView = $this->_getViewName('oxobject2category');

        // category selected or not ?
        if (!$sSelId) {
            $sQAdd = " from {$sArticleTable} where 1 ";
            $sQAdd .= $myConfig->getConfigParam('blVariantsSelection') ? '' : " and {$sArticleTable}.oxparentid = '' ";
        } else {
            // selected category ?
            if ($sSynchSelId && $sSelId != $sSynchSelId) {
                $blVariantsSelectionParameter = $myConfig->getConfigParam('blVariantsSelection');
                $sSqlIfTrue = " ( {$sArticleTable}.oxid=$sView.oxobjectid " .
                              "or {$sArticleTable}.oxparentid=$sView.oxobjectid )";
                $sSqlIfFals = " {$sArticleTable}.oxid=$sView.oxobjectid ";
                $sVariantSelectionSql = $blVariantsSelectionParameter ? $sSqlIfTrue : $sSqlIfFals;
                
                $sQAdd = " from $sView left join {$sArticleTable} on {$sVariantSelectionSql}" .
                         " where $sView.oxcatnid = " . $oDb->quote($sSelId) . " ";
            } else {
                $sQAdd = " from oxaccessoire2article left join {$sArticleTable} " .
                         "on oxaccessoire2article.oxobjectid={$sArticleTable}.oxid " .
                         " where oxaccessoire2article.oxarticlenid = " . $oDb->quote($sSelId) . " ";
            }
        }

        if ($sSynchSelId && $sSynchSelId != $sSelId) {
            // performance
            $sSubSelect .= " select oxaccessoire2article.oxobjectid from oxaccessoire2article ";
            $sSubSelect .= " where oxaccessoire2article.oxarticlenid = " . $oDb->quote($sSynchSelId) . " ";
            $sQAdd .= " and {$sArticleTable}.oxid not in ( $sSubSelect ) ";
        }

        // skipping self from list
        $sId = ($sSynchSelId) ? $sSynchSelId : $sSelId;
        $sQAdd .= " and {$sArticleTable}.oxid != " . $oDb->quote($sId) . " ";

        // creating AJAX component
        return $sQAdd;
    }

    /**
     * Removing article form accessories article list
     */
    public function removeArticleAcc()
    {
        $aChosenArt = $this->_getActionIds('oxaccessoire2article.oxid');
        // removing all
        if (oxRegistry::getConfig()->getRequestParameter('all')) {

            $sQ = $this->_addFilter("delete oxaccessoire2article.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", oxDb::getInstance()->quoteArray($aChosenArt));
            $sQ = "delete from oxaccessoire2article where oxaccessoire2article.oxid in ({$sChosenArticles}) ";
            oxDb::getDb()->Execute($sQ);
        }


    }

    /**
     * Adding article to accessories article list
     */
    public function addArticleAcc()
    {
        $oArticle = oxNew("oxarticle");
        $aChosenArt = $this->_getActionIds('oxarticles.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        // adding
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sArtTable = $this->_getViewName('oxarticles');
            $aChosenArt = $this->_getAll(parent::_addFilter("select $sArtTable.oxid " . $this->_getQuery()));
        }

        if ($oArticle->load($soxId) && $soxId && $soxId != "-1" && is_array($aChosenArt)) {
            foreach ($aChosenArt as $sChosenArt) {
                $oNewGroup = oxNew("oxbase");
                $oNewGroup->init("oxaccessoire2article");
                $oNewGroup->oxaccessoire2article__oxobjectid = new oxField($sChosenArt);
                $oNewGroup->oxaccessoire2article__oxarticlenid = new oxField($oArticle->oxarticles__oxid->value);
                $oNewGroup->oxaccessoire2article__oxsort = new oxField(0);
                $oNewGroup->save();
            }

        }
    }
}
