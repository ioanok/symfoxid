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
 * Themes handler class.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 */
class oxTheme extends oxSuperCfg
{

    /**
     * Theme info array
     *
     * @var array
     */
    protected $_aTheme = array();

    /**
     * Theme info list
     *
     * @var array
     */
    protected $_aThemeList = array();

    /**
     * Load theme info
     *
     * @param string $sOXID theme id
     *
     * @return bool
     */
    public function load($sOXID)
    {
        $sFilePath = $this->getConfig()->getViewsDir() . $sOXID . "/theme.php";
        if (file_exists($sFilePath) && is_readable($sFilePath)) {
            $aTheme = array();
            include $sFilePath;
            $this->_aTheme = $aTheme;
            $this->_aTheme['id'] = $sOXID;
            $this->_aTheme['active'] = ($this->getActiveThemeId() == $sOXID);

            return true;
        }

        return false;
    }

    /**
     * Set theme as active
     */
    public function activate()
    {
        $sError = $this->checkForActivationErrors();
        if ($sError) {
            /** @var oxException $oException */
            $oException = oxNew("oxException", $sError);
            throw $oException;
        }
        $sParent = $this->getInfo('parentTheme');
        if ($sParent) {
            $this->getConfig()->saveShopConfVar("str", 'sTheme', $sParent);
            $this->getConfig()->saveShopConfVar("str", 'sCustomTheme', $this->getId());
        } else {
            $this->getConfig()->saveShopConfVar("str", 'sTheme', $this->getId());
            $this->getConfig()->saveShopConfVar("str", 'sCustomTheme', '');
        }
    }

    /**
     * Load theme info list
     *
     * @return array
     */
    public function getList()
    {
        $this->_aThemeList = array();
        $sOutDir = $this->getConfig()->getViewsDir();
        foreach (glob($sOutDir . "*", GLOB_ONLYDIR) as $sDir) {
            $oTheme = oxNew('oxTheme');
            if ($oTheme->load(basename($sDir))) {
                $this->_aThemeList[$sDir] = $oTheme;
            }
        }

        return $this->_aThemeList;
    }

    /**
     * get theme info item
     *
     * @param string $sName name of info item to retrieve
     *
     * @return mixed
     */
    public function getInfo($sName)
    {
        if (!isset($this->_aTheme[$sName])) {
            return null;
        }

        return $this->_aTheme[$sName];
    }

    /**
     * return current active theme, or custom theme if specified
     *
     * @return string
     */
    public function getActiveThemeId()
    {
        $sCustTheme = $this->getConfig()->getConfigParam('sCustomTheme');
        if ($sCustTheme) {
            return $sCustTheme;
        }

        return $this->getConfig()->getConfigParam('sTheme');
    }

    /**
     * return loaded parent
     *
     * @return oxTheme
     */
    public function getParent()
    {
        $sParent = $this->getInfo('parentTheme');
        if (!$sParent) {
            return null;
        }
        $oTheme = oxNew('oxTheme');
        if ($oTheme->load($sParent)) {
            return $oTheme;
        }

        return null;
    }

    /**
     * run pre-activation checks and return EXCEPTION_* translation string if error
     * found or false on success
     *
     * @return string
     */
    public function checkForActivationErrors()
    {
        if (!$this->getId()) {
            return 'EXCEPTION_THEME_NOT_LOADED';
        }
        $oParent = $this->getParent();
        if ($oParent) {
            $sParentVersion = $oParent->getInfo('version');
            if (!$sParentVersion) {
                return 'EXCEPTION_PARENT_VERSION_UNSPECIFIED';
            }
            $aMyParentVersions = $this->getInfo('parentVersions');
            if (!$aMyParentVersions || !is_array($aMyParentVersions)) {
                return 'EXCEPTION_UNSPECIFIED_PARENT_VERSIONS';
            }
            if (!in_array($sParentVersion, $aMyParentVersions)) {
                return 'EXCEPTION_PARENT_VERSION_MISMATCH';
            }
        } elseif ($this->getInfo('parentTheme')) {
            return 'EXCEPTION_PARENT_THEME_NOT_FOUND';
        }

        return false;
    }

    /**
     * Get theme ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->getInfo("id");
    }
}
