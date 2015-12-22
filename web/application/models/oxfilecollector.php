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
 * Directory reader.
 * Performs reading of file list of one shop directory
 *
 */

class oxFileCollector
{

    /**
     * base directory
     *
     * @var string
     */
    protected $_sBaseDirectory;

    /**
     * array of collected files
     *
     * @var array
     */
    protected $_aFiles;

    /**
     * Setter for working directory
     *
     * @param string $sDir Directory
     */
    public function setBaseDirectory($sDir)
    {
        if (!empty($sDir)) {
            $this->_sBaseDirectory = $sDir;
        }
    }

    /**
     * get collection files
     *
     * @return mixed
     */
    public function getFiles()
    {
        return $this->_aFiles;
    }

    /**
     * Add one file to collection if it exists
     *
     * @param string $sFile file name to add to collection
     *
     * @throws Exception
     * @return null
     */
    public function addFile($sFile)
    {
        if (empty($sFile)) {
            throw new Exception('Parameter $sFile is empty!');
        }

        if (empty($this->_sBaseDirectory)) {
            throw new Exception('Base directory is not set, please use setter setBaseDirectory!');
        }

        if (is_file($this->_sBaseDirectory . $sFile)) {

            $this->_aFiles[] = $sFile;

            return true;
        }

        return false;
    }


    /**
     * browse all folders and sub-folders after files which have given extensions
     *
     * @param string  $sFolder     which is explored
     * @param array   $aExtensions list of extensions to scan - if empty all files are taken
     * @param boolean $blRecursive should directories be checked in recursive manner
     *
     * @throws exception
     * @return null
     */
    public function addDirectoryFiles($sFolder, $aExtensions = array(), $blRecursive = false)
    {
        if (empty($sFolder)) {
            throw new Exception('Parameter $sFolder is empty!');
        }

        if (empty($this->_sBaseDirectory)) {
            throw new Exception('Base directory is not set, please use setter setBaseDirectory!');
        }

        $aCurrentList = array();

        if (!is_dir($this->_sBaseDirectory . $sFolder)) {
            return;
        }

        $handle = opendir($this->_sBaseDirectory . $sFolder);

        while ($sFile = readdir($handle)) {

            if ($sFile != "." && $sFile != "..") {
                if (is_dir($this->_sBaseDirectory . $sFolder . $sFile)) {
                    if ($blRecursive) {
                        $aResultList = $this->addDirectoryFiles($sFolder . $sFile . '/', $aExtensions, $blRecursive);

                        if (is_array($aResultList)) {
                            $aCurrentList = array_merge($aCurrentList, $aResultList);
                        }
                    }
                } else {
                    $sExt = substr(strrchr($sFile, '.'), 1);

                    if ((!empty($aExtensions) && is_array($aExtensions) && in_array($sExt, $aExtensions)) ||
                        (empty($aExtensions))
                    ) {

                        $this->addFile($sFolder . $sFile);
                    }
                }
            }
        }
        closedir($handle);
    }
}
