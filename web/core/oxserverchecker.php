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
 * Checks if server node is valid, information is not outdated.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 *
 * @ignore   This class will not be included in documentation.
 */
class oxServerChecker
{

    /**
     * Time in seconds, server node information life time.
     */
    const NODE_VALIDITY_TIME = 43200;

    /**
     * Current checking time - timestamp.
     *
     * @var int
     */
    private $_iCurrentTime = 0;

    /**
     * Class constructor. Sets current time to $_iCurrentTime parameter.
     */
    public function __construct()
    {
        $this->_iCurrentTime = oxRegistry::get("oxUtilsDate")->getTime();
    }

    /**
     * Checks if server node is valid.
     *
     * @param oxApplicationServer $oServer
     *
     * @return bool
     */
    public function check(oxApplicationServer $oServer)
    {
        $blResult = false;

        if ($this->_isValid($oServer) && $this->_isServerTimeValid($oServer->getTimestamp())) {
            $blResult = true;
        }

        return $blResult;
    }

    /**
     * Check is server information out dated.
     *
     * @param oxApplicationServer $oServer
     *
     * @return bool
     */
    private function _isValid($oServer)
    {
        return ($oServer->getTimestamp() - $this->_getCurrentTime() + self::NODE_VALIDITY_TIME) > 0;
    }

    /**
     * Method checks if server time was not rolled back.
     *
     * @param int $iServerTimeInPast timestamp of time in past
     *
     * @return bool
     */
    private function _isServerTimeValid($iServerTimeInPast)
    {
        return ($this->_getCurrentTime() - $iServerTimeInPast) >= 0;
    }

    /**
     * Returns current time - timestamp.
     *
     * @return int
     */
    private function _getCurrentTime()
    {
        return $this->_iCurrentTime;
    }
}
