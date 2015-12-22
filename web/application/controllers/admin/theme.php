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
 * Admin theme manager.
 * Returns template, that arranges two other templates ("theme_list.tpl"
 * and "theme_main.tpl") to frame.
 * Admin Menu: Main Menu -> Theme.
 */
class Theme extends oxAdminView
{

    /**
     * Executes parent method parent::render() and returns name of template
     * file "theme.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        return "theme.tpl";
    }
}
