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
 * oxTagCloud set interface
 *
 */
interface oxITagList
{

    /**
     * Returns cache id, on which tagcloud should cache content.
     * If null is returned, content will not be cached.
     *
     * @return string
     */
    public function getCacheId();

    /**
     * Loads tagcloud set
     *
     * @return boolean
     */
    public function loadList();

    /**
     * Returns tagcloud set
     *
     * @return oxTagSet
     */
    public function get();
}
