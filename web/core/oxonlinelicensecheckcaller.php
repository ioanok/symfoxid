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
 * Class makes call to given URL address and sends request parameter.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 *
 * @ignore   This class will not be included in documentation.
 */
class oxOnlineLicenseCheckCaller extends oxOnlineCaller
{

    /** Online License Key Check web service url. */
    const WEB_SERVICE_URL = 'https://olc.oxid-esales.com/check.php';

    /** XML document tag name. */
    const XML_DOCUMENT_NAME = 'olcRequest';

    /**
     * Expected response element in the XML response message fom web service.
     *
     * @var string
     */
    private $_sResponseElement = 'olc';

    /**
     * Performs Web service request
     *
     * @param oxOnlineLicenseCheckRequest $oRequest Object with request parameters
     *
     * @throws oxException
     * @return oxOnlineLicenseCheckResponse
     */
    public function doRequest(oxOnlineLicenseCheckRequest $oRequest)
    {
        $sResponse = $this->call($oRequest);

        return $this->_formResponse($sResponse);
    }

    /**
     * Removes serial keys from request and forms email body.
     *
     * @param oxOnlineLicenseCheckRequest $oRequest
     *
     * @return string
     */
    protected function _formEmail($oRequest)
    {
        $oRequest->keys = null;

        return parent::_formEmail($oRequest);
    }

    /**
     * Parse response message received from Online License Key Check web service and save it to response object.
     *
     * @param string $sRawResponse
     *
     * @throws oxException
     *
     * @return oxOnlineLicenseCheckResponse
     */
    protected function _formResponse($sRawResponse)
    {
        /** @var oxUtilsXml $oUtilsXml */
        $oUtilsXml = oxRegistry::get("oxUtilsXml");
        if (empty($sRawResponse) || !($oDomDoc = $oUtilsXml->loadXml($sRawResponse))) {
            throw new oxException('OLC_ERROR_RESPONSE_NOT_VALID');
        }

        if ($oDomDoc->documentElement->nodeName != $this->_sResponseElement) {
            throw new oxException('OLC_ERROR_RESPONSE_UNEXPECTED');
        }

        $oResponseNode = $oDomDoc->firstChild;

        if (!$oResponseNode->hasChildNodes()) {
            throw new oxException('OLC_ERROR_RESPONSE_NOT_VALID');
        }

        $oNodes = $oResponseNode->childNodes;

        /** @var oxOnlineLicenseCheckResponse $oResponse */
        $oResponse = oxNew('oxOnlineLicenseCheckResponse');

        // iterate through response node to get response parameters
        for ($i = 0; $i < $oNodes->length; $i++) {
            $sNodeName = $oNodes->item($i)->nodeName;
            $sNodeValue = $oNodes->item($i)->nodeValue;
            $oResponse->$sNodeName = $sNodeValue;
        }

        return $oResponse;
    }

    /**
     * Gets XML document name.
     *
     * @return string XML document tag name.
     */
    protected function _getXMLDocumentName()
    {
        return self::XML_DOCUMENT_NAME;
    }

    /**
     * Gets service url.
     *
     * @return string Web service url.
     */
    protected function _getServiceUrl()
    {
        return self::WEB_SERVICE_URL;
    }
}
