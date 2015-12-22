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
 * Manages Trusted Shops ratings.
 */
class oxTsRatings extends oxSuperCfg
{

    /**
     * timeout in seconds for regenerating data (3h)
     */
    const CACHE_TTL = 43200;

    /**
     * data id for cache
     */
    const TS_RATINGS = 'TS_RATINGS';

    /**
     * _aChannel channel data to be passed to view
     *
     * @var array
     * @access protected
     */
    protected $_aChannel = array();

    /**
     * Trusted shops ID
     *
     * @var null
     */
    protected $_sTsId = null;

    /**
     * Returns trusted shop id
     *
     * @return null
     */
    public function getTsId()
    {
        return $this->_sTsId;
    }

    /**
     * Sets trusted shop id
     *
     * @param string $sId Trusted shops id
     */
    public function setTsId($sId)
    {
        $this->_sTsId = $sId;
    }

    /**
     * Executes curl request to trusted shops
     *
     * @param string $sUrl Trusted shops url
     *
     * @return string curl response text
     */
    protected function _executeCurl($sUrl)
    {
        $oCurl = oxNew('oxCurl');
        $oCurl->setMethod("GET");
        $oCurl->setUrl($sUrl);
        $oCurl->setOption('CURLOPT_HEADER', false);
        $oCurl->setOption('CURLOPT_POST', false);
        $sOutput = $oCurl->execute();

        return $sOutput;
    }

    /**
     * Returns trusted shop ratings, if possible, if not returns array
     * with key empty set to true
     *
     * @return array
     */
    public function getRatings()
    {
        if (($this->_aChannel = oxRegistry::getUtils()->fromFileCache(self::TS_RATINGS))) {
            return $this->_aChannel;
        }
        $sTsId = $this->getTsId();

        $sUrl = "https://www.trustedshops.com/bewertung/show_xml.php?tsid=" . $sTsId;
        $sOutput = $this->_executeCurl($sUrl);

        $this->_aChannel['empty'] = true;

        try {
            $oDomFile = oxNew("oxSimpleXml");
            if ($oXml = $oDomFile->xmlToObject($sOutput)) {
                $aResult = $oXml->ratings->xpath('//result[@name="average"]');

                $this->_aChannel['empty'] = false;
                $this->_aChannel['result'] = (float) $aResult[0];
                $this->_aChannel['max'] = "5.00";
                $this->_aChannel['count'] = (int) $oXml->ratings["amount"];
                $this->_aChannel['shopName'] = (string) $oXml->name;
                oxRegistry::getUtils()->toFileCache(self::TS_RATINGS, $this->_aChannel, self::CACHE_TTL);
            }
        } catch (Exception $oEx) {
            $oEx = oxNew("oxException");
            $oEx->setMessage($oEx->getMessage());
            $oEx->debugOut();
        }

        return $this->_aChannel;
    }
}
