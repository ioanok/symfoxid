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
 * Base logger class
 */
class oePayPalLogger
{
    /**
     * Logger session id.
     *
     * @var string
     */
    protected $_sLoggerSessionId;

    /**
     * Log title
     */
    protected $_sLogTitle = '';

    /**
     * Sets logger session id.
     *
     * @param string $sId session id
     */
    public function setLoggerSessionId($sId)
    {
        $this->_sLoggerSessionId = $sId;
    }

    /**
     * Returns loggers session id.
     *
     * @return string
     */
    public function getLoggerSessionId()
    {
        return $this->_sLoggerSessionId;
    }

    /**
     * Returns full log file path.
     *
     * @return string
     */
    protected function _getLogFilePath()
    {
        return getShopBasePath() . 'modules/oe/oepaypal/logs/log.txt';
    }

    /**
     * Set log title.
     *
     * @param string $sTitle Log title
     */
    public function setTitle($sTitle)
    {
        $this->_sLogTitle = $sTitle;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_sLogTitle;
    }

    /**
     * Writes log message.
     *
     * @param mixed $mLogData logger data
     *
     * @return null
     */
    public function log($mLogData)
    {
        $oH = @fopen($this->_getLogFilePath(), "a+");
        if ($oH !== false) {
            if (is_string($mLogData)) {
                parse_str($mLogData, $aResult);
            } else {
                $aResult = $mLogData;
            }

            if (is_array($aResult)) {
                foreach ($aResult as $sKey => $sValue) {
                    $aResult[$sKey] = urldecode($sValue);
                }
            }

            fwrite($oH, "======================= " . $this->getTitle() . " [" . date("Y-m-d H:i:s") . "] ======================= #\n\n");
            fwrite($oH, "SESS ID: " . $this->getLoggerSessionId() . "\n");
            fwrite($oH, trim(var_export($aResult, true)) . "\n\n");
            @fclose($oH);
        }

        //resetting log title
        $this->setTitle('');
    }
}
