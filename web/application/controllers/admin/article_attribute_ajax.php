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
 * Class controls article assignment to attributes
 */
class article_attribute_ajax extends ajaxListComponent
{

    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,         visible, multilanguage, ident
        array('oxtitle', 'oxattribute', 1, 1, 0),
        array('oxid', 'oxattribute', 0, 0, 1)
    ),
                                 'container2' => array(
                                     array('oxtitle', 'oxattribute', 1, 1, 0),
                                     array('oxid', 'oxobject2attribute', 0, 0, 1),
                                     array('oxvalue', 'oxobject2attribute', 0, 1, 1),
                                     array('oxattrid', 'oxobject2attribute', 0, 0, 1),
                                 )
    );

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $oDb = oxDb::getDb();
        $sArtId = oxRegistry::getConfig()->getRequestParameter('oxid');
        $sSynchArtId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        $sAttrViewName = $this->_getViewName('oxattribute');
        $sO2AViewName = $this->_getViewName('oxobject2attribute');
        if ($sArtId) {
            // all categories article is in
            $sQAdd = " from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxattrid " .
                     " where {$sO2AViewName}.oxobjectid = " . $oDb->quote($sArtId) . " ";
        } else {
            $sQAdd = " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxattrid " .
                     "from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxattrid " .
                     " where {$sO2AViewName}.oxobjectid = " . $oDb->quote($sSynchArtId) . " ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->_getActionIds('oxobject2attribute.oxid');
        $sOxid = oxRegistry::getConfig()->getRequestParameter('oxid');
        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sO2AViewName = $this->_getViewName('oxobject2attribute');
            $sQ = $this->_addFilter("delete $sO2AViewName.* " . $this->_getQuery());
            oxDb::getDb()->Execute($sQ);

        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", oxDb::getInstance()->quoteArray($aChosenArt));
            $sQ = "delete from oxobject2attribute where oxobject2attribute.oxid in ({$sChosenArticles}) ";
            oxDb::getDb()->Execute($sQ);
        }

    }

    /**
     * Adds attributes to article.
     */
    public function addAttr()
    {
        $aAddCat = $this->_getActionIds('oxattribute.oxid');
        $soxId = oxRegistry::getConfig()->getRequestParameter('synchoxid');

        if (oxRegistry::getConfig()->getRequestParameter('all')) {
            $sAttrViewName = $this->_getViewName('oxattribute');
            $aAddCat = $this->_getAll($this->_addFilter("select $sAttrViewName.oxid " . $this->_getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aAddCat)) {
            foreach ($aAddCat as $sAdd) {
                $oNew = oxNew("oxbase");
                $oNew->init("oxobject2attribute");
                $oNew->oxobject2attribute__oxobjectid = new oxField($soxId);
                $oNew->oxobject2attribute__oxattrid = new oxField($sAdd);
                $oNew->save();
            }

        }
    }

    /**
     * Saves attribute value
     *
     * @return null
     */
    public function saveAttributeValue()
    {
        $oDb = oxDb::getDb();
        $this->resetContentCache();

        $soxId = oxRegistry::getConfig()->getRequestParameter("oxid");
        $sAttributeId = oxRegistry::getConfig()->getRequestParameter("attr_oxid");
        $sAttributeValue = oxRegistry::getConfig()->getRequestParameter("attr_value");
        if (!$this->getConfig()->isUtf()) {
            $sAttributeValue = iconv('UTF-8', oxRegistry::getLang()->translateString("charset"), $sAttributeValue);
        }

        $oArticle = oxNew("oxarticle");
        if ($oArticle->load($soxId)) {


            if (isset($sAttributeId) && ("" != $sAttributeId)) {
                $sViewName = $this->_getViewName("oxobject2attribute");
                $sOxIdField = 'oxarticles__oxid';
                $sQuotedOxid = $oDb->quote($oArticle->$sOxIdField->value);
                $sSelect = "select * from {$sViewName} where {$sViewName}.oxobjectid= {$sQuotedOxid} and
                            {$sViewName}.oxattrid= " . $oDb->quote($sAttributeId);
                $oO2A = oxNew("oxi18n");
                $oO2A->setLanguage(oxRegistry::getConfig()->getRequestParameter('editlanguage'));
                $oO2A->init("oxobject2attribute");
                if ($oO2A->assignRecord($sSelect)) {
                    $oO2A->oxobject2attribute__oxvalue->setValue($sAttributeValue);
                    $oO2A->save();
                }
            }

        }
    }
}
