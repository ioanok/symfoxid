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
 * Database field description object.
 *
 * when a value is requested from oxField, it either takes 'value' field or
 * 'rawValue' (depending which one is available).
 * In case 'rawValue' is taken, it is escaped first before returning.
 *
 * T_RAW and T_TEXT types represent not the assignment logic, but rather a
 * returned value escaping status.
 *
 */
class oxField // extends oxSuperCfg
{

    /**
     * escaping functionality type: expected value is escaped text.
     */
    const T_TEXT = 1;

    /**
     * escaping functionality type: expected value is not escaped (raw) text.
     */
    const T_RAW = 2;

    /**
     * Constructor
     * Initial value assigment is coded here by not calling a function is for performance
     * because oxField is created MANY times and even a function call matters
     *
     * if T_RAW is used, then it fills $value, because this is the value, that does
     * not need to be escaped and is by definition equal to $rawValue (which is not set
     * for less memory usage).
     *
     * if T_TEXT is used, then $rawValue is assigned and retrieved $value (which is not
     * set initially) is escaped $rawValue.
     *
     * e.g.
     * > if your input is "<b>string</b>" and you want your output to be exactly same,
     *   you should use T_RAW - in this way it will be assigned to $value property as
     *   the result.
     * > if your input is "1 & (a < b)" and you want your output to be escaped, you
     *   should use T_TEXT - in this way it will be assigned to $rawValue property and
     *   it will be escaped.
     *
     * @param mixed $value Field value
     * @param int   $type  Value type
     *
     * @return null
     */
    public function __construct($value = null, $type = self::T_TEXT)
    {
        // duplicate content here is needed for performance.
        // as this function is called *many* (a lot) times, it is crucial to be fast here!
        switch ($type) {
            case self::T_TEXT:
            default:
                $this->rawValue = $value;
                break;
            case self::T_RAW:
                $this->value = $value;
                break;
        }
    }

    /**
     * Checks if $name is set
     *
     * @param string $sName Variable name
     *
     * @return boolean
     */
    public function __isset($sName)
    {
        switch ($sName) {
            case 'rawValue':
                return ($this->rawValue !== null);
                break;
            case 'value':
                return ($this->value !== null);
                break;
            //return true;
        }
        return false;
    }

    /**
     * Magic getter
     *
     * @param string $sName Variable name
     *
     * @return string | null
     */
    public function __get($sName)
    {
        switch ($sName) {
            case 'rawValue':
                return $this->value;
                break;
            case 'value':
                if (is_string($this->rawValue)) {
                    $this->value = getStr()->htmlspecialchars($this->rawValue);
                } else {
                    // TODO: call htmlentities for each value (recursively?)
                    $this->value = $this->rawValue;
                }
                if ($this->rawValue == $this->value) {
                    unset($this->rawValue);
                }

                return $this->value;
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Returns actual field value
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Converts to formatted db date
     */
    public function convertToFormattedDbDate()
    {
        $this->setValue(oxRegistry::get("oxUtilsDate")->formatDBDate($this->rawValue), self::T_RAW);
    }

    /**
     * Converts to pseudo html - new lines to <br /> tags
     */
    public function convertToPseudoHtml()
    {
        $this->setValue(str_replace("\r", '', nl2br(getStr()->htmlspecialchars($this->rawValue))), self::T_RAW);
    }

    /**
     * Initial field value
     *
     * @param mixed $value Field value
     * @param int   $type  Value type
     */
    protected function _initValue($value = null, $type = self::T_TEXT)
    {
        switch ($type) {
            case self::T_TEXT:
                $this->rawValue = $value;
                break;
            case self::T_RAW:
                $this->value = $value;
                break;
        }
    }

    /**
     * Sets field value and type
     *
     * @param mixed $value Field value
     * @param int   $type  Value type
     */
    public function setValue($value = null, $type = self::T_TEXT)
    {
        unset($this->rawValue);
        unset($this->value);
        $this->_initValue($value, $type);
    }

    /**
     * Return raw value
     *
     * @return string
     */
    public function getRawValue()
    {
        if (null === $this->rawValue) {
            return $this->value;
        };

        return $this->rawValue;
    }
}
