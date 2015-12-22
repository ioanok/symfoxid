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
 * Class OxPsPaymorrowSessionMonitor.
 */
class OxPsPaymorrowSessionMonitor extends oxWidget
{

    /**
     * Widget template
     *
     * @var string
     */
    protected $_sThisTemplate = 'oxpspaymorrowsessionmonitor.tpl';


    /**
     * Returns resource URL.
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getPaymorrowResourceControllerJavaScript()
    {
        return (string) str_replace('&amp;', '&', $this->getConfig()->getShopSecureHomeURL()) .
               'index.php?cl=oxpspaymorrowresource&fnc=getPaymorrowSessionMonitorJavaScript';
    }

    /**
     * Returns if view should be cached.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public function isCacheable()
    {
        return false;
    }
}
