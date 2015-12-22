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
 * Admin user history settings manager.
 * Collects user history settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> History.
 */
class User_Remark extends oxAdminDetails
{

    /**
     * Executes parent method parent::render(), creates oxuser, oxlist and
     * oxRemark objects, passes data to Smarty engine and returns name of
     * template file "user_remark.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        $sRemoxId = oxRegistry::getConfig()->getRequestParameter("rem_oxid");
        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oUser = oxNew("oxuser");
            $oUser->load($soxId);
            $this->_aViewData["edit"] = $oUser;

            // all remark
            $oRems = oxNew("oxlist");
            $oRems->init("oxremark");
            $sQuotedUserId = oxDb::getDb()->quote($oUser->getId());
            $sSelect = "select * from oxremark where oxparentid=" . $sQuotedUserId . " order by oxcreate desc";
            $oRems->selectString($sSelect);
            foreach ($oRems as $key => $val) {
                if ($val->oxremark__oxid->value == $sRemoxId) {
                    $val->selected = 1;
                    $oRems[$key] = $val;
                    break;
                }
            }

            $this->_aViewData["allremark"] = $oRems;

            if (isset($sRemoxId)) {
                $oRemark = oxNew("oxRemark");
                $oRemark->load($sRemoxId);
                $this->_aViewData["remarktext"] = $oRemark->oxremark__oxtext->value;
                $this->_aViewData["remarkheader"] = $oRemark->oxremark__oxheader->value;
            }
        }

        return "user_remark.tpl";
    }

    /**
     * Saves user history text changes.
     */
    public function save()
    {
        parent::save();

        $oRemark = oxNew("oxremark");

        // try to load if exists
        $oRemark->load(oxRegistry::getConfig()->getRequestParameter("rem_oxid"));

        $oRemark->oxremark__oxtext = new oxField(oxRegistry::getConfig()->getRequestParameter("remarktext"));
        $oRemark->oxremark__oxheader = new oxField(oxRegistry::getConfig()->getRequestParameter("remarkheader"));
        $oRemark->oxremark__oxparentid = new oxField($this->getEditObjectId());
        $oRemark->oxremark__oxtype = new oxField("r");
        $oRemark->save();
    }

    /**
     * Deletes user actions history record.
     */
    public function delete()
    {
        $oRemark = oxNew("oxRemark");
        $oRemark->delete(oxRegistry::getConfig()->getRequestParameter("rem_oxid"));
    }
}
