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
class oxRequiredFieldsValidator
{

    /**
     * Required fields array.
     *
     * @var array
     */
    private $_aRequiredFields = array();

    /**
     * Invalid fields array.
     *
     * @var array
     */
    private $_aInvalidFields = array();

    /**
     * Required Field validator.
     *
     * @var oxRequiredFieldValidator
     */
    private $_oFieldValidator = array();

    /**
     * Sets dependencies.
     *
     * @param oxRequiredFieldValidator $oFieldValidator
     */
    public function __construct($oFieldValidator = null)
    {
        if (is_null($oFieldValidator)) {
            $oFieldValidator = oxNew('oxRequiredFieldValidator');
        }
        $this->setFieldValidator($oFieldValidator);
    }

    /**
     * Returns required fields for address.
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->_aRequiredFields;
    }

    /**
     * Sets required fields array
     *
     * @param array $aFields Fields
     */
    public function setRequiredFields($aFields)
    {
        $this->_aRequiredFields = $aFields;
    }

    /**
     * Returns required fields for address.
     *
     * @return oxRequiredFieldValidator
     */
    public function getFieldValidator()
    {
        return $this->_oFieldValidator;
    }

    /**
     * Sets required fields array
     *
     * @param oxRequiredFieldValidator $oFieldValidator
     */
    public function setFieldValidator($oFieldValidator)
    {
        $this->_oFieldValidator = $oFieldValidator;
    }

    /**
     * Gets invalid fields.
     *
     * @return array
     */
    public function getInvalidFields()
    {
        return $this->_aInvalidFields;
    }

    /**
     * Checks if all required fields are filled.
     * Returns array of invalid fields or empty array if all fields are fine.
     *
     * @param oxBase $oObject Address fields with values.
     *
     * @return bool If any invalid field exist.
     */
    public function validateFields($oObject)
    {
        $aRequiredFields = $this->getRequiredFields();
        $oFieldValidator = $this->getFieldValidator();

        $aInvalidFields = array();
        foreach ($aRequiredFields as $sFieldName) {
            if (!$oFieldValidator->validateFieldValue($oObject->getFieldData($sFieldName))) {
                $aInvalidFields[] = $sFieldName;
            }
        }
        $this->_setInvalidFields($aInvalidFields);

        return empty($aInvalidFields);
    }

    /**
     * Add fields to invalid fields array.
     *
     * @param array $aFields Invalid field name.
     */
    private function _setInvalidFields($aFields)
    {
        $this->_aInvalidFields = $aFields;
    }
}
