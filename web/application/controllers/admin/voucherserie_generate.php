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
 * Voucher Serie generator class
 *
 */
class VoucherSerie_Generate extends VoucherSerie_Main
{

    /**
     * Voucher generator class name
     *
     * @var string
     */
    public $sClassDo = "voucherserie_generate";

    /**
     * Number of vouchers to generate per tick
     *
     * @var int
     */
    public $iGeneratePerTick = 100;

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = "voucherserie_generate.tpl";

    /**
     * Voucher serie object
     *
     * @var oxvoucherserie
     */
    protected $_oVoucherSerie = null;

    /**
     * Generated vouchers count
     *
     * @var int
     */
    protected $_iGenerated = false;

    /**
     * Generates vouchers by offset iCnt
     *
     * @param integer $iCnt voucher offset
     *
     * @return bool
     */
    public function nextTick($iCnt)
    {
        if ($iGeneratedItems = $this->generateVoucher($iCnt)) {
            return $iGeneratedItems;
        }

        return false;
    }

    /**
     * Generates and saves vouchers. Returns number of saved records
     *
     * @param int $iCnt voucher counter offset
     *
     * @return int saved record count
     */
    public function generateVoucher($iCnt)
    {
        $iAmount = abs((int) oxRegistry::getSession()->getVariable("voucherAmount"));

        // creating new vouchers
        if ($iCnt < $iAmount && ($oVoucherSerie = $this->_getVoucherSerie())) {

            if (!$this->_iGenerated) {
                $this->_iGenerated = $iCnt;
            }

            $blRandomNr = ( bool ) oxRegistry::getSession()->getVariable("randomVoucherNr");
            $sVoucherNr = $blRandomNr ? oxUtilsObject::getInstance()->generateUID() : oxRegistry::getSession()->getVariable("voucherNr");

            $oNewVoucher = oxNew("oxvoucher");
            $oNewVoucher->oxvouchers__oxvoucherserieid = new oxField($oVoucherSerie->getId());
            $oNewVoucher->oxvouchers__oxvouchernr = new oxField($sVoucherNr);
            $oNewVoucher->save();

            $this->_iGenerated++;
        }

        return $this->_iGenerated;
    }

    /**
     * Runs voucher generation
     */
    public function run()
    {
        $blContinue = true;
        $iExportedItems = 0;

        // file is open
        $iStart = oxRegistry::getConfig()->getRequestParameter("iStart");

        for ($i = $iStart; $i < $iStart + $this->iGeneratePerTick; $i++) {
            if (($iExportedItems = $this->nextTick($i)) === false) {
                // end reached
                $this->stop(ERR_SUCCESS);
                $blContinue = false;
                break;
            }
        }

        if ($blContinue) {
            // make ticker continue
            $this->_aViewData['refresh'] = 0;
            $this->_aViewData['iStart'] = $i;
            $this->_aViewData['iExpItems'] = $iExportedItems;
        }
    }
}
