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
 * PayPal Extension checker, check if extension is active on shop/sub-shop
 */
class oePayPalExtensionChecker
{
    /**
     * Shop id
     *
     * @var string
     */
    protected $_sShopId = null;

    /**
     * Extension id
     *
     * @var string
     */
    protected $_sExtensionId = '';

    /**
     * Set shop id
     *
     * @param string $sShopId shop id
     */
    public function setShopId($sShopId)
    {
        $this->_sShopId = $sShopId;
    }

    /**
     * Return shop id
     *
     * @return string
     */
    public function getShopId()
    {
        if (is_null($this->_sShopId)) {
            $this->setShopId(oxRegistry::getConfig()->getShopId());
        }

        return $this->_sShopId;
    }

    /**
     * Set extension id
     *
     * @param string $sExtensionId extension id
     */
    public function setExtensionId($sExtensionId)
    {
        $this->_sExtensionId = $sExtensionId;
    }

    /**
     * Return extension id
     *
     * @return string
     */
    public function getExtensionId()
    {
        return $this->_sExtensionId;
    }

    /**
     * Return return extended classes array
     *
     * @return array
     */
    protected function _getExtendedClasses()
    {
        return $this->_getConfigValue('aModules');
    }

    /**
     * Return disabled modules array
     *
     * @return array
     */
    protected function _getDisabledModules()
    {
        return $this->_getConfigValue('aDisabledModules');
    }

    /**
     * Return config value
     *
     * @param string $sConfigName - config parameter name were stored arrays od extended classes
     *
     * @return array
     */
    protected function _getConfigValue($sConfigName)
    {
        $oDb = oxDb::getDb();
        $oConfig = oxRegistry::getConfig();
        $sConfigKey = $oConfig->getConfigParam('sConfigKey');

        $sSelect = "SELECT DECODE( `oxvarvalue` , " . $oDb->quote($sConfigKey) . " ) AS `oxvarvalue` " .
                   "FROM `oxconfig` WHERE `oxvarname` = " . $oDb->quote($sConfigName) . " AND `oxshopid` = " . $oDb->quote($this->getShopId());

        return unserialize($oDb->getOne($sSelect));
    }

    /**
     * Check if module is active.
     *
     * @return  bool
     */
    public function isActive()
    {
        $sModuleId = $this->getExtensionId();
        $blModuleIsActive = false;

        $aModules = $this->_getExtendedClasses();

        if (is_array($aModules)) {
            // Check if module was ever installed.
            $blModuleExists = false;
            foreach ($aModules as $sExtendPath) {
                if (false !== strpos($sExtendPath, '/' . $sModuleId . '/')) {

                    $blModuleExists = true;
                    break;
                }
            }

            // If module exists, check if it is not disabled.
            if ($blModuleExists) {
                $aDisabledModules = $this->_getDisabledModules();
                if (!(is_array($aDisabledModules) && in_array($sModuleId, $aDisabledModules))) {
                    $blModuleIsActive = true;
                }
            }
        }

        return $blModuleIsActive;
    }
}
