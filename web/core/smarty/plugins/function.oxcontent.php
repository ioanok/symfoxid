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
 * File: insert.oxid_content.php
 * Type: string, html
 * Name: oxid_content
 * Purpose: Output content snippet
 * add [{ insert name="oxid_content" ident="..." }] where you want to display content
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxcontent( $params, &$smarty )
{
    $myConfig = oxRegistry::getConfig();
    $sText = $myConfig->getActiveShop()->oxshops__oxproductive->value ? null : "<b>content not found ! check ident(".$params['ident'].") !</b>";
    $smarty->oxidcache = new oxField($sText, oxField::T_RAW);

    $sIdent = isset( $params['ident'] )?$params['ident']:null;
    $sOxid  = isset( $params['oxid'] )?$params['oxid']:null;

    if ( $sIdent || $sOxid ) {
        $oContent = oxNew( "oxcontent" );
        if ( $sOxid ) {
            $blLoaded = $oContent->load( $sOxid );
        } else {
            $blLoaded = $oContent->loadbyIdent( $sIdent );
        }

        if ( $blLoaded && $oContent->oxcontents__oxactive->value ) {
            // set value
            $sField = "oxcontent";
            if ( $params['field'] ) {
                $sField = $params['field'];
            }
            // set value
            $sProp = 'oxcontents__'.$sField;
            $smarty->oxidcache = clone $oContent->$sProp;
            $smarty->compile_check  = true;
            $sCacheId = oxRegistry::getLang()->getBaseLanguage() . $myConfig->getShopId();
            $sText = $smarty->fetch( "ox:".(string)$sIdent.(string)$sOxid.$sField.$sCacheId);
            $smarty->compile_check  = $myConfig->getConfigParam( 'blCheckTemplates' );
        }
    }

    // if we write '[{oxcontent ident="oxemailfooterplain" assign="fs_text"}]' the content wont be outputted.
    // instead of this the content will be assigned to variable.
    if( isset( $params['assign']) && $params['assign'])
        $smarty->assign($params['assign'], $sText);
    else
        return $sText;

}
