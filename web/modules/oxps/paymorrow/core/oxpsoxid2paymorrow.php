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
 * Class OxpsOxid2Paymorrow.
 * OXID to Paymorrow gateway bootstrap.
 */
class OxpsOxid2Paymorrow
{

    /**
     * Get Paymorrow module settings instance.
     *
     * @return OxpsPaymorrowSettings
     */
    public function getPaymorrowSettings()
    {
        return oxNew( 'OxpsPaymorrowSettings' );
    }

    /**
     * Get Paymorrow gateway instance.
     *
     * @return OxpsPaymorrowGateway|PaymorrowGateway
     */
    public function getPaymorrowGateway()
    {
        return oxNew( 'OxpsPaymorrowGateway' );
    }

    /**
     * Get request controller instance.
     *
     * @return RequestController
     */
    public function getPaymorrowRequestController()
    {
        return oxNew( 'RequestController' );
    }

    /**
     * Get Paymorrow client instance.
     *
     * @return OxpsPaymorrowClient
     */
    public function getPaymorrowClient()
    {
        return oxNew( 'OxpsPaymorrowClient' );
    }

    /**
     * Get data provider instance.
     *
     * @return OxpsPaymorrowEshopDataProvider
     */
    public function getEshopDataProvider()
    {
        return oxNew( 'OxpsPaymorrowEshopDataProvider' );
    }

    /**
     * Get built resource proxy with merchant and endpoint url set.
     *
     * @return PaymorrowResourceProxy
     */
    public function getBuiltPaymorrowResourceProxy()
    {
        /** @var  $oPmResourceProxy PaymorrowResourceProxy */
        $oPmResourceProxy = oxNew( 'PaymorrowResourceProxy' );

        $oPmSettings = $this->getPaymorrowSettings();

        $oPmResourceProxy->setMerchantId( $oPmSettings->getMerchantId() );
        $oPmResourceProxy->setEndPointUrl( $oPmSettings->getPaymorrowResourcePath() );

        return $oPmResourceProxy;
    }

    /**
     * Collects user data for Paymorrow JavaScript library.
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getPaymorrowPrintData()
    {
        return $this->getEshopDataProvider()->printPmData();
    }

    /**
     * Get initialized Paymorrow gateway instance.
     *
     * @return OxpsPaymorrowGateway
     */
    public function buildPaymorrowGateway()
    {
        $oPmGateway = $this->getPaymorrowGateway();
        $oPmGateway->setPmClient( $this->getPaymorrowClient() );
        $oPmGateway->setEshopDataProvider( $this->getEshopDataProvider() );
        $oPmGateway->setEndPointUrl( $this->getPaymorrowSettings()->getEndPointURL() );

        /**
         * Using singleton pattern in order to get access to this object later
         * and check response from Paymorrow
         */
        $oPmGateway->setResponseHandler( oxRegistry::get( 'OxpsPaymorrowResponseHandler' ) );

        return $oPmGateway;
    }

    /**
     * Get built Paymorrow request controller with Paymorrow gateway set.
     *
     * @return RequestController
     */
    public function getBuiltPaymorrowRequestController()
    {
        $oPmRequestController = $this->getPaymorrowRequestController();

        $oPmGateway = $this->buildPaymorrowGateway();

        $oPmRequestController->setGateway( $oPmGateway );

        return $oPmRequestController;
    }

    /**
     * Get Paymorrow error handler instance.
     *
     * @return OxpsPaymorrowErrorHandler
     */
    public function getPaymorrowErrorHandler()
    {
        return oxNew( 'OxpsPaymorrowErrorHandler' );
    }
}
