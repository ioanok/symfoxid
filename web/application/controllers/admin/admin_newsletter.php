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
 * Admin newsletter manager.
 * Returns template, that arranges two other templates ("newsletter_list.tpl"
 * and "newsletter_main.tpl") to frame.
 * Admin Menu: Customer News -> Newsletter.
 */
class Admin_Newsletter extends oxAdminView
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'newsletter.tpl';
}
