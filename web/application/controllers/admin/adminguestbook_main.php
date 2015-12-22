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
 * Guestbook record manager.
 * Returns template, that arranges guestbook record information.
 * Admin Menu: User information -> Guestbook -> Main.
 */
class Adminguestbook_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render() and returns template file
     * name "adminguestbook_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != '-1' && isset($soxId)) {
            // load object
            $oLinks = oxNew('oxgbentry');
            $oLinks->load($soxId);

            // #580A - setting GB entry as viewed in admin
            if (!isset($oLinks->oxgbentries__oxviewed) || !$oLinks->oxgbentries__oxviewed->value) {
                $oLinks->oxgbentries__oxviewed = new oxField(1);
                $oLinks->save();
            }
            $this->_aViewData["edit"] = $oLinks;
        }

        //show "active" checkbox if moderating is active
        $this->_aViewData['blShowActBox'] = $myConfig->getConfigParam('blGBModerate');

        return 'adminguestbook_main.tpl';
    }

    /**
     * Saves guestbook record changes.
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // checkbox handling
        if (!isset($aParams['oxgbentries__oxactive'])) {
            $aParams['oxgbentries__oxactive'] = 0;
        }

        // shopid
        $aParams['oxgbentries__oxshopid'] = oxRegistry::getSession()->getVariable("actshop");

        $oLinks = oxNew("oxgbentry");
        if ($soxId != "-1") {
            $oLinks->load($soxId);
        } else {
            $aParams['oxgbentries__oxid'] = null;

            // author
            $aParams['oxgbentries__oxuserid'] = oxRegistry::getSession()->getVariable('auth');
        }

        $oLinks->assign($aParams);
        $oLinks->save();
        $this->setEditObjectId($oLinks->getId());
    }
}
