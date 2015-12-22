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
 * Online license check request class used as entity.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 *
 * @ignore   This class will not be included in documentation.
 */
class oxOnlineLicenseCheckRequest extends oxOnlineRequest
{
    /**
     * Web service protocol version.
     *
     * @var string
     */
    public $pVersion = '1.1';

    /**
     * Serial keys.
     *
     * @var string
     */
    public $keys;

    /**
     * Build revision number.
     *
     * @var string
     */
    public $revision;

    /**
     * Product related specific information
     * like amount of sub shops and amount of admin users.
     *
     * @var object
     */
    public $productSpecificInformation;
}
