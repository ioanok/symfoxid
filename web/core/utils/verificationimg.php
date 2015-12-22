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

// #1428C - spam spider prevention
if (isset($_GET['e_mac'])) {
    $sEMac = $_GET['e_mac'];
} else {
    return;
}

require_once '../oxfunctions.php';

if (!function_exists('generateVerificationImg')) {

    /**
     * Generates image
     *
     * @param string $sMac verification code
     *
     * @return null
     */
    function generateVerificationImg($sMac)
    {
        $iWidth = 80;
        $iHeight = 18;
        $iFontSize = 14;

        if (function_exists('imagecreatetruecolor')) {
            // GD2
            $oImage = imagecreatetruecolor($iWidth, $iHeight);
        } elseif (function_exists('imagecreate')) {
            // GD1
            $oImage = imagecreate($iWidth, $iHeight);
        } else {
            // GD not found
            return;
        }

        $iTextX = ($iWidth - strlen($sMac) * imagefontwidth($iFontSize)) / 2;
        $iTextY = ($iHeight - imagefontheight($iFontSize)) / 2;

        $aColors = array();
        $aColors["text"] = imagecolorallocate($oImage, 0, 0, 0);
        $aColors["shadow1"] = imagecolorallocate($oImage, 200, 200, 200);
        $aColors["shadow2"] = imagecolorallocate($oImage, 100, 100, 100);
        $aColors["background"] = imagecolorallocate($oImage, 255, 255, 255);
        $aColors["border"] = imagecolorallocate($oImage, 0, 0, 0);

        imagefill($oImage, 0, 0, $aColors["background"]);
        imagerectangle($oImage, 0, 0, $iWidth - 1, $iHeight - 1, $aColors["border"]);
        imagestring($oImage, $iFontSize, $iTextX + 1, $iTextY + 0, $sMac, $aColors["shadow2"]);
        imagestring($oImage, $iFontSize, $iTextX + 0, $iTextY + 1, $sMac, $aColors["shadow1"]);
        imagestring($oImage, $iFontSize, $iTextX, $iTextY, $sMac, $aColors["text"]);

        header('Content-type: image/png');
        imagepng($oImage);
        imagedestroy($oImage);
    }
}

if (!function_exists('strRem')) {

    require_once '../oxdecryptor.php';

    /**
     * OXID specific string manipulation method
     *
     * @param string $sVal string
     *
     * @return string
     */
    function strRem($sVal)
    {
        $oDecryptor = new oxDecryptor;

        $oCfg = new oxConfKey();
        $sKey = $oCfg->sConfigKey;

        return $oDecryptor->decrypt($sVal, $sKey);
    }
}

/**
 * Simple class returning config key.
 */
class oxConfKey
{

    /**
     * @var $sConfigKey string
     */
    public $sConfigKey;

    /**
     * Config class constructor.
     */
    public function __construct()
    {
        include_once '../oxconfk.php';
    }

    /**
     * Config key getter.
     *
     * @return string
     */
    public function get()
    {
        return $this->sConfigKey;
    }
}

$sMac = strRem($sEMac);

generateVerificationImg($sMac);
