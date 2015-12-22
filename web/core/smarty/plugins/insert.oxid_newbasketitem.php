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
function smarty_insert_oxid_newbasketitem($params, &$smarty)
{
    $myConfig  = oxRegistry::getConfig();

    $aTypes = array('0' => 'none','1' => 'message', '2' =>'popup', '3' =>'basket');
    $iType  = $myConfig->getConfigParam( 'iNewBasketItemMessage' );

    // If corect type of message is expected
    if ($iType && $params['type'] && ($params['type'] != $aTypes[$iType] )) {
        return '';
    }

    //name of template file where is stored message text
    $sTemplate = $params['tpl']?$params['tpl']:'inc_newbasketitem.snippet.tpl';

    //allways render for ajaxstyle popup
    $blRender = $params['ajax'] && ($iType == 2);

    //fetching article data
    $oNewItem = oxRegistry::getSession()->getVariable( '_newitem' );
    $oBasket  = oxRegistry::getSession()->getBasket();

    if ( $oNewItem ) {
        // loading article object here because on some system passing article by session couses problems
        $oNewItem->oArticle = oxNew( 'oxarticle' );
        $oNewItem->oArticle->Load( $oNewItem->sId );

        // passing variable to template with unique name
        $smarty->assign( '_newitem', $oNewItem );

        // deleting article object data
        oxRegistry::getSession()->deleteVariable( '_newitem' );

        $blRender = true;
    }

    // returning generated message content
    if ( $blRender ) {
        return $smarty->fetch( $sTemplate );
    }
}
