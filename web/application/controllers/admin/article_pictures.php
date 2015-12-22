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
 * Admin article picture manager.
 * Collects information about article's used pictures, there is posibility to
 * upload any other picture, etc.
 * Admin Menu: Manage Products -> Articles -> Pictures.
 */
class Article_Pictures extends oxAdminDetails
{

    /**
     * Loads article information - pictures, passes data to Smarty
     * engine, returns name of template file "article_pictures.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData["edit"] = $oArticle = oxNew("oxarticle");

        $soxId = $this->getEditObjectId();
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oArticle->load($soxId);


            // variant handling
            if ($oArticle->oxarticles__oxparentid->value) {
                $oParentArticle = oxNew("oxarticle");
                $oParentArticle->load($oArticle->oxarticles__oxparentid->value);
                $this->_aViewData["parentarticle"] = $oParentArticle;
                $this->_aViewData["oxparentid"] = $oArticle->oxarticles__oxparentid->value;
            }
        }

        $this->_aViewData["iPicCount"] = $this->getConfig()->getConfigParam('iPicCount');

        return "article_pictures.tpl";
    }

    /**
     * Saves (uploads) pictures to server.
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig = $this->getConfig();

        if ($myConfig->isDemoShop()) {
            // disabling uploading pictures if this is demo shop
            $oEx = oxNew("oxExceptionToDisplay");
            $oEx->setMessage('ARTICLE_PICTURES_UPLOADISDISABLED');
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx, false);

            return;
        }

        parent::save();

        $oArticle = oxNew("oxarticle");
        if ($oArticle->load($this->getEditObjectId())) {
            $oArticle->assign(oxRegistry::getConfig()->getRequestParameter("editval"));
            oxRegistry::get("oxUtilsFile")->processFiles($oArticle);

            // Show that no new image added
            if (oxRegistry::get("oxUtilsFile")->getNewFilesCounter() == 0) {
                $oEx = oxNew("oxExceptionToDisplay");
                $oEx->setMessage('NO_PICTURES_CHANGES');
                oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx, false);
            }

            $oArticle->save();
        }
    }

    /**
     * Deletes selected master picture and all other master pictures
     * where master picture index is higher than currently deleted index.
     * Also deletes custom icon and thumbnail.
     *
     * @return null
     */
    public function deletePicture()
    {
        $myConfig = $this->getConfig();

        if ($myConfig->isDemoShop()) {
            // disabling uploading pictures if this is demo shop
            $oEx = oxNew("oxExceptionToDisplay");
            $oEx->setMessage('ARTICLE_PICTURES_UPLOADISDISABLED');
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx, false);

            return;
        }

        $sOxId = $this->getEditObjectId();
        $iIndex = oxRegistry::getConfig()->getRequestParameter("masterPicIndex");

        $oArticle = oxNew("oxarticle");
        $oArticle->load($sOxId);

        if ($iIndex == "ICO") {
            // deleting main icon
            $this->_deleteMainIcon($oArticle);
        } elseif ($iIndex == "TH") {
            // deleting thumbnail
            $this->_deleteThumbnail($oArticle);
        } else {
            $iIndex = (int) $iIndex;
            if ($iIndex > 0) {
                // deleting master picture
                $this->_resetMasterPicture($oArticle, $iIndex, true);
            }
        }

        $oArticle->save();
    }

    /**
     * Deletes selected master picture and all pictures generated
     * from master picture
     *
     * @param oxArticle $oArticle       article object
     * @param int       $iIndex         master picture index
     * @param bool      $blDeleteMaster if TRUE - deletes and unsets master image file
     *
     * @return null
     */
    protected function _resetMasterPicture($oArticle, $iIndex, $blDeleteMaster = false)
    {
        if ($oArticle->{"oxarticles__oxpic" . $iIndex}->value) {

            if (!$oArticle->isDerived()) {
                $oPicHandler = oxRegistry::get("oxPictureHandler");
                $oPicHandler->deleteArticleMasterPicture($oArticle, $iIndex, $blDeleteMaster);
            }

            if ($blDeleteMaster) {
                //reseting master picture field
                $oArticle->{"oxarticles__oxpic" . $iIndex} = new oxField();
            }

            // cleaning oxzoom fields
            if (isset($oArticle->{"oxarticles__oxzoom" . $iIndex})) {
                $oArticle->{"oxarticles__oxzoom" . $iIndex} = new oxField();
            }

            if ($iIndex == 1) {
                $this->_cleanupCustomFields($oArticle);
            }
        }
    }

    /**
     * Deletes main icon file
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _deleteMainIcon($oArticle)
    {
        if ($oArticle->oxarticles__oxicon->value) {

            if (!$oArticle->isDerived()) {
                $oPicHandler = oxRegistry::get("oxPictureHandler");
                $oPicHandler->deleteMainIcon($oArticle);
            }

            //reseting field
            $oArticle->oxarticles__oxicon = new oxField();
        }
    }

    /**
     * Deletes thumbnail file
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _deleteThumbnail($oArticle)
    {
        if ($oArticle->oxarticles__oxthumb->value) {

            if (!$oArticle->isDerived()) {
                $oPicHandler = oxRegistry::get("oxPictureHandler");
                $oPicHandler->deleteThumbnail($oArticle);
            }

            //reseting field
            $oArticle->oxarticles__oxthumb = new oxField();
        }
    }

    /**
     * Cleans up article custom fields oxicon and oxthumb. If there is custom
     * icon or thumb picture, leaves records untouched.
     *
     * @param oxArticle $oArticle article object
     */
    protected function _cleanupCustomFields($oArticle)
    {
        $myConfig = $this->getConfig();

        $sIcon = $oArticle->oxarticles__oxicon->value;
        $sThumb = $oArticle->oxarticles__oxthumb->value;

        if ($sIcon == "nopic.jpg") {
            $oArticle->oxarticles__oxicon = new oxField();
        }

        if ($sThumb == "nopic.jpg") {
            $oArticle->oxarticles__oxthumb = new oxField();
        }
    }
}
