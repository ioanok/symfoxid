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
 * simple class to add a error message to display
 */
class oxDisplayError implements oxIDisplayError
{

    /**
     * Error message
     *
     * @var string $_sMessage
     */
    protected $_sMessage;

    /**
     * returns the stored message
     *
     * @return string stored message
     */
    public function getOxMessage()
    {
        return oxRegistry::getLang()->translateString($this->_sMessage);
    }

    /**
     * stored the message
     *
     * @param string $sMessage message
     */
    public function setMessage($sMessage)
    {
        $this->_sMessage = $sMessage;
    }

    /**
     * Returns errorrous class name (currently returns null)
     *
     * @return null
     */
    public function getErrorClassType()
    {
        return null;
    }

    /**
     * Returns value (currently returns empty string)
     *
     * @param string $sName value ignored
     *
     * @return empty string
     */
    public function getValue($sName)
    {
        return '';
    }
}
