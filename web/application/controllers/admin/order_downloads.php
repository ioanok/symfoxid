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
 * Admin order article manager.
 * Collects order articles information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders -> Articles.
 */
class Order_Downloads extends oxAdminDetails
{

    /**
     * Active order object
     *
     * @var oxorder
     */
    protected $_oEditObject = null;

    /**
     * Executes parent method parent::render(), passes data
     * to Smarty engine, returns name of template file "order_downloads.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        if ($oOrder = $this->getEditObject()) {
            $this->_aViewData["edit"] = $oOrder;
        }

        return "order_downloads.tpl";
    }

    /**
     * Returns editable order object
     *
     * @return oxorder
     */
    public function getEditObject()
    {
        $soxId = $this->getEditObjectId();
        if ($this->_oEditObject === null && isset($soxId) && $soxId != "-1") {
            $this->_oEditObject = oxNew("oxOrderFileList");
            $this->_oEditObject->loadOrderFiles($soxId);
        }

        return $this->_oEditObject;
    }

    /**
     * Returns editable order object
     */
    public function resetDownloadLink()
    {
        $sOrderFileId = oxRegistry::getConfig()->getRequestParameter('oxorderfileid');
        $oOrderFile = oxNew("oxorderfile");
        if ($oOrderFile->load($sOrderFileId)) {
            $oOrderFile->reset();
            $oOrderFile->save();
        }
    }
}
