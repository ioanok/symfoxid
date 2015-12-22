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
 * Abstract model class
 */
abstract class oePayPalModel
{
    /**
     * Data base gateway.
     *
     * @var oePayPalPayPalDbGateway
     */
    protected $_oDbGateway = null;

    /**
     * Model data.
     *
     * @var array
     */
    protected $_aData = null;

    /**
     * Was object information found in database.
     *
     * @var bool
     */
    protected $_blIsLoaded = false;

    /**
     * Set response data.
     *
     * @param array $aData model data
     */
    public function setData($aData)
    {
        $aData = array_change_key_case($aData, CASE_LOWER);
        $this->_aData = $aData;
    }

    /**
     * Return response data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->_aData;
    }

    /**
     * Return value from data by given key.
     *
     * @param string $sKey key of data value
     *
     * @return string
     */
    protected function _getValue($sKey)
    {
        $aData = $this->getData();

        return $aData[$sKey];
    }

    /**
     * Return value from data by given key.
     *
     * @param string $sKey   key of data value
     * @param string $sValue data value
     */
    protected function _setValue($sKey, $sValue)
    {
        $this->_aData[$sKey] = $sValue;
    }

    /**
     * Returns model database gateway.
     *
     * @var $oDbGateway
     */
    abstract protected function _getDbGateway();

    /**
     * Set model database gateway.
     *
     * @param oePayPalPayPalDbGateway $oDbGateway
     */
    protected function _setDbGateway($oDbGateway)
    {
        $this->_oDbGateway = $oDbGateway;
    }

    /**
     * Method for model saving (insert and update data).
     *
     * @return int|false
     */
    public function save()
    {
        $mId = $this->_getDbGateway()->save($this->getData());
        $this->setId($mId);

        return $mId;
    }

    /**
     * Delete model data from db.
     *
     * @param string $sId model id
     *
     * @return bool
     */
    public function delete($sId = null)
    {
        if (!is_null($sId)) {
            $this->setId($sId);
        }

        return $this->_getDbGateway()->delete($this->getId());
    }

    /**
     * Method for loading model, if loaded returns true.
     *
     * @param string $sId model id
     *
     * @return bool
     */
    public function load($sId = null)
    {
        if (!is_null($sId)) {
            $this->setId($sId);
        }

        $this->_blIsLoaded = false;
        $aData = $this->_getDbGateway()->load($this->getId());
        if ($aData) {
            $this->setData($aData);
            $this->_blIsLoaded = true;
        }

        return $this->isLoaded();
    }

    /**
     * Returns whether object information found in database.
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->_blIsLoaded;
    }

    /**
     * Abstract method for delete model.
     *
     * @param string $sId model id
     */
    abstract public function setId($sId);

    /**
     * Abstract method for getting id.
     */
    abstract public function getId();
}
