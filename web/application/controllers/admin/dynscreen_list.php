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
 * Admin dynscreen list manager.
 * Arranges controll tabs and sets title.
 *
 * @subpackage dyn
 */
class Dynscreen_List extends Dynscreen
{

    /**
     * Executes marent method parent::render() and returns mane of template
     * file "dynscreen_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $this->_aViewData['menu'] = basename(oxRegistry::getConfig()->getRequestParameter("menu"));

        return "dynscreen_list.tpl";
    }
}
