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
 * Admin statistic list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Statistics -> Show.
 */
class Statistic_List extends oxAdminList
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'statistic_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxstatistic';
}
