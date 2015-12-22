<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.

 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 */

/**
 * Lists all non RDFa payments images.
 * Checks if image with name of payment id exists in theme's out/img folder and if so - displays it.
 */
class oeThemeSwitcherWPaymentList extends oxWidget
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/paymentlist.tpl';

    /**
     * Directory, where payment images are stored
     *
     * @var string
     */
    protected $_imageDir = 'payments';

    /**
     * Images type to check
     *
     * @var string
     */
    protected $_imageType = 'png';

    /**
     * Payment List, which images should be returned
     *
     * @var oxPaymentList
     */
    protected $_oPaymentList = null;

    /**
     * Returns all payment images
     *
     * @param int $iLang language id
     *
     * @return array
     */
    public function getPaymentList($iLang = null)
    {
        if ($iLang === null) {
            $iLang = oxRegistry::getLang()->getBaseLanguage();
        }
        if ($this->_aPaymentList[$iLang] === null) {
            $this->_aPaymentList[$iLang] = $this->_loadPaymentList($iLang);
        }

        return $this->_aPaymentList[$iLang];
    }

    /**
     * Loads payments from db and adds images if exists
     *
     * @param int $iLang language id
     *
     * @return array
     */
    protected function _loadPaymentList($iLang)
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $sTable = getViewName('oxpayments', $iLang);
        $aPayments = $oDb->getAll("SELECT $sTable.* FROM $sTable WHERE $sTable.OXACTIVE = 1");

        $aPaymentList = array();

        if (is_array($aPayments) && !empty($aPayments)) {
            foreach ($aPayments as $aPayment) {
                $sPaymentId = $aPayment["OXID"];
                $aPayment["OXPAYMENTIMAGE"] = $this->_getImageUrl($sPaymentId, $iLang);
                $aPaymentList[$sPaymentId] = $aPayment;
            }
        }

        return $aPaymentList;
    }

    /**
     * Returns image path based on given payment id
     *
     * @param string $sPaymentId payment abbr
     * @param int    $iLang      language id
     *
     * @return string
     */
    protected function _getImageUrl($sPaymentId, $iLang)
    {
        $sLangAbbr = oxRegistry::getLang()->getLanguageAbbr($iLang);

        $sImagePath = $this->_imageDir . '/' . $sLangAbbr . '/' . $sPaymentId . '.' . $this->_imageType;

        return $this->getConfig()->getImageUrl(false, null, null, $sImagePath);
    }
}
