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
 * Module cache events handler class.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 */
class oxModuleCache extends oxSuperCfg
{

    /**
     * @var oxModule
     */
    protected $_oModule = null;

    /**
     * Sets dependencies.
     *
     * @param oxModule $_oModule
     */
    public function __construct(oxModule $_oModule)
    {
        $this->_oModule = $_oModule;
    }

    /**
     * Sets module.
     *
     * @param oxModule $oModule
     */
    public function setModule($oModule)
    {
        $this->_oModule = $oModule;
    }

    /**
     * Gets module.
     *
     * @return oxModule
     */
    public function getModule()
    {
        return $this->_oModule;
    }

    /**
     * Resets template, language and menu xml cache
     */
    public function resetCache()
    {
        $aTemplates = $this->getModule()->getTemplates();
        $oUtils = oxRegistry::getUtils();
        $oUtils->resetTemplateCache($aTemplates);
        $oUtils->resetLanguageCache();
        $oUtils->resetMenuCache();

        $oUtilsObject = oxUtilsObject::getInstance();
        $oUtilsObject->resetModuleVars();

        $this->_clearApcCache();
    }

    /**
     * Cleans PHP APC cache
     */
    protected function _clearApcCache()
    {
        if (extension_loaded('apc') && ini_get('apc.enabled')) {
            apc_clear_cache();
        }
    }
}
