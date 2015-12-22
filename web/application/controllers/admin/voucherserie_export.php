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
 * General export class.
 */
class VoucherSerie_Export extends VoucherSerie_Main
{

    /**
     * Export class name
     *
     * @var string
     */
    public $sClassDo = "voucherserie_export";

    /**
     * Export file extension
     *
     * @var string
     */
    public $sExportFileType = "csv";

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = "voucherserie_export.tpl";

    /**
     * Number of records to export per tick
     *
     * @var int
     */
    public $iExportPerTick = 1000;

    /**
     * Calls parent costructor and initializes $this->_sFilePath parameter
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();

        // export file name
        $this->sExportFileName = $this->_getExportFileName();

        // set generic frame template
        $this->_sFilePath = $this->_getExportFilePath();
    }

    /**
     * Returns export file download url
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        $myConfig = $this->getConfig();

        // override cause of admin dir
        $sUrl = $myConfig->getConfigParam('sShopURL') . $myConfig->getConfigParam('sAdminDir');
        if ($myConfig->getConfigParam('sAdminSSLURL')) {
            $sUrl = $myConfig->getConfigParam('sAdminSSLURL');
        }

        $sUrl = oxRegistry::get("oxUtilsUrl")->processUrl($sUrl . '/index.php');

        return $sUrl . '&amp;cl=' . $this->sClassDo . '&amp;fnc=download';
    }

    /**
     * Return export file name
     *
     * @return string
     */
    protected function _getExportFileName()
    {
        $sSessionFileName = oxRegistry::getSession()->getVariable("sExportFileName");
        if (!$sSessionFileName) {
            $sSessionFileName = md5($this->getSession()->getId() . oxUtilsObject::getInstance()->generateUId());
            oxRegistry::getSession()->setVariable("sExportFileName", $sSessionFileName);
        }

        return $sSessionFileName;
    }

    /**
     * Return export file path
     *
     * @return string
     */
    protected function _getExportFilePath()
    {
        return $this->getConfig()->getConfigParam('sShopDir') . "/export/" . $this->_getExportFileName();
    }

    /**
     * Performs Voucherserie export to export file.
     */
    public function download()
    {
        $oUtils = oxRegistry::getUtils();
        $oUtils->setHeader("Pragma: public");
        $oUtils->setHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        $oUtils->setHeader("Expires: 0");
        $oUtils->setHeader("Content-Disposition: attachment; filename=vouchers.csv");
        $oUtils->setHeader("Content-Type: application/csv");
        $sFile = $this->_getExportFilePath();
        if (file_exists($sFile) && is_readable($sFile)) {
            readfile($sFile);
        }
        $oUtils->showMessageAndExit("");
    }

    /**
     * Does Export
     */
    public function run()
    {
        $blContinue = true;
        $iExportedItems = 0;

        $this->fpFile = @fopen($this->_sFilePath, "a");
        if (!isset($this->fpFile) || !$this->fpFile) {
            // we do have an error !
            $this->stop(ERR_FILEIO);
        } else {
            // file is open
            $iStart = oxRegistry::getConfig()->getRequestParameter("iStart");
            if (!$iStart) {
                ftruncate($this->fpFile, 0);
            }

            if (($iExportedItems = $this->exportVouchers($iStart)) === false) {
                // end reached
                $this->stop(ERR_SUCCESS);
                $blContinue = false;
            }

            if ($blContinue) {
                // make ticker continue
                $this->_aViewData['refresh'] = 0;
                $this->_aViewData['iStart'] = $iStart + $iExportedItems;
                $this->_aViewData['iExpItems'] = $iStart + $iExportedItems;
            }
            fclose($this->fpFile);
        }
    }

    /**
     * Writes voucher number information to export file and returns number of written records info
     *
     * @param int $iStart start exporting from
     *
     * @return int
     */
    public function exportVouchers($iStart)
    {
        $iExported = false;

        if ($oSerie = $this->_getVoucherSerie()) {

            $oDb = oxDb::getDb(oxDB::FETCH_MODE_ASSOC);

            $sSelect = "select oxvouchernr from oxvouchers where oxvoucherserieid = " . $oDb->quote($oSerie->getId());
            $rs = $oDb->selectLimit($sSelect, $this->iExportPerTick, $iStart);

            if (!$rs->EOF) {
                $iExported = 0;

                // writing header text
                if ($iStart == 0) {
                    $this->write(oxRegistry::getLang()->translateString("VOUCHERSERIE_MAIN_VOUCHERSTATISTICS", oxRegistry::getLang()->getTplLanguage(), true));
                }
            }

            // writing vouchers..
            while (!$rs->EOF) {
                $this->write(current($rs->fields));
                $iExported++;
                $rs->moveNext();
            }
        }

        return $iExported;
    }

    /**
     * writes one line into open export file
     *
     * @param string $sLine exported line
     */
    public function write($sLine)
    {
        if ($sLine) {
            fwrite($this->fpFile, $sLine . "\n");
        }
    }
}
