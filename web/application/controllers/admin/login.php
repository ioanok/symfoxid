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
 * Administrator login form.
 * Performs administrator login form data collection.
 */
class Login extends oxAdminView
{

    /**
     * Sets value for _sThisAction to "login".
     */
    public function __construct()
    {
        $this->getConfig()->setConfigParam('blAdmin', true);
        $this->_sThisAction = "login";
    }

    /**
     * Executes parent method parent::render(), creates shop object, sets template parameters
     * and returns name of template file "login.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        // automatically redirect to SSL login
        if (!$myConfig->isSsl() && strpos($myConfig->getConfigParam('sAdminSSLURL'), 'https://') === 0) {
            oxRegistry::getUtils()->redirect($myConfig->getConfigParam('sAdminSSLURL'), false, 302);
        }

        //resets user once on this screen.
        $oUser = oxNew("oxuser");
        $oUser->logout();

        oxView::render();

        //if( $myConfig->blDemoMode)
        $oBaseShop = oxNew("oxshop");

        $oBaseShop->load($myConfig->getBaseShopId());
        $sVersion = $oBaseShop->oxshops__oxversion->value;

        $this->getViewConfig()->setViewConfigParam('sShopVersion', $sVersion);

        if ($myConfig->isDemoShop()) {
            // demo
            $this->addTplParam("user", "admin");
            $this->addTplParam("pwd", "admin");
        }
        //#533 user profile
        $this->addTplParam("profiles", oxRegistry::getUtils()->loadAdminProfile($myConfig->getConfigParam('aInterfaceProfiles')));

        $aLanguages = $this->_getAvailableLanguages();
        $this->addTplParam("aLanguages", $aLanguages);

        // setting templates language to selected language id
        foreach ($aLanguages as $iKey => $oLang) {
            if ($aLanguages[$iKey]->selected) {
                oxRegistry::getLang()->setTplLanguage($iKey);
                break;
            }
        }

        return "login.tpl";
    }

    /**
     * Checks user login data, on success returns "admin_start".
     *
     * @return mixed
     */
    public function checklogin()
    {
        $myUtilsServer = oxRegistry::get("oxUtilsServer");
        $myUtilsView = oxRegistry::get("oxUtilsView");

        $sUser = oxRegistry::getConfig()->getRequestParameter('user', true);
        $sPass = oxRegistry::getConfig()->getRequestParameter('pwd', true);
        $sProfile = oxRegistry::getConfig()->getRequestParameter('profile');

        try { // trying to login
            /** @var oxUser $oUser */
            $oUser = oxNew("oxuser");
            $oUser->login($sUser, $sPass);
            $iSubshop = (int) $oUser->oxuser__oxrights->value;
            if ($iSubshop) {
                oxRegistry::getSession()->setVariable("shp", $iSubshop);
                oxRegistry::getSession()->setVariable('currentadminshop', $iSubshop);
                oxRegistry::getConfig()->setShopId($iSubshop);
            }
        } catch (oxUserException $oEx) {
            $myUtilsView->addErrorToDisplay('LOGIN_ERROR');
            $oStr = getStr();
            $this->addTplParam('user', $oStr->htmlspecialchars($sUser));
            $this->addTplParam('pwd', $oStr->htmlspecialchars($sPass));
            $this->addTplParam('profile', $oStr->htmlspecialchars($sProfile));

            return;
        } catch (oxCookieException $oEx) {
            $myUtilsView->addErrorToDisplay('LOGIN_NO_COOKIE_SUPPORT');
            $oStr = getStr();
            $this->addTplParam('user', $oStr->htmlspecialchars($sUser));
            $this->addTplParam('pwd', $oStr->htmlspecialchars($sPass));
            $this->addTplParam('profile', $oStr->htmlspecialchars($sProfile));

            return;
        } catch (oxConnectionException $oEx) {
            $myUtilsView->addErrorToDisplay($oEx);
        }

        // success
        oxRegistry::getUtils()->logger("login successful");

        //execute onAdminLogin() event
        $oEvenHandler = oxNew("oxSystemEventHandler");
        $oEvenHandler->onAdminLogin(oxRegistry::getConfig()->getShopId());

        // #533
        if (isset($sProfile)) {
            $aProfiles = oxRegistry::getSession()->getVariable("aAdminProfiles");
            if ($aProfiles && isset($aProfiles[$sProfile])) {
                // setting cookie to store last locally used profile
                $myUtilsServer->setOxCookie("oxidadminprofile", $sProfile . "@" . implode("@", $aProfiles[$sProfile]), time() + 31536000, "/");
                oxRegistry::getSession()->setVariable("profile", $aProfiles[$sProfile]);
            }
        } else {
            //deleting cookie info, as setting profile to default
            $myUtilsServer->setOxCookie("oxidadminprofile", "", time() - 3600, "/");
        }

        // languages
        $iLang = oxRegistry::getConfig()->getRequestParameter("chlanguage");
        $aLanguages = oxRegistry::getLang()->getAdminTplLanguageArray();
        if (!isset($aLanguages[$iLang])) {
            $iLang = key($aLanguages);
        }

        $myUtilsServer->setOxCookie("oxidadminlanguage", $aLanguages[$iLang]->abbr, time() + 31536000, "/");

        //P
        //oxRegistry::getSession()->setVariable( "blAdminTemplateLanguage", $iLang );
        oxRegistry::getLang()->setTplLanguage($iLang);

        return "admin_start";
    }

    /**
     * authorization
     *
     * @return boolean
     */
    protected function _authorize()
    {
        // users are always authorized to use login page
        return true;
    }

    /**
     * Current view ID getter
     *
     * @return string
     */
    public function getViewId()
    {
        return strtolower(get_class($this));
    }

    /**
     * Returns message about shop validation
     *
     * @return string
     */
    public function getShopValidationMessage()
    {
        $sError = '';
        $oSerial = $this->getConfig()->getSerial();
        if ($oSerial->isGracePeriodStarted()) {
            $oSerial->validateShop();
            if (!$oSerial->isShopValid()) {
                $sError = $oSerial->getValidationMessage();
            }
        }

        return $sError;
    }

    /**
     * Returns whether shop grace period expired
     *
     * @return bool
     */
    public function isGracePeriodExpired()
    {
        $oSerial = $this->getConfig()->getSerial();

        return $oSerial->isGracePeriodExpired();
    }


    /**
     * Get available admin interface languages
     *
     * @return array
     */
    protected function _getAvailableLanguages()
    {
        $sDefLang = oxRegistry::get("oxUtilsServer")->getOxCookie('oxidadminlanguage');
        $sDefLang = $sDefLang ? $sDefLang : $this->_getBrowserLanguage();

        $aLanguages = oxRegistry::getLang()->getAdminTplLanguageArray();
        foreach ($aLanguages as $oLang) {
            $oLang->selected = ($sDefLang == $oLang->abbr) ? 1 : 0;
        }

        return $aLanguages;
    }

    /**
     * Get detected user browser language abbervation
     *
     * @return string
     */
    protected function _getBrowserLanguage()
    {
        return strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    }
}
