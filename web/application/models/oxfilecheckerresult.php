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
 * File checker result class
 * Structures and keeps the result of shop file check diagnostics
 *
 */

class oxFileCheckerResult
{

    /**
     * For result output
     *
     * @var mixed
     */
    protected $_aResult = array();

    /**
     * Counts number of matches for each type of result
     *
     * @var array
     */
    protected $_aResultSummary = array();

    /**
     * If the variable is true, the script will show all files, even they are ok.
     *
     * @var bool
     */
    protected $_blListAllFiles = false;

    /**
     * Object constructor
     */
    public function __construct()
    {
        $this->_aResultSummary['OK'] = 0;
        $this->_aResultSummary['VERSIONMISMATCH'] = 0;
        $this->_aResultSummary['UNKNOWN'] = 0;
        $this->_aResultSummary['MODIFIED'] = 0;
        $this->_aResultSummary['FILES'] = 0;
        $this->_aResultSummary['SHOP_OK'] = true;
    }

    /**
     * Setter for working directory
     *
     * @param boolean $blListAllFiles Whether to list all files
     */
    public function setListAllFiles($blListAllFiles)
    {
        $this->_blListAllFiles = $blListAllFiles;
    }

    /**
     * working directory getter
     *
     * @return boolean
     */
    public function getListAllFiles()
    {
        return $this->_blListAllFiles;
    }

    /**
     * Getter for file checker result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->_aResult;
    }

    /**
     * Getter for file checker result summary
     *
     * @return array
     */
    public function getResultSummary()
    {
        return $this->_aResultSummary;
    }

    /**
     * Methods saves result of one file check and returns updated summary array
     *
     * @param array $aResult Result
     *
     * @return array
     */
    public function addResult($aResult)
    {
        $this->_aResultSummary['FILES']++;
        $this->_aResultSummary[$aResult['result']]++;

        if (!$aResult['ok']) {
            $this->_aResultSummary['SHOP_OK'] = false;
        }

        if (($aResult['ok'] && $this->getListAllFiles()) || !$aResult['ok']) {
            $this->_aResult[] = $aResult;
        }

        return $this->_aResultSummary;
    }
}
