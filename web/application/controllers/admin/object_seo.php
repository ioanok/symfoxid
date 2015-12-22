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
 * Base seo config class
 */
class Object_Seo extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(),
     * and returns name of template file
     * "object_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        if ($sType = $this->_getType()) {
            $oObject = oxNew($sType);
            if ($oObject->load($this->getEditObjectId())) {
                $oOtherLang = $oObject->getAvailableInLangs();
                if (!isset($oOtherLang[$iLang])) {
                    $oObject->loadInLang(key($oOtherLang), $this->getEditObjectId());
                }
                $this->_aViewData['edit'] = $oObject;
            }

        }

        $iLang = $this->getEditLang();
        $aLangs = oxRegistry::getLang()->getLanguageNames();
        foreach ($aLangs as $sLangId => $sLanguage) {
            $oLang = new stdClass();
            $oLang->sLangDesc = $sLanguage;
            $oLang->selected = ($sLangId == $iLang);
            $this->_aViewData['otherlang'][$sLangId] = clone $oLang;
        }

        return 'object_seo.tpl';
    }

    /**
     * Saves selection list parameters changes.
     */
    public function save()
    {
        // saving/updating seo params
        if (($sOxid = $this->_getSaveObjectId())) {
            $aSeoData = oxRegistry::getConfig()->getRequestParameter('aSeoData');
            $iShopId = $this->getConfig()->getShopId();
            $iLang = $this->getEditLang();

            // checkbox handling
            if (!isset($aSeoData['oxfixed'])) {
                $aSeoData['oxfixed'] = 0;
            }

            $sParams = $this->_getAdditionalParams($aSeoData);

            $oEncoder = $this->_getEncoder();
            // marking self and page links as expired
            $oEncoder->markAsExpired($sOxid, $iShopId, 1, $iLang, $sParams);

            // saving
            $oEncoder->addSeoEntry(
                $sOxid, $iShopId, $iLang, $this->_getStdUrl($sOxid),
                $aSeoData['oxseourl'], $this->_getSeoEntryType(), $aSeoData['oxfixed'],
                trim($aSeoData['oxkeywords']), trim($aSeoData['oxdescription']), $this->processParam($aSeoData['oxparams']), true, $this->_getAltSeoEntryId()
            );
        }
    }

    /**
     * Gets additional params from aSeoData['oxparams'] if it is set.
     *
     * @param array $aSeoData Seo data array
     *
     * @return null|string
     */
    protected function _getAdditionalParams($aSeoData)
    {
        $sParams = null;
        if (isset($aSeoData['oxparams'])) {
            if (preg_match('/([a-z]*#)?(?<objectseo>[a-z0-9]+)(#[0-9])?/i', $aSeoData['oxparams'], $aMatches)) {
                $sQuotedObjectSeoId = oxDb::getDb()->quote($aMatches['objectseo']);
                $sParams = "oxparams = {$sQuotedObjectSeoId}";
            }
        }
        return $sParams;
    }

    /**
     * Returns id of object which must be saved
     *
     * @return string
     */
    protected function _getSaveObjectId()
    {
        return $this->getEditObjectId();
    }

    /**
     * Returns object seo data
     *
     * @param string $sMetaType meta data type (oxkeywords/oxdescription)
     *
     * @return string
     */
    public function getEntryMetaData($sMetaType)
    {
        return $this->_getEncoder()->getMetaData($this->getEditObjectId(), $sMetaType, $this->getConfig()->getShopId(), $this->getEditLang());
    }

    /**
     * Returns TRUE if current seo entry has fixed state
     *
     * @return bool
     */
    public function isEntryFixed()
    {
        $iLang = (int) $this->getEditLang();
        $iShopId = $this->getConfig()->getShopId();

        $sQ = "select oxfixed from oxseo where
                   oxseo.oxobjectid = " . oxDb::getDb()->quote($this->getEditObjectId()) . " and
                   oxseo.oxshopid = '{$iShopId}' and oxseo.oxlang = {$iLang} and oxparams = '' ";

        return (bool) oxDb::getDb()->getOne($sQ, false, false);
    }

    /**
     * Returns url type
     */
    protected function _getType()
    {
    }

    /**
     * Returns objects std url
     *
     * @param string $sOxid object id
     *
     * @return string
     */
    protected function _getStdUrl($sOxid)
    {
        if ($sType = $this->_getType()) {
            $oObject = oxNew($sType);
            if ($oObject->load($sOxid)) {
                return $oObject->getBaseStdLink($this->getEditLang(), true, false);
            }
        }
    }

    /**
     * Returns edit language id
     *
     * @return int
     */
    public function getEditLang()
    {
        return $this->_iEditLang;
    }

    /**
     * Returns alternative seo entry id
     */
    protected function _getAltSeoEntryId()
    {
    }

    /**
     * Returns seo entry type
     *
     * @return string
     */
    protected function _getSeoEntryType()
    {
        return $this->_getType();
    }

    /**
     * Processes parameter before writing to db
     *
     * @param string $sParam parameter to process
     *
     * @return string
     */
    public function processParam($sParam)
    {
        return $sParam;
    }

    /**
     * Returns current object type seo encoder object
     */
    protected function _getEncoder()
    {
    }

    /**
     * Returns seo uri
     */
    public function getEntryUri()
    {
    }

    /**
     * Returns true if SEO object id has suffix enabled. Default is FALSE
     *
     * @return bool
     */
    public function isEntrySuffixed()
    {
        return false;
    }

    /**
     * Returns TRUE if seo object supports suffixes. Default is FALSE
     *
     * @return bool
     */
    public function isSuffixSupported()
    {
        return false;
    }

    /**
     * Returns FALSE, as this view does not support category selector
     *
     * @return bool
     */
    public function showCatSelect()
    {
        return false;
    }

    /**
     * Returns FALSE, as this view does not support active selection type
     *
     * @return bool
     */
    public function getActCatType()
    {
        return false;
    }
}
