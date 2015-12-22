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
 * Interesting, useful links window.
 * Arranges interesting links window (contents may be changed in
 * administrator GUI) with short link description and URL. OXID
 * eShop -> LINKS.
 */
class Links extends oxUBase
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/info/links.tpl';

    /**
     * Links list.
     *
     * @var object
     */
    protected $_oLinksList = null;

    /**
     * Template variable getter. Returns links list
     *
     * @return object
     */
    public function getLinksList()
    {
        if ($this->_oLinksList === null) {
            $this->_oLinksList = false;
            // Load links
            $oLinksList = oxNew("oxlist");
            $oLinksList->init("oxlinks");
            $oLinksList->getList();
            $this->_oLinksList = $oLinksList;
        }

        return $this->_oLinksList;
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
        $aPath['title'] = oxRegistry::getLang()->translateString('LINKS', $iBaseLanguage, false);
        $aPath['link'] = $this->getLink();

        $aPaths[] = $aPath;

        return $aPaths;
    }
}
