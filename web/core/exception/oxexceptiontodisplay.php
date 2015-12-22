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
 * simplified Exception classes for simply displaying errors
 * saves resources when exception functionality is not needed
 */
class oxExceptionToDisplay implements oxIDisplayError
{

    /**
     * Language const of a Message
     *
     * @var string
     */
    private $_sMessage;

    /**
     * Shop debug
     *
     * @var integer
     */
    protected $_blDebug = false;

    /**
     * Stack trace as a string
     *
     * @var string
     */
    private $_sStackTrace;

    /**
     * Additional values
     *
     * @var string
     */
    private $_aValues;

    /**
     * Typeof the exception (old class name)
     *
     * @var string
     */
    private $_sType;

    /**
     * Stack trace setter
     *
     * @param string $sStackTrace stack trace
     */
    public function setStackTrace($sStackTrace)
    {
        $this->_sStackTrace = $sStackTrace;
    }

    /**
     * Returns stack trace
     *
     * @return string
     */
    public function getStackTrace()
    {
        return $this->_sStackTrace;
    }

    /**
     * Sets oxExceptionToDisplay::_aValues value
     *
     * @param array $aValues exception values to store
     */
    public function setValues($aValues)
    {
        $this->_aValues = $aValues;
    }

    /**
     * Stores into exception storage message or other value
     *
     * @param string $sName  storage name
     * @param mixed  $sValue value to store
     */
    public function addValue($sName, $sValue)
    {
        $this->_aValues[$sName] = $sValue;
    }

    /**
     * Exception type setter
     *
     * @param string $sType exception type
     */
    public function setExceptionType($sType)
    {
        $this->_sType = $sType;
    }

    /**
     * Returns error class type
     *
     * @return string
     */
    public function getErrorClassType()
    {
        return $this->_sType;
    }

    /**
     * Returns exception stored (by name) value
     *
     * @param string $sName storage name
     *
     * @return  mixed
     */
    public function getValue($sName)
    {
        return $this->_aValues[$sName];
    }

    /**
     * Exception debug mode setter
     *
     * @param bool $bl if TRUE debug mode on
     */
    public function setDebug($bl)
    {
        $this->_blDebug = $bl;
    }

    /**
     * Exception message setter
     *
     * @param string $sMessage exception message
     */
    public function setMessage($sMessage)
    {
        $this->_sMessage = $sMessage;
    }

    /**
     * Sets the exception message arguments used when
     * outputing message using sprintf().
     */
    public function setMessageArgs()
    {
        $this->_aMessageArgs = func_get_args();
    }

    /**
     * Returns translated exception message
     *
     * @return string
     */
    public function getOxMessage()
    {
        if ($this->_blDebug) {
            return $this;
        } else {
            $sString = oxRegistry::getLang()->translateString($this->_sMessage);

            if (!empty($this->_aMessageArgs)) {
                $sString = vsprintf($sString, $this->_aMessageArgs);
            }

            return $sString;
        }
    }

    /**
     * When exception is converted as string, this magic method return exception message
     *
     * @return string
     */
    public function __toString()
    {
        $sRes = $this->getErrorClassType() . " (time: " . date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime()) . "): " . $this->getOxMessage() . " \n Stack Trace: " . $this->getStackTrace() . "\n";
        foreach ($this->_aValues as $key => $value) {
            $sRes .= $key . " => " . $value . "\n";
        }

        return $sRes;
    }
}
