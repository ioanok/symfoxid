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
class GenExport_Do extends DynExportBase
{

    /**
     * Export class name
     *
     * @var string
     */
    public $sClassDo = "genExport_do";

    /**
     * Export ui class name
     *
     * @var string
     */
    public $sClassMain = "genExport_main";

    /**
     * Export file name
     *
     * @var string
     */
    public $sExportFileName = "genexport";

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = "dynbase_do.tpl";

    /**
     * Does Export line by line on position iCnt
     *
     * @param integer $iCnt export position
     *
     * @return bool
     */
    public function nextTick($iCnt)
    {
        $iExportedItems = $iCnt;
        $blContinue = false;
        if ($oArticle = $this->getOneArticle($iCnt, $blContinue)) {
            $myConfig = oxRegistry::getConfig();
            $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
            $oSmarty->assign("sCustomHeader", oxRegistry::getSession()->getVariable("sExportCustomHeader"));
            $oSmarty->assign_by_ref("linenr", $iCnt);
            $oSmarty->assign_by_ref("article", $oArticle);
            $oSmarty->assign("spr", $myConfig->getConfigParam('sCSVSign'));
            $oSmarty->assign("encl", $myConfig->getConfigParam('sGiCsvFieldEncloser'));
            $this->write($oSmarty->fetch("genexport.tpl", $this->getViewId()));

            return ++$iExportedItems;
        }

        return $blContinue;
    }

    /**
     * writes one line into open export file
     *
     * @param string $sLine exported line
     */
    public function write($sLine)
    {
        $sLine = $this->removeSID($sLine);

        $sLine = str_replace(array("\r\n", "\n"), "", $sLine);
        $sLine = str_replace("<br>", "\n", $sLine);

        fwrite($this->fpFile, $sLine . "\r\n");
    }
}
