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
 * Admin article main voucherserie manager.
 * There is possibility to change voucherserie name, description, valid terms
 * and etc.
 * Admin Menu: Shop Settings -> Vouchers -> Main.
 */
class VoucherSerie_Main extends DynExportBase
{

    /**
     * Export class name
     *
     * @var string
     */
    public $sClassDo = "voucherSerie_generate";

    /**
     * Voucher serie object
     *
     * @var oxvoucherserie
     */
    protected $_oVoucherSerie = null;

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = "voucherserie_main.tpl";

    /**
     * Executes parent method parent::render(), creates oxvoucherserie object,
     * passes data to Smarty engine and returns name of template file
     * "voucherserie_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oVoucherSerie = oxNew("oxvoucherserie");
            $oVoucherSerie->load($soxId);
            $this->_aViewData["edit"] = $oVoucherSerie;

        }

        return $this->_sThisTemplate;
    }

    /**
     * Saves main Voucherserie parameters changes.
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        // Parameter Processing
        $soxId = $this->getEditObjectId();
        $aSerieParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // Voucher Serie Processing
        $oVoucherSerie = oxNew("oxvoucherserie");
        // if serie already exist use it
        if ($soxId != "-1") {
            $oVoucherSerie->load($soxId);
        } else {
            $aSerieParams["oxvoucherseries__oxid"] = null;
        }



        $aSerieParams["oxvoucherseries__oxdiscount"] = abs($aSerieParams["oxvoucherseries__oxdiscount"]);

        $oVoucherSerie->assign($aSerieParams);
        $oVoucherSerie->save();

        // set oxid if inserted
        $this->setEditObjectId($oVoucherSerie->getId());
    }

    /**
     * Returns voucher status information array
     *
     * @return array
     */
    public function getStatus()
    {
        if ($oSerie = $this->_getVoucherSerie()) {
            return $oSerie->countVouchers();
        }
    }

    /**
     * Overriding parent function, doing nothing..
     */
    public function prepareExport()
    {
    }


    /**
     * Returns voucher serie object
     *
     * @return oxvoucherserie
     */
    protected function _getVoucherSerie()
    {
        if ($this->_oVoucherSerie == null) {
            $oVoucherSerie = oxNew("oxvoucherserie");
            $sId = oxRegistry::getConfig()->getRequestParameter("voucherid");
            if ($oVoucherSerie->load($sId ? $sId : oxRegistry::getSession()->getVariable("voucherid"))) {
                $this->_oVoucherSerie = $oVoucherSerie;
            }
        }

        return $this->_oVoucherSerie;
    }

    /**
     * Prepares Export
     */
    public function start()
    {
        $this->_aViewData['refresh'] = 0;
        $this->_aViewData['iStart'] = 0;
        $iEnd = $this->prepareExport();
        oxRegistry::getSession()->setVariable("iEnd", $iEnd);
        $this->_aViewData['iEnd'] = $iEnd;

        // saving export info
        oxRegistry::getSession()->setVariable("voucherid", oxRegistry::getConfig()->getRequestParameter("voucherid"));
        oxRegistry::getSession()->setVariable("voucherAmount", abs((int) oxRegistry::getConfig()->getRequestParameter("voucherAmount")));
        oxRegistry::getSession()->setVariable("randomVoucherNr", oxRegistry::getConfig()->getRequestParameter("randomVoucherNr"));
        oxRegistry::getSession()->setVariable("voucherNr", oxRegistry::getConfig()->getRequestParameter("voucherNr"));
    }

    /**
     * Current view ID getter helps to identify navigation position
     * fix for 0003701, passing dynexportbase::getViewId
     *
     * @return string
     */
    public function getViewId()
    {
        return oxAdminView::getViewId();
    }
}
