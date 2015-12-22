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
 * List manager class.
 */
class oePayPalList implements Iterator, Countable
{
    /**
     * Array of objects (some object list).
     *
     * @var array $_aArray
     */
    protected $_aArray = array();

    /**
     * Save the state, that active element was unset
     * needed for proper foreach iterator functionality.
     *
     * @var bool $_blRemovedActive
     */
    protected $_blRemovedActive = false;

    /**
     * Flag if array is ok or not.
     *
     * @var boolean $_blValid
     */
    private $_blValid = true;

    /**
     * -----------------------------------------------------------------------------------------------------
     *
     * Implementation of SPL Array classes functions follows here
     *
     * -----------------------------------------------------------------------------------------------------
     */

    /**
     * Returns SPL array keys.
     *
     * @return array
     */
    public function arrayKeys()
    {
        return array_keys($this->_aArray);
    }

    /**
     * Rewind for SPL.
     */
    public function rewind()
    {
        $this->_blRemovedActive = false;
        $this->_blValid = (false !== reset($this->_aArray));
    }

    /**
     * Current for SPL.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->_aArray);
    }

    /**
     * Key for SPL.
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->_aArray);
    }

    /**
     * Previous / first array element.
     *
     * @return mixed
     */
    public function prev()
    {
        $oVar = prev($this->_aArray);
        if ($oVar === false) {
            // the first element, reset pointer
            $oVar = reset($this->_aArray);
        }
        $this->_blRemovedActive = false;

        return $oVar;
    }

    /**
     * Next for SPL.
     */
    public function next()
    {
        if ($this->_blRemovedActive === true && current($this->_aArray)) {
            $oVar = $this->prev();
        } else {
            $oVar = next($this->_aArray);
        }

        $this->_blValid = (false !== $oVar);
    }

    /**
     * Valid for SPL.
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->_blValid;
    }

    /**
     * Count for SPL.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_aArray);
    }

    /**
     * Clears/destroys list contents.
     */
    public function clear()
    {
        $this->_aArray = array();
    }

    /**
     * copies a given array over the objects internal array (something like old $myList->aList = $aArray).
     *
     * @param array $aArray array of list items
     */
    public function setArray($aArray)
    {
        $this->_aArray = $aArray;
    }

    /**
     * Returns the array reversed, the internal array remains untouched.
     *
     * @return array
     */
    public function reverse()
    {
        return array_reverse($this->_aArray);
    }

    /**
     * -----------------------------------------------------------------------------------------------------
     * SPL implementation end
     * -----------------------------------------------------------------------------------------------------
     */

    /**
     * Backward compatibility method.
     *
     * @param string $sName Variable name
     *
     * @return mixed
     */
    public function __get($sName)
    {
        return $this->_aArray;
    }

    /**
     * Returns list items array.
     *
     * @return array
     */
    public function getArray()
    {
        return $this->_aArray;
    }
}
