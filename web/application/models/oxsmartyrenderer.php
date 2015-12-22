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
 * Smarty renderer class
 * Renders smarty template with given parameters and returns rendered body.
 *
 */
class oxSmartyRenderer
{

    /**
     * Template renderer
     *
     * @param string $sTemplateName Template name.
     * @param array  $aViewData     Array of view data (optional).
     *
     * @return string
     */
    public function renderTemplate($sTemplateName, $aViewData = array())
    {
        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();

        foreach ($aViewData as $key => $value) {
            $oSmarty->assign($key, $value);
        }

        $sBody = $oSmarty->fetch($sTemplateName);

        return $sBody;
    }
}
