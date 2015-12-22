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
class oxSeoEncoderRecomm extends oxSeoEncoder
{

    /**
     * Returns SEO uri for tag.
     *
     * @param oxrecommlist $oRecomm recomm list object
     * @param int          $iLang   language
     *
     * @return string
     */
    public function getRecommUri($oRecomm, $iLang = null)
    {
        if (!($sSeoUrl = $this->_loadFromDb('dynamic', $oRecomm->getId(), $iLang))) {
            $myConfig = $this->getConfig();

            // fetching part of base url
            $sSeoUrl = $this->_getStaticUri(
                $oRecomm->getBaseStdLink($iLang, false),
                $myConfig->getShopId(),
                $iLang
            )
            . $this->_prepareTitle($oRecomm->oxrecommlists__oxtitle->value, false, $iLang);

            // creating unique
            $sSeoUrl = $this->_processSeoUrl($sSeoUrl, $oRecomm->getId(), $iLang);

            // inserting
            $this->_saveToDb('dynamic', $oRecomm->getId(), $oRecomm->getBaseStdLink($iLang), $sSeoUrl, $iLang, $myConfig->getShopId());
        }

        return $sSeoUrl;
    }

    /**
     * Returns full url for passed tag
     *
     * @param oxrecommlist $oRecomm recomendation list object
     * @param int          $iLang   language
     *
     * @return string
     */
    public function getRecommUrl($oRecomm, $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = oxRegistry::getLang()->getBaseLanguage();
        }

        return $this->_getFullUrl($this->getRecommUri($oRecomm, $iLang), $iLang);
    }

    /**
     * Returns tag SEO url for specified page
     *
     * @param oxrecommlist $oRecomm recomendation list object
     * @param int          $iPage   page tu prepare number
     * @param int          $iLang   language
     * @param bool         $blFixed fixed url marker (default is false)
     *
     * @return string
     */
    public function getRecommPageUrl($oRecomm, $iPage, $iLang = null, $blFixed = false)
    {
        if (!isset($iLang)) {
            $iLang = oxRegistry::getLang()->getBaseLanguage();
        }
        $sStdUrl = $oRecomm->getBaseStdLink($iLang) . '&amp;pgNr=' . $iPage;
        $sParams = (int) ($iPage + 1);

        $sStdUrl = $this->_trimUrl($sStdUrl, $iLang);
        $sSeoUrl = $this->getRecommUri($oRecomm, $iLang) . $sParams . "/";

        return $this->_getFullUrl($this->_getPageUri($oRecomm, 'dynamic', $sStdUrl, $sSeoUrl, $sParams, $iLang, $blFixed), $iLang);
    }
}
