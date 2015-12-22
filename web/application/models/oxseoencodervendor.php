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
 * Seo encoder base
 *
 */
class oxSeoEncoderVendor extends oxSeoEncoder
{

    /**
     * Root vendor uri cache
     *
     * @var string
     */
    protected $_aRootVendorUri = null;

    /**
     * Returns target "extension" (/)
     *
     * @return string
     */
    protected function _getUrlExtension()
    {
        return '/';
    }

    /**
     * Returns part of SEO url excluding path
     *
     * @param oxVendor $oVendor      vendor object
     * @param int      $iLang        language
     * @param bool     $blRegenerate if TRUE forces seo url regeneration
     *
     * @return string
     */
    public function getVendorUri($oVendor, $iLang = null, $blRegenerate = false)
    {
        if (!isset($iLang)) {
            $iLang = $oVendor->getLanguage();
        }
        // load from db
        if ($blRegenerate || !($sSeoUrl = $this->_loadFromDb('oxvendor', $oVendor->getId(), $iLang))) {

            if ($iLang != $oVendor->getLanguage()) {
                $sId = $oVendor->getId();
                $oVendor = oxNew('oxvendor');
                $oVendor->loadInLang($iLang, $sId);
            }

            $sSeoUrl = '';
            if ($oVendor->getId() != 'root') {
                if (!isset($this->_aRootVendorUri[$iLang])) {
                    $oRootVendor = oxNew('oxvendor');
                    $oRootVendor->loadInLang($iLang, 'root');
                    $this->_aRootVendorUri[$iLang] = $this->getVendorUri($oRootVendor, $iLang);
                }
                $sSeoUrl .= $this->_aRootVendorUri[$iLang];
            }

            $sSeoUrl .= $this->_prepareTitle($oVendor->oxvendor__oxtitle->value, false, $oVendor->getLanguage()) . '/';
            $sSeoUrl = $this->_processSeoUrl($sSeoUrl, $oVendor->getId(), $iLang);

            // save to db
            $this->_saveToDb('oxvendor', $oVendor->getId(), $oVendor->getBaseStdLink($iLang), $sSeoUrl, $iLang);
        }

        return $sSeoUrl;
    }

    /**
     * Returns vendor SEO url for specified page
     *
     * @param oxvendor $oVendor vendor object
     * @param int      $iPage   page tu prepare number
     * @param int      $iLang   language
     * @param bool     $blFixed fixed url marker (default is null)
     *
     * @return string
     */
    public function getVendorPageUrl($oVendor, $iPage, $iLang = null, $blFixed = null)
    {
        if (!isset($iLang)) {
            $iLang = $oVendor->getLanguage();
        }
        $sStdUrl = $oVendor->getBaseStdLink($iLang) . '&amp;pgNr=' . $iPage;
        $sParams = (int) ($iPage + 1);

        $sStdUrl = $this->_trimUrl($sStdUrl, $iLang);
        $sSeoUrl = $this->getVendorUri($oVendor, $iLang) . $sParams . "/";

        if ($blFixed === null) {
            $blFixed = $this->_isFixed('oxvendor', $oVendor->getId(), $iLang);
        }

        return $this->_getFullUrl($this->_getPageUri($oVendor, 'oxvendor', $sStdUrl, $sSeoUrl, $sParams, $iLang, $blFixed), $iLang);
    }

    /**
     * Encodes vendor categoru URLs into SEO format
     *
     * @param oxvendor $oVendor Vendor object
     * @param int      $iLang   language
     *
     * @return null
     */
    public function getVendorUrl($oVendor, $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = $oVendor->getLanguage();
        }

        return $this->_getFullUrl($this->getVendorUri($oVendor, $iLang), $iLang);
    }

    /**
     * Deletes Vendor seo entry
     *
     * @param oxvendor $oVendor Vendor object
     */
    public function onDeleteVendor($oVendor)
    {
        $oDb = oxDb::getDb();
        $sIdQuoted = $oDb->quote($oVendor->getId());
        $oDb->execute("delete from oxseo where oxobjectid = $sIdQuoted and oxtype = 'oxvendor'");
        $oDb->execute("delete from oxobject2seodata where oxobjectid = $sIdQuoted");
    }

    /**
     * Returns alternative uri used while updating seo
     *
     * @param string $sObjectId object id
     * @param int    $iLang     language id
     *
     * @return string
     */
    protected function _getAltUri($sObjectId, $iLang)
    {
        $sSeoUrl = null;
        $oVendor = oxNew("oxvendor");
        if ($oVendor->loadInLang($iLang, $sObjectId)) {
            $sSeoUrl = $this->getVendorUri($oVendor, $iLang, true);
        }

        return $sSeoUrl;
    }
}
