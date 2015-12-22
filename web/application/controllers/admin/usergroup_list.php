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
 * Admin usergroup list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: User Administration -> User Groups.
 */
class UserGroup_List extends oxAdminList
{

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxgroups';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = "oxtitle";

    /**
     * Executes parent method parent::render() and returns name of template
     * file "usergroup_list.tpl".
     *
     * @return string
     */
    public function render()
    {

        parent::render();

        return "usergroup_list.tpl";
    }
}
