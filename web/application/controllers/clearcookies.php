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
 * CMS - loads pages and displays it
 */
class ClearCookies extends oxUBase
{

    /**
     * Current view template
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/info/clearcookies.tpl';

    /**
     * Executes parent::render(), passes template variables to
     * template engine and generates content. Returns the name
     * of template to render content::_sThisTemplate
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        $this->_removeCookies();

        return $this->_sThisTemplate;
    }

    /**
     * Clears all cookies
     */
    protected function _removeCookies()
    {
        $oUtilsServer = oxRegistry::get("oxUtilsServer");
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $aCookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($aCookies as $sCookie) {
                $sRawCookie = explode('=', $sCookie);
                $oUtilsServer->setOxCookie(trim($sRawCookie[0]), '', time() - 10000, '/');
            }
        }
        $oUtilsServer->setOxCookie('language', '', time() - 10000, '/');
        $oUtilsServer->setOxCookie('displayedCookiesNotification', '', time() - 10000, '/');
    }

    /**
     * Returns Bread Crumb - you are here page1/page2/page3...
     *
     * @return array
     */
    public function getBreadCrumb()
    {
        $aPaths = array();
        $aPath = array();

        $iBaseLanguage = oxRegistry::getLang()->getBaseLanguage();
        $aPath['title'] = oxRegistry::getLang()->translateString('INFO_ABOUT_COOKIES', $iBaseLanguage, false);
        $aPath['link'] = $this->getLink();
        $aPaths[] = $aPath;

        return $aPaths;
    }
}
