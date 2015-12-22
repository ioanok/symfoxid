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
 * Admin article main newsletter manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Customer News -> Newsletter -> Main.
 */
class Newsletter_Main extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxnewsletter object
     * and passes it's data to Smarty engine. Returns name of template file
     * "newsletter_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oNewsletter = oxNew("oxnewsletter");
            $oNewsletter->load($soxId);
            $this->_aViewData["edit"] = $oNewsletter;
        }

        // generate editor
        $this->_aViewData["editor"] = $this->_generateTextEditor(
            "100%",
            255,
            $oNewsletter,
            "oxnewsletter__oxtemplate"
        );

        return "newsletter_main.tpl";
    }

    /**
     * Saves newsletter HTML format text.
     */
    public function save()
    {
        $myConfig = $this->getConfig();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // shopid
        $sShopID = oxRegistry::getSession()->getVariable("actshop");
        $aParams['oxnewsletter__oxshopid'] = $sShopID;

        $oNewsletter = oxNew("oxnewsletter");
        if ($soxId != "-1") {
            $oNewsletter->load($soxId);
        } else {
            $aParams['oxnewsletter__oxid'] = null;
        }

        $oNewsletter->assign($aParams);
        $oNewsletter->save();

        // set oxid if inserted
        $this->setEditObjectId($oNewsletter->getId());
    }
}
