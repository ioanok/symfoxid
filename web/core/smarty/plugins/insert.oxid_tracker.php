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
 * File: insert.oxid_tracker.php
 * Type: string, html
 * Name: oxid_tracker
 * Purpose: Output etracker code or Econda Code
 * add [{ insert name="oxid_tracker" title="..." }] after Body Tag in Templates
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_insert_oxid_tracker( $params, &$smarty )
{
    $myConfig = oxRegistry::getConfig();
    // econda is on ?
    if ( $myConfig->getConfigParam( 'blEcondaActive' ) ) {
        include_once $myConfig->getConfigParam( 'sCoreDir' ).'smarty/plugins/oxemosadapter.php';

        $sOutput = oxRegistry::get("oxEmosAdapter")->getCode( $params, $smarty );

        // returning JS code to output
        if ( strlen( trim( $sOutput ) ) ) {
            return "<div style=\"display:none;\">{$sOutput}</div>";
        }
    }
}
