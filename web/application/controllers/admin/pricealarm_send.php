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
 * pricealarm sending manager.
 * Performs sending of pricealarm to selected iAllCnt groups.
 */
class PriceAlarm_Send extends oxAdminList
{

    /**
     * Default tab number
     *
     * @var int
     */
    protected $_iDefEdit = 1;

    /**
     * Executes parent method parent::render(), creates oxpricealarm object,
     * sends pricealarm to iAllCnts of chosen groups and returns name of template
     * file "pricealarm_send.tpl"/"pricealarm_done.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $myConfig = $this->getConfig();
        $oDB = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        ini_set("session.gc_maxlifetime", 36000);

        $iStart = oxRegistry::getConfig()->getRequestParameter("iStart");
        $iAllCnt = oxRegistry::getConfig()->getRequestParameter("iAllCnt");
        // #1140 R
        $sSelect = "select oxpricealarm.oxid, oxpricealarm.oxemail, oxpricealarm.oxartid, oxpricealarm.oxprice " .
                   "from oxpricealarm, oxarticles where oxarticles.oxid = oxpricealarm.oxartid " .
                   "and oxpricealarm.oxsended = '0000-00-00 00:00:00'";
        if (isset($iStart)) {
            $rs = $oDB->SelectLimit($sSelect, $myConfig->getConfigParam('iCntofMails'), $iStart);
        } else {
            $rs = $oDB->Execute($sSelect);
        }

        $iAllCntTmp = 0;

        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $oArticle = oxNew("oxarticle");
                $oArticle->load($rs->fields['oxid']);
                if ($oArticle->getPrice()->getBruttoPrice() <= $rs->fields['oxprice']) {
                    $this->sendeMail(
                        $rs->fields['oxemail'],
                        $rs->fields['oxartid'],
                        $rs->fields['oxid'],
                        $rs->fields['oxprice']
                    );
                    $iAllCntTmp++;
                }
                $rs->moveNext();
            }
        }
        if (!isset($iStart)) {
            // first call
            $iStart = 0;
            $iAllCnt = $iAllCntTmp;
        }


        // adavance mail pointer and set parameter
        $iStart += $myConfig->getConfigParam('iCntofMails');

        $this->_aViewData["iStart"] = $iStart;
        $this->_aViewData["iAllCnt"] = $iAllCnt;
        $this->_aViewData["actlang"] = oxRegistry::getLang()->getBaseLanguage();

        // end ?
        if ($iStart < $iAllCnt) {
            $sPage = "pricealarm_send.tpl";
        } else {
            $sPage = "pricealarm_done.tpl";
        }

        return $sPage;
    }

    /**
     * Overrides parent method to pass referred id
     *
     * @param string $sId class name
     */
    protected function _setupNavigation($sId)
    {
        parent::_setupNavigation('pricealarm_list');
    }

    /**
     * creates and sends email with pricealarm information
     *
     * @param string $sEMail        email address
     * @param string $sProductID    product id
     * @param string $sPricealarmID price alarm id
     * @param string $sBidPrice     bidded price
     */
    public function sendeMail($sEMail, $sProductID, $sPricealarmID, $sBidPrice)
    {
        $myConfig = $this->getConfig();
        $oAlarm = oxNew("oxpricealarm");
        $oAlarm->load($sPricealarmID);

        $oLang = oxRegistry::getLang();
        $iLang = (int) $oAlarm->oxpricealarm__oxlang->value;

        $iOldLangId = $oLang->getTplLanguage();
        $oLang->setTplLanguage($iLang);

        $oEmail = oxNew('oxemail');
        $blSuccess = (int) $oEmail->sendPricealarmToCustomer($sEMail, $oAlarm);

        $oLang->setTplLanguage($iOldLangId);

        if ($blSuccess) {
            $oAlarm->oxpricealarm__oxsended = new oxField(date("Y-m-d H:i:s"));
            $oAlarm->save();
        }

    }
}
