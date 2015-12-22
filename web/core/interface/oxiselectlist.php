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
 * Interface for selection list based objects
 *
 */
interface oxISelectList
{

    /**
     * Returns selection list label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns array of oxSelection's
     *
     * @return array
     */
    public function getSelections();

    /**
     * Returns active selection object
     *
     * @return oxSelection
     */
    public function getActiveSelection();
}
