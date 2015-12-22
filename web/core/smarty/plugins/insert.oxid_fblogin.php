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
 * File: insert.oxid_newbasketitem.php
 * Type: string, html
 * Name: newbasketitem
 * Purpose: Used for tracking in econda, etracker etc.
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_insert_oxid_fblogin($params, &$smarty)
{
    $myConfig  = oxRegistry::getConfig();
    $oView = $myConfig->getActiveView();

    if ( !$myConfig->getConfigParam( "bl_showFbConnect") ) {
        return;
    }

    // user logged in using facebook account so showing additional
    // popup about connecting facebook user id to existing shop account
    $oFb = oxRegistry::get("oxFb");

    if ( $oFb->isConnected() && $oFb->getUser() ) {

        //name of template
        $sTemplate = 'inc/popup_fblogin.tpl';

        // checking, if Facebeook User Id was successfully added
        if ( oxRegistry::getSession()->getVariable( '_blFbUserIdUpdated' ) ) {
            $sTemplate = 'inc/popup_fblogin_msg.tpl';
            oxRegistry::getSession()->deleteVariable( '_blFbUserIdUpdated' );
        }

        return $smarty->fetch( $sTemplate );
    }
}
