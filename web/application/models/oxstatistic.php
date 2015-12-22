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
 * Statistics manager.
 */
class oxStatistic extends oxBase
{

    /**
     * @var string Name of current class
     */
    protected $_sClassName = 'oxstatistic';

    /**
     * Class constructor, initiates paren constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxstatistics');
    }

    /**
     * Sets reports array to current statistics object
     *
     * @param array $aVal array of reports to set in current statistics object
     */
    public function setReports($aVal)
    {
        $this->oxstatistics__oxvalue = new oxField(serialize($aVal), oxField::T_RAW);
    }

    /**
     * Returns array of reports assigned to current statistics object
     *
     * @return array
     */
    public function getReports()
    {
        return unserialize($this->oxstatistics__oxvalue->value);
    }

    /**
     * Sets data field value
     *
     * @param string $sFieldName index OR name (eg. 'oxarticles__oxtitle') of a data field to set
     * @param string $sValue     value of data field
     * @param int    $iDataType  field type
     *
     * @return null
     */
    protected function _setFieldData($sFieldName, $sValue, $iDataType = oxField::T_TEXT)
    {
        if ('oxvalue' === $sFieldName) {
            $iDataType = oxField::T_RAW;
        }

        return parent::_setFieldData($sFieldName, $sValue, $iDataType);
    }
}
