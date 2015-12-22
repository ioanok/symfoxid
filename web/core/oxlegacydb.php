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
 * Database connection class
 */
class oxLegacyDb extends oxSuperCfg
{

    /**
     * Database connection object
     *
     * @var object
     */
    protected $_oDb = null;


    /**
     * Set connection
     *
     * @param object $oConnection Connection
     */
    public function setConnection($oConnection)
    {
        $this->_oDb = $oConnection;
    }

    /**
     * Set fetch mode to connection
     *
     * @param int $iFetchMode Fetch mode
     */
    public function setFetchMode($iFetchMode)
    {
        $this->_oDb->setFetchMode($iFetchMode);

    }

    /**
     * Return connection to db
     *
     * @param bool $blType - connection type
     *
     * @return object
     */
    public function getDb($blType = true)
    {

        return $this->_oDb;
    }

    /**
     * Get value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return string
     */
    public function getOne($sSql, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->getOne($sSql, $aParams);
    }

    /**
     * Get value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return array
     */
    public function getArray($sSql, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->getArray($sSql, $aParams);
    }

    /**
     * Get value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return array
     */
    public function getRow($sSql, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->getRow($sSql, $aParams);
    }

    /**
     * Get value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return array
     */
    public function getAll($sSql, $aParams = false, $blType = true)
    {

        return $this->getDb($blType)->getAll($sSql, $aParams);
    }

    /**
     * Get value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return object
     */
    public function select($sSql, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->execute($sSql, $aParams);
    }

    /**
     * Get value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return array
     */
    public function getAssoc($sSql, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->getAssoc($sSql, $aParams);
    }

    /**
     * Get column value
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return object
     */
    public function getCol($sSql, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->getCol($sSql, $aParams);
    }

    /**
     * Get array
     *
     * @param string     $sSql    Query
     * @param int        $iRows   Rows
     * @param int        $iOffset Offset
     * @param array|bool $aParams Array of parameters
     * @param bool       $blType  connection type
     *
     * @return object
     */
    public function selectLimit($sSql, $iRows = -1, $iOffset = -1, $aParams = false, $blType = true)
    {
        return $this->getDb($blType)->SelectLimit($sSql, $iRows, $iOffset, $aParams);
    }

    /**
     * Execute query
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     *
     * @return object
     */
    public function execute($sSql, $aParams = false)
    {
        return $this->getDb(false)->execute($sSql, $aParams);
    }

    /**
     * Execute query
     *
     * @param string     $sSql    Query
     * @param array|bool $aParams Array of parameters
     *
     * @return object
     */
    public function query($sSql, $aParams = false)
    {
        return $this->getDb(false)->Query($sSql, $aParams);
    }

    /**
     * Return count effected values
     *
     * @return int
     */
    public function Affected_Rows()
    {
        return $this->getDb(false)->Affected_Rows();
    }

    /**
     * Return error number
     *
     * @return int
     */
    public function errorNo()
    {
        return $this->getDb(false)->ErrorNo();
    }

    /**
     * Return error message
     *
     * @return string
     */
    public function errorMsg()
    {
        return $this->getDb(false)->ErrorMsg();
    }

    /**
     * Quote string
     *
     * @param string $sValue value
     *
     * @return string
     */
    public function qstr($sValue)
    {
        return $this->getDb(false)->qstr($sValue);
    }

    /**
     * Quote string
     *
     * @param string $sValue value
     *
     * @return string
     */
    public function quote($sValue)
    {
        return $this->getDb(false)->quote($sValue);
    }

    /**
     * Quotes an array.
     *
     * @param array $aStrArray array of strings to quote
     *
     * @return array
     */
    public function quoteArray($aStrArray)
    {
        foreach ($aStrArray as $sKey => $sString) {
            $aStrArray[$sKey] = $this->quote($sString);
        }

        return $aStrArray;
    }

    /**
     * return meta data
     *
     * @param string $sTable Table name
     *
     * @return array
     */
    public function metaColumns($sTable)
    {
        return $this->getDb(false)->MetaColumns($sTable);
    }

    /**
     * return meta data
     *
     * @param string $sTable       Table name
     * @param bool   $blNumIndexes Numeric indexes
     *
     * @return array
     */
    public function metaColumnNames($sTable, $blNumIndexes = false)
    {
        return $this->getDb(false)->MetaColumnNames($sTable, $blNumIndexes);
    }

    /**
     * Start mysql transaction
     *
     * @return bool
     */
    public function startTransaction()
    {
        return $this->getDb(false)->execute('START TRANSACTION');
    }

    /**
     * Commit mysql transaction
     *
     * @return bool
     */
    public function commitTransaction()
    {
        return $this->getDb(false)->execute('COMMIT');
    }

    /**
     * RollBack mysql transaction
     *
     * @return bool
     */
    public function rollbackTransaction()
    {
        return $this->getDb(false)->execute('ROLLBACK');
    }

    /**
     * Set transaction isolation level
     * Allowed values READ UNCOMMITTED, READ COMMITTED, REPEATABLE READ, SERIALIZABLE
     *
     * @param string $sLevel level
     *
     * @return bool
     */
    public function setTransactionIsolationLevel($sLevel = null)
    {
        $blResult = false;

        $aLevels = array('READ UNCOMMITTED', 'READ COMMITTED', 'REPEATABLE READ', 'SERIALIZABLE');
        if (in_array(strtoupper($sLevel), $aLevels)) {
            $blResult = $this->getDb(false)->execute('SET TRANSACTION ISOLATION LEVEL ' . $sLevel);
        }

        return $blResult;
    }

    /**
     * Calls Db UI method
     *
     * @param integer $iPollSecs poll seconds
     */
    public function UI($iPollSecs = 5)
    {
        $this->getDb(false)->UI($iPollSecs);
    }

    /**
     * Returns last insert ID
     *
     * @return int
     */
    public function Insert_ID()
    {
        return $this->getDb(false)->Insert_ID();
    }
}
