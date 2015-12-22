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
 * Abstract model db gateway class.
 */
abstract class oePayPalModelDbGateway
{
    /**
     * Returns data base resource.
     *
     * @return oxDb
     */
    protected function _getDb()
    {
        return oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
    }

    /**
     * Abstract method for data saving (insert and update).
     *
     * @param array $aData model data
     */
    abstract public function save($aData);

    /**
     * Abstract method for loading model data.
     *
     * @param string $sId model id
     */
    abstract public function load($sId);

    /**
     * Abstract method for delete model data.
     *
     * @param string $sId model id
     */
    abstract public function delete($sId);
}
