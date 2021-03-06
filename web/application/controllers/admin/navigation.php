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
 * Administrator GUI navigation manager class.
 */
class Navigation extends oxAdminView
{

    /**
     * Allowed host url
     *
     * @var string
     */
    protected $_sAllowedHost = "http://admin.oxid-esales.com";

    /**
     * Executes parent method parent::render(), generates menu HTML code,
     * passes data to Smarty engine, returns name of template file "nav_frame.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $myUtilsServer = oxRegistry::get("oxUtilsServer");

        $sItem = oxRegistry::getConfig()->getRequestParameter("item");
        $sItem = $sItem ? basename($sItem) : false;
        if (!$sItem) {
            $sItem = "nav_frame.tpl";
            $aFavorites = oxRegistry::getConfig()->getRequestParameter("favorites");
            if (is_array($aFavorites)) {
                $myUtilsServer->setOxCookie('oxidadminfavorites', implode('|', $aFavorites));
            }
        } else {
            $oNavTree = $this->getNavigation();

            // set menu structure
            $this->_aViewData["menustructure"] = $oNavTree->getDomXml()->documentElement->childNodes;

            // version patch strin
            $sVersion = str_replace(array("EE.", "PE."), "", $this->_sShopVersion);
            $this->_aViewData["sVersion"] = trim($sVersion);

            //checking requirements if this is not nav frame reload
            if (!oxRegistry::getConfig()->getRequestParameter("navReload")) {
                // #661 execute stuff we run each time when we start admin once
                if ('home.tpl' == $sItem) {
                    $this->_aViewData['aMessage'] = $this->_doStartUpChecks();
                }
            } else {
                //removing reload param to force requirements checking next time
                oxRegistry::getSession()->deleteVariable("navReload");
            }

            // favorite navigation
            $aFavorites = explode('|', $myUtilsServer->getOxCookie('oxidadminfavorites'));

            if (is_array($aFavorites) && count($aFavorites)) {
                $this->_aViewData["menufavorites"] = $oNavTree->getListNodes($aFavorites);
                $this->_aViewData["aFavorites"] = $aFavorites;
            }

            // history navigation
            $aHistory = explode('|', $myUtilsServer->getOxCookie('oxidadminhistory'));
            if (is_array($aHistory) && count($aHistory)) {
                $this->_aViewData["menuhistory"] = $oNavTree->getListNodes($aHistory);
            }

            // open history node ?
            $this->_aViewData["blOpenHistory"] = oxRegistry::getConfig()->getRequestParameter('openHistory');
        }

        $sWhere = '';
        $blisMallAdmin = oxRegistry::getSession()->getVariable('malladmin');
        /** @var oxShopList $oShoplist */
        $oShoplist = oxNew('oxShopList');
        if (!$blisMallAdmin) {
            // we only allow to see our shop
            $iShopId = oxRegistry::getSession()->getVariable("actshop");
            /** @var oxShop $oShop */
            $oShop = oxNew('oxShop');
            $oShop->load($iShopId);
            $oShoplist->add($oShop);
        } else {
            $oShoplist->getIdTitleList();
        }

