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
 * Admin dynscreen manager.
 * Returns template, that arranges two other templates ("dynscreen_list.tpl"
 * and "dyn_affiliates_about.tpl") to frame.
 *
 * @subpackage dyn
 */
class Dynscreen extends oxAdminList
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'dynscreen.tpl';

    /**
     * Sets up navigation for current view
     *
     * @param string $sNode None name
     */
    protected function _setupNavigation($sNode)
    {
        $myAdminNavig = $this->getNavigation();
        $sNode = oxRegistry::getConfig()->getRequestParameter("menu");

        // active tab
        $iActTab = oxRegistry::getConfig()->getRequestParameter('actedit');
        $iActTab = $iActTab ? $iActTab : $this->_iDefEdit;

        $sActTab = $iActTab ? "&actedit=$iActTab" : '';

        // list url
        $this->_aViewData['listurl'] = $myAdminNavig->getListUrl($sNode) . $sActTab;

        // edit url
        $sEditUrl = $myAdminNavig->getEditUrl($sNode, $iActTab) . $sActTab;
        if (!getStr()->preg_match("/^http(s)?:\/\//", $sEditUrl)) {
            //internal link, adding path
            /** @var oxUtilsUrl $oUtilsUrl */
            $oUtilsUrl = oxRegistry::get("oxUtilsUrl");
            $sSelfLinkParameter = $this->getViewConfig()->getViewConfigParam('selflink');
            $sEditUrl = $oUtilsUrl->appendParamSeparator($sSelfLinkParameter) . $sEditUrl;
        }

        $this->_aViewData['editurl'] = $sEditUrl;

        // tabs
        $this->_aViewData['editnavi'] = $myAdminNavig->getTabs($sNode, $iActTab);

        // active tab
        $this->_aViewData['actlocation'] = $myAdminNavig->getActiveTab($sNode, $iActTab);

        // default tab
        $this->_aViewData['default_edit'] = $myAdminNavig->getActiveTab($sNode, $this->_iDefEdit);

        // passign active tab number
        $this->_aViewData['actedit'] = $iActTab;

        // buttons
        $this->_aViewData['bottom_buttons'] = $myAdminNavig->getBtn($sNode);
    }

    /**
     * Returns dyn area view id
     *
     * @return string
     */
    public function getViewId()
    {
        return 'dyn_menu';
    }
}
