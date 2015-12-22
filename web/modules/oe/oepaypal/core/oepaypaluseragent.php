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
 * Class for User Agent.
 *
 * @package core
 */
class oePayPalUserAgent
{
    /**
     * Detected device type
     *
     * @var string
     */
    protected $_sDeviceType = null;

    /**
     * Mobile device types.
     *
     * @var string
     */
    protected $_sMobileDevicesTypes = 'iphone|ipod|android|webos|htc|fennec|iemobile|blackberry|symbianos|opera mobi';

    /**
     * Function returns all supported mobile devices types.
     *
     * @return string
     */
    public function getMobileDeviceTypes()
    {
        return $this->_sMobileDevicesTypes;
    }

    /**
     * Returns device type: mobile | desktop.
     *
     * @return string
     */
    public function getDeviceType()
    {
        if ($this->_sDeviceType === null) {
            $this->setDeviceType($this->_detectDeviceType());
        }

        return $this->_sDeviceType;
    }

    /**
     * Set device type.
     *
     * @param string $sDeviceType
     */
    public function setDeviceType($sDeviceType)
    {
        $this->_sDeviceType = $sDeviceType;
    }

    /**
     * Set mobile device types.
     *
     * @param string $sMobileDeviceTypes
     */
    public function setMobileDeviceTypes($sMobileDeviceTypes)
    {
        $this->_sMobileDevicesTypes = $sMobileDeviceTypes;
    }

    /**
     * Detects device type from global variable. Device types: mobile, desktop.
     *
     * @return string
     */
    protected function _detectDeviceType()
    {
        $sDeviceType = 'desktop';
        if (preg_match('/(' . $this->getMobileDeviceTypes() . ')/is', $_SERVER['HTTP_USER_AGENT'])) {
            $sDeviceType = 'mobile';
        }

        return $sDeviceType;
    }
}
