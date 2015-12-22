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
 * Class InvoicepdfOrder_Overview extends order_overview.
 */
class InvoicepdfOrder_Overview extends InvoicepdfOrder_Overview_parent
{

    /**
     * Add Languages to parameters.
     *
     * @return string
     */
    public function render()
    {
        $return = parent::render();

        $oLang = oxRegistry::getLang();
        $this->_aViewData["alangs"] = $oLang->getLanguageNames();

        return $return;
    }

    /**
     * Performs PDF export to user (outputs file to save).
     */
    public function createPDF()
    {
        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oOrder = oxNew("oxorder");
            if ($oOrder->load($soxId)) {
                $oUtils = oxRegistry::getUtils();
                $sTrimmedBillName = trim($oOrder->oxorder__oxbilllname->getRawValue());
                $sFilename = $oOrder->oxorder__oxordernr->value . "_" . $sTrimmedBillName . ".pdf";
                $sFilename = $this->makeValidFileName($sFilename);
                ob_start();
                $oOrder->genPDF($sFilename, oxRegistry::getConfig()->getRequestParameter("pdflanguage"));
                $sPDF = ob_get_contents();
                ob_end_clean();
                $oUtils->setHeader("Pragma: public");
                $oUtils->setHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                $oUtils->setHeader("Expires: 0");
                $oUtils->setHeader("Content-type: application/pdf");
                $oUtils->setHeader("Content-Disposition: attachment; filename=" . $sFilename);
                oxRegistry::getUtils()->showMessageAndExit($sPDF);
            }
        }
    }
}
