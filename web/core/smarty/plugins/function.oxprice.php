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
 * Smarty function
 * -------------------------------------------------------------
 * Purpose: Output price string
 * add [{ oxprice price="..." currency="..." }] where you want to display content
 * price - decimal number: 13; 12.45; 13.01;
 * currency - currency abbreviation: EUR, USD, LTL etc.
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
*/
function smarty_function_oxprice( $params, &$smarty )
{
    $sOutput = '';
    $iDecimals = 2;
    $sDecimalsSeparator = ',';
    $sThousandSeparator = '.';
    $sCurrencySign = '';
    $sSide = '';
    $mPrice = $params['price'];

    if ( !is_null( $mPrice ) ) {

        $sPrice = ( $mPrice instanceof oxPrice ) ? $mPrice->getPrice() : $mPrice;
        $oCurrency = isset( $params['currency'] ) ? $params['currency'] : null;

        if ( !is_null( $oCurrency ) ) {
            $sDecimalsSeparator = isset( $oCurrency->dec ) ? $oCurrency->dec : $sDecimalsSeparator;
            $sThousandSeparator = isset( $oCurrency->thousand ) ? $oCurrency->thousand : $sThousandSeparator;
            $sCurrencySign = isset( $oCurrency->sign ) ? $oCurrency->sign : $sCurrencySign;
            $sSide = isset( $oCurrency->side ) ? $oCurrency->side : $sSide;
            $iDecimals = isset( $oCurrency->decimal ) ? (int) $oCurrency->decimal : $iDecimals;
        }

        if ( is_numeric( $sPrice ) ) {
            if ( (float) $sPrice > 0 || $sCurrencySign  ) {
                $sPrice = number_format( $sPrice, $iDecimals, $sDecimalsSeparator, $sThousandSeparator );
                $sOutput = ( isset($sSide) && $sSide == 'Front' ) ? $sCurrencySign . $sPrice : $sPrice . ' ' . $sCurrencySign;
            }

            $sOutput = trim($sOutput);
        }
    }

    return $sOutput;
}
