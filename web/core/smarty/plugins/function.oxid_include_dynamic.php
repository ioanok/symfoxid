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
 * Purpose: render or leave dynamic parts with parameters in
 * templates used by content caching algorithm.
 * Use [{ oxid_include_dynamic file="..." }] instead of include
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxid_include_dynamic($params, &$smarty)
{
    $params = array_change_key_case($params, CASE_LOWER);

    if (!isset($params['file'])) {
        $smarty->trigger_error("oxid_include_dynamic: missing 'file' parameter");
        return;
    }

    if ( !empty($smarty->_tpl_vars["_render4cache"]) ) {
        $sContent = "<oxid_dynamic>";
        foreach ($params as $key => $val) {
            $sContent .= " $key='".base64_encode($val)."'";
        }
        $sContent .= "</oxid_dynamic>";
        return $sContent;
    } else {
        $sPrefix="_";
        if ( array_key_exists('type', $params) ) {
            $sPrefix.= $params['type']."_";
        }

        foreach ($params as $key => $val) {
            if ($key != 'type' && $key != 'file') {
                $sContent .= " $key='$val'";
                $smarty->assign($sPrefix.$key, $val);
            }
        }

        $smarty->assign("__oxid_include_dynamic", true);
        $sRes = $smarty->fetch($params['file']);
        $smarty->clear_assign("__oxid_include_dynamic");
        return $sRes;
    }
}
