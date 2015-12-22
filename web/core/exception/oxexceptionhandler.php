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
 * Exception handler, deals with all high level exceptions (caught in oxShopControl)
 */
class oxExceptionHandler
{

    /**
     * Log file path/name
     *
     * @var string
     */
    protected $_sFileName = 'EXCEPTION_LOG.txt';

    /**
     * Shop debug
     *
     * @var integer
     */
    protected $_iDebug = 0;

    /**
     * Class constructor
     *
     * @param integer $iDebug debug level
     */
    public function __construct($iDebug = 0)
    {
        $this->_iDebug = (int) $iDebug;
    }

    /**
     * Set the debug level
     *
     * @param int $iDebug debug level (0== no debug)
     */
    public function setIDebug($iDebug)
    {
        $this->_iDebug = $iDebug;
    }

    /**
     * Set log file path/name
     *
     * @param string $sFile file name
     */
    public function setLogFileName($sFile)
    {
        $this->_sFileName = $sFile;
    }

    /**
     * Get log file path/name
     *
     * @return string
     */
    public function getLogFileName()
    {
        return $this->_sFileName;
    }

    /**
     * Uncaught exception handler, deals with uncaught exceptions (global)
     *
     * @param Exception $oEx exception object
     *
     * @return null
     */
    public function handleUncaughtException(Exception $oEx)
    {
        // split between php or shop exception
        if (!$oEx instanceof oxException) {
            $this->_dealWithNoOxException($oEx);

            return; // Return straight away ! (in case of unit testing)
        }

        $oEx->setLogFileName($this->_sFileName); // set common log file ...

        $this->_uncaughtException($oEx); // Return straight away ! (in case of unit testing)
    }

    /**
     * Deal with uncaught oxException exceptions.
     *
     * @param oxException $oEx Exception to handle
     *
     * @return null
     */
    protected function _uncaughtException(oxException $oEx)
    {
        // exception occurred in function processing
        $oEx->setNotCaught();
        // general log entry for all exceptions here
        $oEx->debugOut();

        if (defined('OXID_PHP_UNIT')) {
            return $oEx->getString();
        } elseif (0 != $this->_iDebug) {
            oxRegistry::getUtils()->showMessageAndExit($oEx->getString());
        }

        try {
            oxRegistry::getUtils()->redirectOffline(500);
        } catch (Exception $oException) {
        }

        exit();
    }

    /**
     * No oxException, just write log file.
     *
     * @param Exception $oEx exception object
     *
     * @return null
     */
    protected function _dealWithNoOxException(Exception $oEx)
    {
        if (0 != $this->_iDebug) {
            $sLogMsg = date('Y-m-d H:i:s') . $oEx . "\n---------------------------------------------\n";
            oxRegistry::getUtils()->writeToLog($sLogMsg, $this->getLogFileName());
            if (defined('OXID_PHP_UNIT')) {
                return;
            } elseif (0 != $this->_iDebug) {
                oxRegistry::getUtils()->showMessageAndExit($sLogMsg);
            }
        }

        try {
            oxRegistry::getUtils()->redirectOffline(500);
        } catch (Exception $oException) {
        }

        exit();
    }

    /**
     * Only used for convenience in UNIT tests by doing so we avoid
     * writing extended classes for testing protected or private methods
     *
     * @param string $sMethod Methods name
     * @param array  $aArgs   Argument array
     *
     * @throws oxSystemComponentException Throws an exception if the called method does not exist or is not accessible in current class
     *
     * @return string
     */
    public function __call($sMethod, $aArgs)
    {
        if (defined('OXID_PHP_UNIT')) {
            if (substr($sMethod, 0, 4) == "UNIT") {
                $sMethod = str_replace("UNIT", "_", $sMethod);
            }
            if (method_exists($this, $sMethod)) {
                return call_user_func_array(array(& $this, $sMethod), $aArgs);
            }
        }

        throw new oxSystemComponentException("Function '$sMethod' does not exist or is not accessible! (" . __CLASS__ . ")" . PHP_EOL);
    }
}
