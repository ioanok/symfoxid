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
 * Admin article main pricealarm manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Customer News -> pricealarm -> Main.
 */
class PriceAlarm_Mail extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxpricealarm object
     * and passes it's data to Smarty engine. Returns name of template file
     * "pricealarm_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();
        // #889C - Netto prices in Admin
        $sIndex = "";
        if ($myConfig->getConfigParam('blEnterNetPrice')) {
            $sIndex = " * " . (1 + $myConfig->getConfigParam('dDefaultVAT') / 100);
        }

        $sShopID = $myConfig->getShopId();
        //articles price in subshop and baseshop can be different
        $this->_aViewData['iAllCnt'] = 0;
        $sQ = "select oxprice, oxartid from oxpricealarm " .
              "where oxsended = '000-00-00 00:00:00' and oxshopid = '$sShopID' ";
        $rs = oxDb::getDb()->execute($sQ);
        if ($rs != false && $rs->recordCount() > 0) {
            $aSimpleCache = array();
            while (!$rs->EOF) {
                $sPrice = $rs->fields[0];
                $sArtID = $rs->fields[1];
                if (isset($aSimpleCache[$sArtID])) {
                    if ($aSimpleCache[$sArtID] <= $sPrice) {
                        $this->_aViewData['iAllCnt'] += 1;
                    }
                } else {
                    $oArticle = oxNew("oxarticle");
                    if ($oArticle->load($sArtID)) {
                        $dArtPrice = $aSimpleCache[$sArtID] = $oArticle->getPrice()->getBruttoPrice();
                        if ($dArtPrice <= $sPrice) {
                            $this->_aViewData['iAllCnt'] += 1;
                        }
                    }
                }
                $rs->moveNext();
            }
        }

        return "pricealarm_mail.tpl";
    }
}
