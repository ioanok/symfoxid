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
 */

/**
 * PayPal escape class
 */
class oePayPalEscape
{
    /**
     * Checks if passed parameter has special chars and replaces them.
     * Returns checked value.
     *
     * @param mixed $sValue value to process escaping
     *
     * @return mixed
     */
    public function escapeSpecialChars($sValue)
    {
        if (is_object($sValue)) {
            return $sValue;
        }

        if (is_array($sValue)) {
            $sValue = $this->_escapeArraySpecialChars($sValue);
        } elseif (is_string($sValue)) {
            $sValue = $this->_escapeStringSpecialChars($sValue);
        }

        return $sValue;
    }

    /**
     * Checks if passed parameter has special chars and replaces them.
     * Returns checked value.
     *
     * @param array $sValue value to process escaping
     *
     * @return array
     */
    private function _escapeArraySpecialChars($sValue)
    {
        $newValue = array();
        foreach ($sValue as $sKey => $sVal) {
            $sValidKey = $sKey;
            $sValidKey = $this->escapeSpecialChars($sValidKey);
            $sVal = $this->escapeSpecialChars($sVal);
            if ($sValidKey != $sKey) {
                unset ($sValue[$sKey]);
            }
            $newValue[$sValidKey] = $sVal;
        }

        return $newValue;
    }

    /**
     * Checks if passed parameter has special chars and replaces them.
     * Returns checked value.
     *
     * @param string $sValue value to process escaping
     *
     * @return string
     */
    private function _escapeStringSpecialChars($sValue)
    {
        $sValue = str_replace(
            array('&', '<', '>', '"', "'", chr(0), '\\'),
            array('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '', '&#092;'),
            $sValue
        );

        return $sValue;
    }
}
