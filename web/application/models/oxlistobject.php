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
 * Simple list object
 */
class oxListObject
{

    /**
     * @var string
     */
    private $_sTableName = '';

    /**
     * Class constructor
     *
     * @param string $sTableName Table name
     */
    public function __construct($sTableName)
    {
        $this->_sTableName = $sTableName;
    }

    /**
     * Assigns database record to object
     *
     * @param object $aData Database record
     *
     * @return null
     */
    public function assign($aData)
    {
        if (!is_array($aData)) {
            return;
        }
        foreach ($aData as $sKey => $sValue) {
            $sFieldName = strtolower($this->_sTableName . '__' . $sKey);
            $this->$sFieldName = new oxField($sValue);
        }
    }

    /**
     * Returns object id
     *
     * @return int
     */
    public function getId()
    {
        $sFieldName = strtolower($this->_sTableName . '__oxid');
        return $this->$sFieldName->value;
    }
}
