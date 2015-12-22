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
 * Exception class for a non existing language local
 */
class oxLanguageException extends oxException
{

    /**
     * Language constant
     *
     * @var string
     */
    private $_sLangConstant = "";

    /**
     * sets the language constant which is missing
     *
     * @param string $sLangConstant language constant
     */
    public function setLangConstant($sLangConstant)
    {
        $this->_sLangConstant = $sLangConstant;
    }

    /**
     * Get language constant
     *
     * @return string
     */
    public function getLangConstant()
    {
        return $this->_sLangConstant;
    }

    /**
     * Get string dump
     * Overrides oxException::getString()
     *
     * @return string
     */
    public function getString()
    {
        return __CLASS__ . '-' . parent::getString() . " Faulty Constant --> " . $this->_sLangConstant . "\n";
    }

    /**
     * Creates an array of field name => field value of the object
     * to make a easy conversion of exceptions to error messages possible
     * Overrides oxException::getValues()
     * should be extended when additional fields are used!
     *
     * @return array
     */
    public function getValues()
    {
        $aRes = parent::getValues();
        $aRes['langConstant'] = $this->getLangConstant();

        return $aRes;
    }
}
