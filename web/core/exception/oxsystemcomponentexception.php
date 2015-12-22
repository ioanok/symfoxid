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
 * exceptions for missing components e.g.:
 * - missing class
 * - missing function
 * - missing template
 * - missing field in object
 */
class oxSystemComponentException extends oxException
{

    /**
     * Component causing the exception.
     *
     * @var string
     */
    private $_sComponent;

    /**
     * Sets the component name which caused the exception as a string.
     *
     * @param string $sComponent name of component
     */
    public function setComponent($sComponent)
    {
        $this->_sComponent = $sComponent;
    }

    /**
     * Name of the component that caused the exception
     *
     * @return string
     */
    public function getComponent()
    {
        return $this->_sComponent;
    }

    /**
     * Get string dump
     * Overrides oxException::getString()
     *
     * @return string
     */
    public function getString()
    {
        return __CLASS__ . '-' . parent::getString() . " Faulty component --> " . $this->_sComponent;
    }

    /**
     * Creates an array of field name => field value of the object.
     * To make a easy conversion of exceptions to error messages possible.
     * Should be extended when additional fields are used!
     * Overrides oxException::getValues().
     *
     * @return array
     */
    public function getValues()
    {
        $aRes = parent::getValues();
        $aRes['component'] = $this->getComponent();

        return $aRes;
    }
}
