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
 * Class handling CAPTCHA image
 * This class requires utility file utils/verificationimg.php as image generator
 *
 */
class oxCaptcha extends oxSuperCfg
{

    /**
     * CAPTCHA length
     *
     * @var int
     */
    protected $_iMacLength = 5;

    /**
     * Captcha text
     *
     * @var string
     */
    protected $_sText = null;

    /**
     * Possible CAPTCHA chars, no ambiguities
     *
     * @var string
     */
    private $_sMacChars = 'abcdefghijkmnpqrstuvwxyz23456789';

    /**
     * Captcha timeout 60 * 5 = 5 minutes
     *
     * @var int
     */
    protected $_iTimeout = 300;

    /**
     * Returns text
     *
     * @return string
     */
    public function getText()
    {
        if (!$this->_sText) {
            $this->_sText = '';
            for ($i = 0; $i < $this->_iMacLength; $i++) {
                $this->_sText .= strtolower($this->_sMacChars{rand(0, strlen($this->_sMacChars) - 1)});
            }
        }

        return $this->_sText;
    }

    /**
     * Returns text hash
     *
     * @param string $sText User supplie text
     *
     * @return string
     */
    public function getHash($sText = null)
    {
        // inserting captcha record
        $iTime = time() + $this->_iTimeout;
        $sTextHash = $this->getTextHash($sText);

        // if session is started - storing captcha info here
        $session = $this->getSession();
        if ($session->isSessionStarted()) {
            $sHash = oxUtilsObject::getInstance()->generateUID();
            $aHash = $session->getVariable("aCaptchaHash");
            $aHash[$sHash] = array($sTextHash => $iTime);
            $session->setVariable("aCaptchaHash", $aHash);
        } else {
            $oDb = oxDb::getDb();
            $sQ = "insert into oxcaptcha ( oxhash, oxtime ) values ( '{$sTextHash}', '{$iTime}' )";
            $oDb->execute($sQ);
            $sHash = $oDb->getOne("select LAST_INSERT_ID()", false, false);
        }

        return $sHash;
    }

    /**
     * Returns given string captcha hash
     *
     * @param string $sText string to hash
     *
     * @return string
     */
    public function getTextHash($sText)
    {
        if (!$sText) {
            $sText = $this->getText();
        }

        $sText = strtolower($sText);

        return md5("ox{$sText}");
    }

    /**
     * Returns url to CAPTCHA image generator.
     *
     * @return string
     */
    public function getImageUrl()
    {
        $sUrl = $this->getConfig()->getCoreUtilsURL() . "verificationimg.php?e_mac=";
        $sUrl .= oxRegistry::getUtils()->strMan($this->getText());

        return $sUrl;
    }

    /**
     * Checks if image could be generated
     *
     * @return bool
     */
    public function isImageVisible()
    {
        return ((function_exists('imagecreatetruecolor') || function_exists('imagecreate')) && $this->getConfig()->getConfigParam('iUseGDVersion') > 1);
    }

    /**
     * Checks for session captcha hash validity
     *
     * @param string $sMacHash hash key
     * @param string $sHash    captcha hash
     * @param int    $iTime    check time
     *
     * @return bool
     */
    protected function _passFromSession($sMacHash, $sHash, $iTime)
    {
        $blPass = null;
        $oSession = $this->getSession();
        if (($aHash = $oSession->getVariable("aCaptchaHash"))) {
            $blPass = (isset($aHash[$sMacHash][$sHash]) && $aHash[$sMacHash][$sHash] >= $iTime) ? true : false;
            unset($aHash[$sMacHash]);
            if (!empty($aHash)) {
                $oSession->setVariable("aCaptchaHash", $aHash);
            } else {
                $oSession->deleteVariable("aCaptchaHash");
            }
        }

        return $blPass;
    }

    /**
     * Checks for DB captcha hash validity
     *
     * @param int    $iMacHash hash key
     * @param string $sHash    captcha hash
     * @param int    $iTime    check time
     *
     * @return bool
     */
    protected function _passFromDb($iMacHash, $sHash, $iTime)
    {
        $blPass = false;

        $oDb = oxDb::getDb();
        $sQ = "select 1 from oxcaptcha where oxid = {$iMacHash} and oxhash = '{$sHash}'";
        if (($blPass = (bool) $oDb->getOne($sQ, false, false))) {
            // cleanup
            $sQ = "delete from oxcaptcha where oxid = {$iMacHash} and oxhash = '{$sHash}'";
            $oDb->execute($sQ);
        }

        // garbage cleanup
        $sQ = "delete from oxcaptcha where oxtime < $iTime";
        $oDb->execute($sQ);

        return $blPass;
    }

    /**
     * Verifies captcha input vs supplied hash. Returns true on success.
     *
     * @param string $sMac     User supplied text
     * @param string $sMacHash Generated hash
     *
     * @return bool
     */
    public function pass($sMac, $sMacHash)
    {
        $iTime = time();
        $sHash = $this->getTextHash($sMac);

        $blPass = $this->_passFromSession($sMacHash, $sHash, $iTime);

        // if captha info was NOT stored in session
        if ($blPass === null) {
            $blPass = $this->_passFromDb((int) $sMacHash, $sHash, $iTime);
        }

        return (bool) $blPass;
    }
}
