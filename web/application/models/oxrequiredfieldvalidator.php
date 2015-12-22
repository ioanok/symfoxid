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
 * Class for validating address
 *
 */
class oxRequiredFieldValidator
{

    /**
     * Validates field value.
     *
     * @param string $sFieldValue Field value
     *
     * @return bool
     */
    public function validateFieldValue($sFieldValue)
    {
        $blValid = true;
        if (is_array($sFieldValue)) {
            $blValid = $this->_validateFieldValueArray($sFieldValue);
        } else {
            if (!trim($sFieldValue)) {
                $blValid = false;
            }
        }

        return $blValid;
    }

    /**
     * Checks if all values are filled up
     *
     * @param array $aFieldValues field values
     *
     * @return bool
     */
    private function _validateFieldValueArray($aFieldValues)
    {
        $blValid = true;
        foreach ($aFieldValues as $sValue) {
            if (!trim($sValue)) {
                $blValid = false;
                break;
            }
        }

        return $blValid;
    }
}
