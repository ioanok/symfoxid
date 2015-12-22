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
 */

/**
 * Class OxpsPaymorrowResource
 */
class OxpsPaymorrowResource extends oxUBase
{

    /**
     * Get Paymorrow dynamic JavaScript.
     */
    public function getPaymorrowJavaScript()
    {
        $this->_getResource( '/pmfunc.js' );
    }

    /**
     * Get Paymorrow dynamic JavaScript.
     */
    public function getPaymorrowSessionMonitorJavaScript()
    {
        /**
         * From Paymorrow documentation:
         *
         * 6.5 Paymorrow session monitoring
         * Paymorrow needs to monitor customer’s behavior in the eshop during the full shopping session for qualified decision whether accept or decline customer’s order.
         * Paymorrow requires for this session monitoring to place on every single page session monitroring JS.
         * <script type="text/javascript" src="pmResource.php?path=/pmsession.js&amp;session_id=xxxxx"></script>
         * session_id is customer’s http sessionId which is sent to prepareOrder as attribute client_browser_session_id.
         * This pmsession.js must NOT be cached.
         */
        $this->_getResource( '/pmsession.js?session_id=' . oxRegistry::getSession()->getId(), true );
    }

    /**
     * Get Paymorrow dynamic css.
     */
    public function getPaymorrowCSS()
    {
        $this->_getResource( '/css/pmstyle.css' );
    }

    /**
     * Get Paymorrow dynamic JavaScript for module pages in admin back-end.
     */
    public function getPaymorrowAdminJavaScript()
    {
        $this->_getResource( '/pmadminfunc.js' );
    }

    /**
     * Get Paymorrow dynamic CSS for module pages in admin back-end.
     */
    public function getPaymorrowAdminCss()
    {
        $this->_getResource( '/css/pmadminstyle.css' );
    }


    /**
     * Get a resource by path and output the resource content.
     *
     * @param string $sResourcePath
     * @param bool   $blNoCache     forces not to cache resource
     */
    protected function _getResource( $sResourcePath, $blNoCache = false )
    {
        $aResponse = null;

        if ( !$blNoCache ) {
            /** @var OxpsPaymorrowResourceCache $oResourceCache */
            $oResourceCache = oxNew( 'OxpsPaymorrowResourceCache' );
            $aResponse = $oResourceCache->pop( $sResourcePath );
        }

        if ( empty( $aResponse ) ) {

            /** @var OxpsOxid2Paymorrow $oOxidToPm */
            $oOxidToPm = oxNew( 'OxpsOxid2Paymorrow' );
            $aResponse = $oOxidToPm->getBuiltPaymorrowResourceProxy()->getResource( $sResourcePath );

            if ( !$blNoCache ) {
                $oResourceCache->push( $sResourcePath, $aResponse );
            }
        }

        $this->_resourceResponse( $aResponse );
    }

    /**
     * Sent response headers, content and stop execution to prevent defaults.
     *
     * @codeCoverageIgnore
     *
     * @param array $aResponse
     */
    protected function _resourceResponse( array $aResponse )
    {
        if ( isset( $aResponse['contentType'] ) ) {
            oxRegistry::getUtils()->setHeader( 'Content-Type: ' . $aResponse['contentType'] );
        }

        if ( isset( $aResponse['body'] ) ) {
            print( $aResponse['body'] );
        }

        exit();
    }
}
