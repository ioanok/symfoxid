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
 * Smarty plugin
 * -------------------------------------------------------------
 * File: function.oxstyle.php
 * Type: string, html
 * Name: oxstyle
 * Purpose: Collect given css files. but include them only at the top of the page.
 *
 * Add [{oxstyle include="oxid.css"}] to include local css file.
 * Add [{oxstyle include="oxid.css?20120413"}] to include local css file with query string part.
 * Add [{oxstyle include="http://www.oxid-esales.com/oxid.css"}] to include external css file.
 *
 * IMPORTANT!
 * Do not forget to add plain [{oxstyle}] tag where you need to output all collected css includes.
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxstyle($params, &$smarty)
{
    $myConfig   = oxRegistry::getConfig();
    $sSuffix     = !empty($smarty->_tpl_vars["__oxid_include_dynamic"]) ? '_dynamic' : '';
    $sWidget    = !empty($params['widget']) ? $params['widget' ] : '';
    $blInWidget = !empty($params['inWidget']) ? $params['inWidget'] : false;

    $sCStyles  = 'conditional_styles'.$sSuffix;
    $sStyles  = 'styles'.$sSuffix;

    $aCStyles  = (array) $myConfig->getGlobalParameter($sCStyles);
    $aStyles  = (array) $myConfig->getGlobalParameter($sStyles);


    if ( $sWidget && !$blInWidget ) {
        return;
    }

    $sOutput  = '';
    if ( !empty($params['include']) ) {
        $sStyle = $params['include'];
        if (!preg_match('#^https?://#', $sStyle)) {
            $sOriginalStyle = $sStyle;

            // Separate query part #3305.
            $aStyle = explode('?', $sStyle);
            $sStyle = $aStyle[0] = $myConfig->getResourceUrl($aStyle[0], $myConfig->isAdmin());

            if ($sStyle && count($aStyle) > 1) {
                // Append query part if still needed #3305.
                $sStyle .= '?'.$aStyle[1];
            } elseif ($sSPath = $myConfig->getResourcePath($sOriginalStyle, $myConfig->isAdmin())) {
                // Append file modification timestamp #3725.
                $sStyle .= '?'.filemtime($sSPath);
            }
        }

        // File not found ?
        if (!$sStyle) {
            if ($myConfig->getConfigParam( 'iDebug' ) != 0) {
                $sError = "{oxstyle} resource not found: ".getStr()->htmlspecialchars($params['include']);
                trigger_error($sError, E_USER_WARNING);
            }
            return;
        }

        // Conditional comment ?
        if ( !empty($params['if']) ) {
            $aCStyles[$sStyle] = $params['if'];
            $myConfig->setGlobalParameter($sCStyles, $aCStyles);
        } else {
            $aStyles[] = $sStyle;
            $aStyles = array_unique($aStyles);
            $myConfig->setGlobalParameter($sStyles, $aStyles);
        }
    } else {
        foreach ($aStyles as $sSrc) {
            $sOutput .= '<link rel="stylesheet" type="text/css" href="'.$sSrc.'" />'.PHP_EOL;
        }
        foreach ($aCStyles as $sSrc => $sCondition) {
            $sOutput .= '<!--[if '.$sCondition.']><link rel="stylesheet" type="text/css" href="'.$sSrc.'"><![endif]-->'.PHP_EOL;
        }
    }

    return $sOutput;
}
