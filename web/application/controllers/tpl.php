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
 * Template preparation class.
 * Used only in some specific cases (usually when you need to outpt just template
 * having text information).
 */
class Tpl extends oxUBase
{

    /**
     * Executes parent method parent::render(), returns name of template file.
     *
     * @return  string  $sTplName   template file name
     */
    public function render()
    {
        parent::render();

        // security fix so that you cant access files from outside template dir
        $sTplName = basename((string) oxRegistry::getConfig()->getRequestParameter("tpl"));
        if ($sTplName) {
            $sTplName = 'custom/' . $sTplName;
        }

        return $sTplName;
    }
}
