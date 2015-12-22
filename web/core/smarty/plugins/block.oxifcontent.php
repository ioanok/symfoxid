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
 * File: block.oxid_content.php
 * Type: string, html
 * Name: block_oxifcontent
 * Purpose: Output content snippet if content exists
 * add [{oxifcontent ident="..." }][{/oxifcontent}] where you want to display content
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param string $content rendered content
 * @param Smarty &$smarty clever simulation of a method
 * @param bool   &$repeat repeat
 *
 * @return string
 */
function smarty_block_oxifcontent( $params, $content, &$smarty, &$repeat)
{
    $myConfig = oxRegistry::getConfig();

    $sIdent  = isset( $params['ident'] )?$params['ident']:null;
    $sOxid   = isset( $params['oxid'] )?$params['oxid']:null;
    $sAssign = isset( $params['assign'])?$params['assign']:null;
    $sObject = isset( $params['object'])?$params['object']:'oCont';

    if ($repeat) {
        if ( $sIdent || $sOxid ) {

            static $aContentCache = array();

            if ( ( $sIdent && isset( $aContentCache[$sIdent] ) ) ||
                 ( $sOxid && isset( $aContentCache[$sOxid] ) ) ) {
                $oContent = $sOxid ? $aContentCache[$sOxid] : $aContentCache[$sIdent];
            } else {
                $oContent = oxNew( "oxContent" );
                $blLoaded = $sOxid ? $oContent->load( $sOxid ) : ( $oContent->loadbyIdent( $sIdent ) );
                if ( $blLoaded && $oContent->isActive() ) {
                    $aContentCache[$oContent->getId()] = $aContentCache[$oContent->getLoadId()] = $oContent;
                } else {
                    $oContent = false;
                    if ( $sOxid ) {
                        $aContentCache[$sOxid] = $oContent;
                    } else {
                        $aContentCache[$sIdent] = $oContent;
                    }
                }
            }

            $blLoaded = false;
            if ( $oContent ) {
                $smarty->assign($sObject, $oContent);
                $blLoaded = true;
            }
        } else {
            $blLoaded = false;
        }
        $repeat = $blLoaded;
    } else {
        $oStr = getStr();
        $blHasSmarty = $oStr->strstr( $content, '[{' );
        if ( $blHasSmarty  ) {
            $content = oxRegistry::get("oxUtilsView")->parseThroughSmarty( $content, $sIdent.md5($content), $myConfig->getActiveView() );
        }

        if ($sAssign) {
            $smarty->assign($sAssign, $content);
        } else {
            return $content;
        }
    }

}
