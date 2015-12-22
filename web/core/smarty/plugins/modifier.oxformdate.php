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
 * Smarty modifier
 * -------------------------------------------------------------
 * Name:     smarty_modifier_oxformdate<br>
 * Purpose:  Conterts date/timestamp/datetime type value to user defined format
 * Example:  {$object|oxformdate:"foo"}
 * -------------------------------------------------------------
 *
 * @param object $oConvObject   oxField object
 * @param string $sFieldType    additional type if field (this may help to force formatting)
 * @param bool   $blPassedValue bool if true, will simulate object as sometimes we need to apply formatting to some regulat values
 *
 * @return string
 */
function smarty_modifier_oxformdate( $oConvObject, $sFieldType = null, $blPassedValue = false)
{   // creating fake bject
    if ( $blPassedValue || is_string($oConvObject) ) {
        $sValue = $oConvObject;
        $oConvObject = new oxField();
        $oConvObject->fldmax_length = "0";
        $oConvObject->fldtype = $sFieldType;
        $oConvObject->setValue($sValue);
    }

    $myConfig = oxRegistry::getConfig();

    // if such format applies to this type of field - sets formatted value to passed object
    if ( !$myConfig->getConfigParam( 'blSkipFormatConversion' ) ) {
        if ( $oConvObject->fldtype == "datetime" || $sFieldType == "datetime")
            oxRegistry::get('oxUtilsDate')->convertDBDateTime( $oConvObject );
        elseif ( $oConvObject->fldtype == "timestamp" || $sFieldType == "timestamp")
            oxRegistry::get('oxUtilsDate')->convertDBTimestamp( $oConvObject );
        elseif ( $oConvObject->fldtype == "date" || $sFieldType == "date")
            oxRegistry::get('oxUtilsDate')->convertDBDate( $oConvObject );
    }

    return $oConvObject->value;
}

/* vim: set expandtab: */
