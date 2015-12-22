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
 * Variant selection lists manager class
 *
 */
class oxVariantSelectList implements oxISelectList
{

    /**
     * Variant selection list label
     *
     * @var string
     */
    protected $_sLabel = null;

    /**
     * Selection list index
     *
     * @var int
     */
    protected $_iIndex = 0;

    /**
     * List with selections
     *
     * @var array
     */
    protected $_aList = array();

    /**
     * Active variant selection object
     *
     * @var oxSelection
     */
    protected $_oActiveSelection = null;

    /**
     * Builds current selection list
     *
     * @param string $sLabel list label
     * @param int    $iIndex list index
     *
     * @return null
     */
    public function __construct($sLabel, $iIndex)
    {
        $this->_sLabel = trim($sLabel);
        $this->_iIndex = $iIndex;
    }

    /**
     * Returns variant selection list label
     *
     * @return string
     */
    public function getLabel()
    {
        return getStr()->htmlspecialchars($this->_sLabel);
    }

    /**
     * Adds given variant info to current variant selection list
     *
     * @param string $sName      selection name
     * @param string $sValue     selection value
     * @param string $blDisabled selection state - disabled/enabled
     * @param string $blActive   selection state - active/inactive
     */
    public function addVariant($sName, $sValue, $blDisabled, $blActive)
    {
        if (($sName = trim($sName))) {
            $sKey = $sValue;

            // creating new
            if (!isset($this->_aList[$sKey])) {
                $this->_aList[$sKey] = oxNew("oxSelection", $sName, $sValue, $blDisabled, $blActive);
            } else {

                // overriding states
                if ($this->_aList[$sKey]->isDisabled() && !$blDisabled) {
                    $this->_aList[$sKey]->setDisabled($blDisabled);
                }

                if (!$this->_aList[$sKey]->isActive() && $blActive) {
                    $this->_aList[$sKey]->setActiveState($blActive);
                }
            }

            // storing active selection
            if ($this->_aList[$sKey]->isActive()) {
                $this->_oActiveSelection = $this->_aList[$sKey];
            }
        }
    }

    /**
     * Returns active selection object
     *
     * @return oxSelection
     */
    public function getActiveSelection()
    {
        return $this->_oActiveSelection;
    }

    /**
     * Returns array of oxSelection's
     *
     * @return array
     */
    public function getSelections()
    {
        return $this->_aList;
    }
}
