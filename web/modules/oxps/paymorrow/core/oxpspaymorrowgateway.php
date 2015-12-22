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
 * Class OxpsPaymorrowGateway.
 */
class OxpsPaymorrowGateway extends PaymorrowGateway
{

    /**
     * Magic call method making a default workflow call to Paymorrow API.
     *
     * @param string $sMethodName
     * @param array  $aArguments
     *
     * @return array
     */
    public function __call( $sMethodName, $aArguments )
    {
        // Get all gateway related instances: client, data provider and response, handler

        /** @var OxpsOxid2Paymorrow $oOxidToPm */
        $oOxidToPm = oxNew( 'OxpsOxid2Paymorrow' );

        /** @var OxpsPaymorrowClient|PaymorrowClient $oClient */
        $oClient = $this->getPmClient();

        /** @var OxpsPaymorrowEshopDataProvider $oDataProvider */
        $oDataProvider = $oOxidToPm->getEshopDataProvider();

        /** @var OxpsPaymorrowResponseHandler $oResponseHandler */
        $oResponseHandler = oxRegistry::get( 'OxpsPaymorrowResponseHandler' );

        // Set method URL
        $oClient->setEndPoint( $this->getEndPointUrl() . $sMethodName );

        // Collect auth data and perform a request
        $aResponseData = $oClient->sendRequest(
            array_merge( $oDataProvider->collectCommonData(), (array) reset( $aArguments ) )
        );

        // Check it response is OK or an error
        if ( isset( $aResponseData['response_status'] ) and ( $aResponseData['response_status'] === 'OK' ) ) {
            $sResponseHandlerMethodFormat = 'handle%sResponseOK';
        } else {
            $sResponseHandlerMethodFormat = 'handle%sResponseError';
        }

        // Call the response handler method
        $sResponseHandlerMethod = sprintf( $sResponseHandlerMethodFormat, ucfirst( $sMethodName ) );
        $oResponseHandler->$sResponseHandlerMethod( $aResponseData );

        // Return formatted response data
        return $this->prepareResponseData( $aResponseData );
    }


    /**
     * Non-static alias for parent method `addressHash`.
     *
     * @codeCoverageIgnore
     *
     * @param array $aData
     *
     * @return string
     */
    public function getAddressHash( array $aData )
    {
        return parent::addressHash( $aData );
    }
}
