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
 * Encrypt string with sha512 algorithm.
 */
class oxSha512Hasher extends oxHasher
{

    /** Algorithm name. */
    const HASHING_ALGORITHM_SHA512 = 'sha512';

    /**
     * Encrypt string.
     *
     * @param string $sString
     *
     * @return string
     */
    public function hash($sString)
    {
        return hash(self::HASHING_ALGORITHM_SHA512, $sString);
    }
}
