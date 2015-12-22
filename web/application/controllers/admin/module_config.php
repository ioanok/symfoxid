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
 * Admin article main deliveryset manager.
 * There is possibility to change deliveryset name, article, user
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling -> Main Sets.
 */
class Module_Config extends Shop_Config
{

    protected $_sModule = 'shop_config.tpl';

    /**
     * Add additional config type for modules.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_aConfParams['password'] = 'confpassword';
    }

    /**
     * Executes parent method parent::render(), creates deliveryset category tree,
     * passes data to Smarty engine and returns name of template file "deliveryset_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        $sModuleId = $this->_sModuleId = $this->getEditObjectId();
        $sShopId = $myConfig->getShopId();

        $oModule = oxNew('oxModule');

        if ($sModuleId && $oModule->load($sModuleId)) {
            try {
                $aDbVariables = $this->_loadMetadataConfVars($oModule->getInfo("settings"));

                $this->_aViewData["var_constraints"] = $aDbVariables['constraints'];
                $this->_aViewData["var_grouping"] = $aDbVariables['grouping'];
                $iCount = 0;
                foreach ($this->_aConfParams as $sType => $sParam) {
                    $this->_aViewData[$sParam] = $aDbVariables['vars'][$sType];
                    $iCount += count($aDbVariables['vars'][$sType]);
                }
            } catch (oxException $oEx) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
                $oEx->debugOut();
            }
        } else {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay(new oxException('EXCEPTION_MODULE_NOT_LOADED'));
        }

        $this->_aViewData["oModule"] = $oModule;

        return 'module_config.tpl';
    }

    /**
     * return module filter for config variables
     *
     * @return string
     */
    protected function _getModuleForConfigVars()
    {
        return oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
    }

    /**
     * Load and parse config vars from metadata.
     * Return value is a map:
     *      'vars'        => config variable values as array[type][name] = value
     *      'constraints' => constraints list as array[name] = constraint
     *      'grouping'    => grouping info as array[name] = grouping
     *
     * @param array $aModuleSettings settings array from module metadata
     *
     * @return array
     */
    public function _loadMetadataConfVars($aModuleSettings)
    {
        $oConfig = $this->getConfig();

        $aConfVars = array(
            "bool"     => array(),
            "str"      => array(),
            "arr"      => array(),
            "aarr"     => array(),
            "select"   => array(),
            "password" => array(),
        );
        $aVarConstraints = array();
        $aGrouping = array();

        $aDbVariables = $this->loadConfVars($oConfig->getShopId(), $this->_getModuleForConfigVars());

        if (is_array($aModuleSettings)) {

            foreach ($aModuleSettings as $aValue) {
                $sName = $aValue["name"];
                $sType = $aValue["type"];
                $sValue = null;
                if (is_null($oConfig->getConfigParam($sName))) {
                    switch ($aValue["type"]) {
                        case "arr":
                            $sValue = $this->_arrayToMultiline($aValue["value"]);
                            break;
                        case "aarr":
                            $sValue = $this->_aarrayToMultiline($aValue["value"]);
                            break;
                        case "bool":
                            $sValue = filter_var($aValue["value"], FILTER_VALIDATE_BOOLEAN);
                            break;
                        default:
                            $sValue = $aValue["value"];
                            break;
                    }
                    $sValue = getStr()->htmlentities($sValue);
                } else {
                    $sDbType = $this->_getDbConfigTypeName($sType);
                    $sValue = $aDbVariables['vars'][$sDbType][$sName];
                }

                $sGroup = $aValue["group"];

                $sConstraints = "";
                if ($aValue["constraints"]) {
                    $sConstraints = $aValue["constraints"];
                } elseif ($aValue["constrains"]) {
                    $sConstraints = $aValue["constrains"];
                }

                $aConfVars[$sType][$sName] = $sValue;
                $aVarConstraints[$sName] = $this->_parseConstraint($sType, $sConstraints);
                if ($sGroup) {
                    if (!isset($aGrouping[$sGroup])) {
                        $aGrouping[$sGroup] = array($sName => $sType);
                    } else {
                        $aGrouping[$sGroup][$sName] = $sType;
                    }
                }
            }
        }

        return array(
            'vars'        => $aConfVars,
            'constraints' => $aVarConstraints,
            'grouping'    => $aGrouping,
        );
    }


    /**
     * Saves shop configuration variables
     */
    public function saveConfVars()
    {
        $oConfig = $this->getConfig();

        $this->resetContentCache();

        $sModuleId = $this->_sModuleId = $this->getEditObjectId();
        $sShopId = $oConfig->getShopId();

        $sModuleId = $this->_getModuleForConfigVars();

        foreach ($this->_aConfParams as $sType => $sParam) {
            $aConfVars = $oConfig->getRequestParameter($sParam);
            if (is_array($aConfVars)) {
                foreach ($aConfVars as $sName => $sValue) {
                    $sDbType = $this->_getDbConfigTypeName($sType);
                    $oConfig->saveShopConfVar(
                        $sDbType,
                        $sName,
                        $this->_serializeConfVar($sDbType, $sName, $sValue),
                        $sShopId,
                        $sModuleId
                    );
                }
            }
        }
    }

    /**
     * Convert metadata type to DB type.
     *
     * @param string $sType Metadata type.
     *
     * @return string
     */
    private function _getDbConfigTypeName($sType)
    {
        $sDbType = $sType;
        if ($sType === 'password') {
            $sDbType = 'str';
        }

        return $sDbType;
    }

}
