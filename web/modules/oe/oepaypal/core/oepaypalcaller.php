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
 * PayPal caller service class
 */
class oePayPalCaller
{
    /**
     * Service call parameters.
     *
     * @var array
     */
    protected $_aParams = array();

    /**
     * PayPal logger.
     *
     * @var oePayPalLogger
     */
    protected $_oLogger = null;

    /**
     * PayPal curl object.
     *
     * @var object
     */
    protected $_oCurl = null;

    /**
     * Setter for logger.
     *
     * @param oePayPalLogger $oLogger logger
     */
    public function setLogger($oLogger)
    {
        $this->_oLogger = $oLogger;
    }

    /**
     * Getter for PayPal logger.
     *
     * @return oePayPalLogger
     */
    public function getLogger()
    {
        return $this->_oLogger;
    }

    /**
     * Sets PayPal curl object.
     *
     * @param oePayPalCurl $oPayPalCurl PayPal curl object.
     */
    public function setCurl($oPayPalCurl)
    {
        $this->_oCurl = $oPayPalCurl;
    }

    /**
     * Returns curl instance
     *
     * @return oePayPalCurl
     */
    public function getCurl()
    {
        if (is_null($this->_oCurl)) {
            $oCurl = oxNew('oePayPalCurl');
            $this->setCurl($oCurl);
        }

        return $this->_oCurl;
    }

    /**
     * PayPal request parameters setter.
     *
     * @param string $sParamName  parameter name
     * @param mixed  $mParamValue parameter value
     *
     * @return null
     */
    public function setParameter($sParamName, $mParamValue)
    {
        $this->_aParams[$sParamName] = $mParamValue;
    }

    /**
     * PayPal request parameters setter.
     *
     * @param array $aParameters parameters to use to build request.
     *
     * @return null
     */
    public function setParameters($aParameters)
    {
        $this->_aParams = array_merge($this->_aParams, $aParameters);
    }

    /**
     * Returns PayPal request parameters array.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_aParams;
    }

    /**
     * Calls given remote PayPal method.
     *
     * @param string $sMethodName .
     *
     * @return array
     */
    public function call($sMethodName = null)
    {
        $this->_setMethod($sMethodName);

        $oCurl = $this->getCurl();
        $oCurl->setParameters($this->getParameters());

        $this->log($this->getParameters(), 'Request to PayPal');

        $aResponse = $oCurl->execute();

        $this->log($aResponse, 'Response from PayPal');

        $this->_validateResponse($aResponse);

        return $aResponse;
    }

    /**
     * Set method name to execute like DoExpressCheckoutPayment or GetExpressCheckoutDetails.
     *
     * @param string $sName Name of a method
     */
    protected function _setMethod($sName)
    {
        if (!is_null($sName)) {
            $this->setParameter("METHOD", $sName);
        }
    }

    /**
     * Validates response from PayPal errors.
     *
     * @param array $aResponse
     *
     * @throws oePayPalResponseException if response has error from PayPal
     */
    protected function _validateResponse($aResponse)
    {
        if ('Failure' == $aResponse['ACK']) {
            /**
             * @var oePayPalResponseException $oException
             */
            $oException = oxNew('oePayPalResponseException', $aResponse['L_LONGMESSAGE0'], $aResponse['L_ERRORCODE0']);
            throw $oException;
        }
    }

    /**
     * Outputs given request data.
     *
     * @param string $sMethodName
     *
     * @return sting
     */
    public function getCallBackResponse($sMethodName)
    {
        $this->setParameter("METHOD", $sMethodName);

        $oCurl = $this->getCurl();
        $oCurl->setParameters($this->getParameters());
        $sRequest = $oCurl->getQuery();

        $this->log($sRequest, 'Callback response from eShop to PayPal');

        return $sRequest;
    }

    /**
     * Logs given request and responds parameters to log file.
     *
     * @param array  $aValue request / response parameters
     * @param string $sTitle section title in log file
     *
     * @return null
     */
    public function log($aValue, $sTitle = '')
    {
        if (!is_null($this->getLogger())) {
            $this->getLogger()->setTitle($sTitle);
            $this->getLogger()->log($aValue);
        }
    }

    /**
     * Set parameter from request.
     *
     * @param oePayPalPayPalRequest $oRequest request
     */
    public function setRequest(oePayPalPayPalRequest $oRequest)
    {
        $this->setParameters($oRequest->getData());
    }
}
