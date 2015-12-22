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
 * Admin user extended settings manager.
 * Collects user extended settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> Extended.
 */
class User_Extend extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxuser object and
     * returns name of template file "user_extend.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oUser = oxNew("oxuser");
            $oUser->load($soxId);

            //show country in active language
            $oCountry = oxNew("oxCountry");
            $oCountry->loadInLang(oxRegistry::getLang()->getObjectTplLanguage(), $oUser->oxuser__oxcountryid->value);
            $oUser->oxuser__oxcountry = new oxField($oCountry->oxcountry__oxtitle->value);

            $this->_aViewData["edit"] = $oUser;
        }

        if (!$this->_allowAdminEdit($soxId)) {
            $this->_aViewData['readonly'] = true;
        }

        return "user_extend.tpl";
    }

    /**
     * Saves user extended information.
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();

        if (!$this->_allowAdminEdit($soxId)) {
            return false;
        }

        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        $oUser = oxNew("oxuser");
        if ($soxId != "-1") {
            $oUser->load($soxId);
        } else {
            $aParams['oxuser__oxid'] = null;
        }

        // checkbox handling
        $aParams['oxuser__oxactive'] = $oUser->oxuser__oxactive->value;

        $blNewsParams = oxRegistry::getConfig()->getRequestParameter("editnews");
        if (isset($blNewsParams)) {
            $oNewsSubscription = $oUser->getNewsSubscription();
            $oNewsSubscription->setOptInStatus((int) $blNewsParams);
            $oNewsSubscription->setOptInEmailStatus((int) oxRegistry::getConfig()->getRequestParameter("emailfailed"));
        }

        $oUser->assign($aParams);
        $oUser->save();

        // set oxid if inserted
        $this->setEditObjectId($oUser->getId());
    }
}
