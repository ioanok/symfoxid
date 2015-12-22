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
 * Main PayPal controller
 */
class oePayPalController extends oxUBase
{
    /**
     * @var oePayPalRequest
     */
    protected $_oRequest = null;

    /**
     * @var oePayPalLogger
     */
    protected $_oLogger = null;

    /**
     * @var oePayPalConfig
     */
    protected $_oPayPalConfig = null;

    /**
     * Return request object
     *
     * @return oePayPalRequest
     */
    public function getRequest()
    {
        if (is_null($this->_oRequest)) {
            $this->_oRequest = oxNew('oePayPalRequest');
        }

        return $this->_oRequest;
    }

    /**
     * Return PayPal logger
     *
     * @return oePayPalLogger
     */
    public function getLogger()
    {
        if (is_null($this->_oLogger)) {
            $this->_oLogger = oxNew('oePayPalLogger');
            $this->_oLogger->setLoggerSessionId($this->getSession()->getId());
        }

        return $this->_oLogger;
    }

    /**
     * Return PayPal config
     *
     * @return oePayPalConfig
     */
    public function getPayPalConfig()
    {
        if (is_null($this->_oPayPalConfig)) {
            $this->setPayPalConfig(oxNew('oePayPalConfig'));
        }

        return $this->_oPayPalConfig;
    }

    /**
     * Set PayPal config
     *
     * @param oePayPalConfig $oPayPalConfig config
     */
    public function setPayPalConfig($oPayPalConfig)
    {
        $this->_oPayPalConfig = $oPayPalConfig;
    }


    /**
     * Logs passed value.
     *
     * @param mixed $mValue
     */
    public function log($mValue)
    {
        if ($this->getPayPalConfig()->isLoggingEnabled()) {
            $this->getLogger()->log($mValue);
        }
    }
}
