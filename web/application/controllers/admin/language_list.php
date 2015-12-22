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
 * Admin selectlist list manager.
 */
class Language_List extends oxAdminList
{

    /**
     * Default sorting parameter.
     *
     * @var string
     */
    protected $_sDefSortField = 'sort';

    /**
     * Default sorting order.
     *
     * @var string
     */
    protected $_sDefSortOrder = 'asc';

    /**
     * Checks for Malladmin rights
     *
     * @return null
     */
    public function deleteEntry()
    {
        $myConfig = $this->getConfig();


        $sOxId = $this->getEditObjectId();

        $aLangData['params'] = $myConfig->getConfigParam('aLanguageParams');
        $aLangData['lang'] = $myConfig->getConfigParam('aLanguages');
        $aLangData['urls'] = $myConfig->getConfigParam('aLanguageURLs');
        $aLangData['sslUrls'] = $myConfig->getConfigParam('aLanguageSSLURLs');

        $iBaseId = (int) $aLangData['params'][$sOxId]['baseId'];

        // preventing deleting main language with base id = 0
        if ($iBaseId == 0) {
            $oEx = oxNew("oxExceptionToDisplay");
            $oEx->setMessage('LANGUAGE_DELETINGMAINLANG_WARNING');
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);

            return;
        }

        // unsetting selected lang from languages arrays
        unset($aLangData['params'][$sOxId]);
        unset($aLangData['lang'][$sOxId]);
        unset($aLangData['urls'][$iBaseId]);
        unset($aLangData['sslUrls'][$iBaseId]);

        //saving languages info back to DB
        $myConfig->saveShopConfVar('aarr', 'aLanguageParams', $aLangData['params']);
        $myConfig->saveShopConfVar('aarr', 'aLanguages', $aLangData['lang']);
        $myConfig->saveShopConfVar('arr', 'aLanguageURLs', $aLangData['urls']);
        $myConfig->saveShopConfVar('arr', 'aLanguageSSLURLs', $aLangData['sslUrls']);

        //if deleted language was default, setting defalt lang to 0
        if ($iBaseId == $myConfig->getConfigParam('sDefaultLang')) {
            $myConfig->saveShopConfVar('str', 'sDefaultLang', 0);
        }

        // reseting all multilanguage DB fields with deleted lang id
        // to default value
        $this->_resetMultiLangDbFields($iBaseId);
    }

    /**
     * Executes parent method parent::render() and returns name of template
     * file "selectlist_list.tpl".
     *
     * @return string
     */
    public function render()
    {

        parent::render();
        $this->_aViewData['mylist'] = $this->_getLanguagesList();

        return "language_list.tpl";
    }

    /**
     * Collects shop languages list.
     *
     * @return array
     */
    protected function _getLanguagesList()
    {
        $aLangParams = $this->getConfig()->getConfigParam('aLanguageParams');
        $aLanguages = oxRegistry::getLang()->getLanguageArray();
        $sDefaultLang = $this->getConfig()->getConfigParam('sDefaultLang');

        foreach ($aLanguages as $sKey => $sValue) {
            $sOxId = $sValue->oxid;
            $aLanguages[$sKey]->active = (!isset($aLangParams[$sOxId]["active"])) ? 1 : $aLangParams[$sOxId]["active"];
            $aLanguages[$sKey]->default = ($aLangParams[$sOxId]["baseId"] == $sDefaultLang) ? true : false;
            $aLanguages[$sKey]->sort = $aLangParams[$sOxId]["sort"];
        }

        if (is_array($aLangParams)) {
            $aSorting = $this->getListSorting();

            if (is_array($aSorting)) {
                foreach ($aSorting as $aFieldSorting) {
                    foreach ($aFieldSorting as $sField => $sDir) {
                        $this->_sDefSortField = $sField;
                        $this->_sDefSortOrder = $sDir;

                        if ($sField == 'active') {
                            //reverting sort order for field 'active'
                            $this->_sDefSortOrder = 'desc';
                        }
                        break 2;
                    }
                }
            }

            uasort($aLanguages, array($this, '_sortLanguagesCallback'));
        }

        return $aLanguages;
    }

    /**
     * Callback function for sorting languages objects. Sorts array according
     * 'sort' parameter
     *
     * @param object $oLang1 language object
     * @param object $oLang2 language object
     *
     * @return bool
     */
    protected function _sortLanguagesCallback($oLang1, $oLang2)
    {
        $sSortParam = $this->_sDefSortField;
        $sVal1 = is_string($oLang1->$sSortParam) ? strtolower($oLang1->$sSortParam) : $oLang1->$sSortParam;
        $sVal2 = is_string($oLang2->$sSortParam) ? strtolower($oLang2->$sSortParam) : $oLang2->$sSortParam;

        if ($this->_sDefSortOrder == 'asc') {
            return ($sVal1 < $sVal2) ? -1 : 1;
        } else {
            return ($sVal1 > $sVal2) ? -1 : 1;
        }
    }

    /**
     * Resets all multilanguage fields with specific language id
     * to default value in all tables.
     *
     * @param string $iLangId language ID
     */
    protected function _resetMultiLangDbFields($iLangId)
    {
        $iLangId = (int) $iLangId;

        //skipping reseting language with id = 0
        if ($iLangId) {

            oxDb::getDb()->startTransaction();

            try {
                $oDbMeta = oxNew("oxDbMetaDataHandler");
                $oDbMeta->resetLanguage($iLangId);

                oxDb::getDb()->commitTransaction();
            } catch (Exception $oEx) {

                // if exception, rollBack everything
                oxDb::getDb()->rollbackTransaction();

                //show warning
                $oEx = oxNew("oxExceptionToDisplay");
                $oEx->setMessage('LANGUAGE_ERROR_RESETING_MULTILANG_FIELDS');
                oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
            }
        }
    }
}