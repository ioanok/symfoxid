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
 * SEPA (Single Euro Payments Area) BIC validation class
 *
 */
class oxSepaBICValidator
{

    /**
     * Business identifier code validation
     *
     * Structure
     *  - 4 letters: Institution Code or bank code.
     *  - 2 letters: ISO 3166-1 alpha-2 country code
     *  - 2 letters or digits: location code
     *  - 3 letters or digits: branch code, optional
     *
     * @param string $sBIC code to check
     *
     * @return bool
     */
    public function isValid($sBIC)
    {
        $sBIC = strtoupper(trim($sBIC));

        return (bool) getStr()->preg_match("(^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$)", $sBIC);
    }
}
