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
 * Newsletter preview manager.
 * Creates plaintext and HTML format newsletter preview.
 * Admin Menu: Customer News -> Newsletter -> Preview.
 */
class Newsletter_Preview extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxnewsletter object
     * and passes it's data to Smarty engine, returns name of template file
     * "newsletter_preview.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oNewsletter = oxNew("oxnewsletter");
            $oNewsletter->load($soxId);
            $this->_aViewData["edit"] = $oNewsletter;

            // user
            $sUserID = oxRegistry::getSession()->getVariable("auth");

            // assign values to the newsletter and show it
            $oNewsletter->prepare($sUserID, $this->getConfig()->getConfigParam('bl_perfLoadAktion'));

            $this->_aViewData["previewhtml"] = $oNewsletter->getHtmlText();
            $this->_aViewData["previewtext"] = $oNewsletter->getPlainText();
        }

        return "newsletter_preview.tpl";
    }
}