        $this->_aViewData['shoplist'] = $oShoplist;
        return $sItem;
    }

    /**
     * Changing active shop
     */
    public function chshp()
    {
        parent::chshp();

        // informing about basefrm parameters
        $this->_aViewData['loadbasefrm'] = true;
        $sListView = oxRegistry::getConfig()->getRequestParameter('listview');
        $sEditView = oxRegistry::getConfig()->getRequestParameter('editview');
        $iActEdit = oxRegistry::getConfig()->getRequestParameter('actedit');


        $this->_aViewData['listview'] = $sListView;
        $this->_aViewData['editview'] = $sEditView;
        $this->_aViewData['actedit'] = $iActEdit;
    }

    /**
     * Destroy session, redirects to admin login and clears cache
     */
    public function logout()
    {
        $mySession = $this->getSession();
        $myConfig = $this->getConfig();

        $oUser = oxNew("oxuser");
        $oUser->logout();

        // kill session
        $mySession->destroy();

        // delete also, this is usually not needed but for security reasons we execute still
        if ($myConfig->getConfigParam('blAdodbSessionHandler')) {
            $oDb = oxDb::getDb();
            $oDb->execute("delete from oxsessions where SessionID = " . $oDb->quote($mySession->getId()));
        }

        //reseting content cache if needed
        if ($myConfig->getConfigParam('blClearCacheOnLogout')) {
            $this->resetContentCache(true);
        }

        oxRegistry::getUtils()->redirect('index.php', true, 302);
    }

    /**
     * Caches external url file locally, adds <base> tag with original url to load images and other links correcly
     */
    public function exturl()
    {
        $myUtils = oxRegistry::getUtils();
        if ($sUrl = oxRegistry::getConfig()->getRequestParameter("url")) {

            // Limit external url's only allowed host
            $myConfig = $this->getConfig();
            if ($myConfig->getConfigParam('blLoadDynContents') && strpos($sUrl, $this->_sAllowedHost) === 0) {

                $sPath = $myConfig->getConfigParam('sCompileDir') . "/" . md5($sUrl) . '.html';
                if ($myUtils->getRemoteCachePath($sUrl, $sPath)) {

                    $oStr = getStr();
                    $sVersion = $myConfig->getVersion();
                    $sEdition = $myConfig->getFullEdition();
                    $sCurYear = date("Y");

                    // Get ceontent
                    $sOutput = file_get_contents($sPath);

                    // Fix base path
                    $sOutput = $oStr->preg_replace("/<\/head>/i", "<base href=\"" . dirname($sUrl) . '/' . "\"></head>\n  <!-- OXID eShop {$sEdition}, Version {$sVersion}, Shopping Cart System (c) OXID eSales AG 2003 - {$sCurYear} - http://www.oxid-esales.com -->", $sOutput);

                    // Fix self url's
                    $myUtils->showMessageAndExit($oStr->preg_replace("/href=\"#\"/i", 'href="javascript::void();"', $sOutput));
                }
            } else {
                // Caching not allowed, redirecting
                $myUtils->redirect($sUrl, true, 302);
            }
        }

        $myUtils->showMessageAndExit("");
    }

    /**
     * Every Time Admin starts we perform these checks
     * returns some messages if there is something to display
     *
     * @return string
     */
    protected function _doStartUpChecks()
    { // #661
        $aMessage = array();

        // check if system reguirements are ok
        $oSysReq = new oxSysRequirements();
        if (!$oSysReq->getSysReqStatus()) {
            $aMessage['warning'] = oxRegistry::getLang()->translateString('NAVIGATION_SYSREQ_MESSAGE');
            $aMessage['warning'] .= '<a href="?cl=sysreq&amp;stoken=' . $this->getSession()->getSessionChallengeToken() . '" target="basefrm">';
            $aMessage['warning'] .= oxRegistry::getLang()->translateString('NAVIGATION_SYSREQ_MESSAGE2') . '</a>';
        }

        // version check
        if ($this->getConfig()->getConfigParam('blCheckForUpdates')) {
            if ($sVersionNotice = $this->_checkVersion()) {
                $aMessage['message'] .= $sVersionNotice;
            }
        }


        // check if setup dir is deleted
        if (file_exists($this->getConfig()->getConfigParam('sShopDir') . '/setup/index.php')) {
            $aMessage['warning'] .= ((!empty($aMessage['warning'])) ? "<br>" : '') . oxRegistry::getLang()->translateString('SETUP_DIRNOTDELETED_WARNING');
        }

        // check if updateApp dir is deleted or empty
        $sUpdateDir = $this->getConfig()->getConfigParam('sShopDir') . '/updateApp/';
        if (file_exists($sUpdateDir) && !(count(glob("$sUpdateDir/*")) === 0)) {
            $aMessage['warning'] .= ((!empty($aMessage['warning'])) ? "<br>" : '') . oxRegistry::getLang()->translateString('UPDATEAPP_DIRNOTDELETED_WARNING');
        }

        // check if config file is writable
        $sConfPath = $this->getConfig()->getConfigParam('sShopDir') . "/config.inc.php";
        if (!is_readable($sConfPath) || is_writable($sConfPath)) {
            $aMessage['warning'] .= ((!empty($aMessage['warning'])) ? "<br>" : '') . oxRegistry::getLang()->translateString('SETUP_CONFIGPERMISSIONS_WARNING');
        }

        return $aMessage;
    }

    /**
     * Checks if newer shop version available. If true - returns message
     *
     * @return string
     */
    protected function _checkVersion()
    {
        $sVersion = 'PE';

        $sQuery = 'http://admin.oxid-esales.com/' . $sVersion . '/onlinecheck.php?getlatestversion';
        if ($sVersion = oxRegistry::get("oxUtilsFile")->readRemoteFileAsString($sQuery)) {
            // current version is older ..
            if (version_compare($this->getConfig()->getVersion(), $sVersion) == '-1') {
                return sprintf(oxRegistry::getLang()->translateString('NAVIGATION_NEWVERSIONAVAILABLE'), $sVersion);
            }
        }
    }
}
