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
 * Wraps and provides getters for configuration constants stored in configuration file (usually config.inc.php).
 */
class OxConfigFile
{

    /**
     * Performs variable loading from configuration file by including the php file.
     * It works with current configuration file format well,
     * however in case the variable storage format is not satisfactory
     * this method is a subject to be changed.
     *
     * @param string $sFileName Configuration file name
     */
    private function _loadVars($sFileName)
    {
        include $sFileName;
    }

    /**
     * Initializes the instance. Loads config variables from the file.
     *
     * @param string $sFileName Configuration file name
     */
    public function __construct($sFileName)
    {
        $this->_loadVars($sFileName);
    }

    /**
     * Returns loaded variable value by name.
     *
     * @param string $sVarName Variable name
     *
     * @return mixed
     */
    public function getVar($sVarName)
    {
        if (isset ($this->$sVarName)) {
            return $this->$sVarName;
        }

        return null;
    }

    /**
     * Set config variable.
     *
     * @param string $sVarName Variable name
     * @param string $sValue   Variable value
     */
    public function setVar($sVarName, $sValue)
    {
        $this->$sVarName = $sValue;
    }

    /**
     * Checks by name if variable is set
     *
     * @param string $sVarName Variable name
     *
     * @return bool
     */
    public function isVarSet($sVarName)
    {
        return isset($this->$sVarName);
    }

    /**
     * Returns all loaded vars as an array
     *
     * @return array[string]mixed
     */
    public function getVars()
    {
        $aAllVars = get_object_vars($this);

        return $aAllVars;
    }

    /**
     * Sets custom config file to include
     *
     * @param string $sFileName custom configuration file name
     */
    public function setFile($sFileName)
    {
        if (is_readable($sFileName)) {
            $this->_loadVars($sFileName);
        }
    }
}
