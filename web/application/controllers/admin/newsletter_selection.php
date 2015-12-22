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
 * Newsletter user group selection manager.
 * Adds/removes chosen user group to/from newsletter mailing.
 * Admin Menu: Customer News -> Newsletter -> Selection.
 */
class Newsletter_Selection extends oxAdminDetails
{

    /**
     * Amount of users assigned to active newsletter receiver group
     *
     * @var int
     */
    protected $_iUserCount = null;

    /**
     * Executes parent method parent::render(), creates oxlist object and
     * collects user groups information, passes it's data to Smarty engine
     * and returns name of template file "newsletter_selection.tpl".
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
            if ($oNewsletter->load($soxId)) {
                $this->_aViewData["edit"] = $oNewsletter;

                if (oxRegistry::getConfig()->getRequestParameter("aoc")) {
                    $oNewsletterSelectionAjax = oxNew('newsletter_selection_ajax');
                    $this->_aViewData['oxajax'] = $oNewsletterSelectionAjax->getColumns();

                    return "popups/newsletter_selection.tpl";
                }
            }
        }

        return "newsletter_selection.tpl";
    }

    /**
     * Returns count of users assigned to active newsletter receiver group
     *
     * @return int
     */
    public function getUserCount()
    {
        if ($this->_iUserCount === null) {
            $this->_iUserCount = 0;

            // load object
            $oNewsletter = oxNew("oxnewsletter");
            if ($oNewsletter->load($this->getEditObjectId())) {
                // get nr. of users in these groups
                // we do not use lists here as we dont need this overhead right now
                $oDB = oxDb::getDb();
                $blSep = false;
                $sSelectGroups = " ( oxobject2group.oxgroupsid in ( ";

                // remove already added groups
                foreach ($oNewsletter->getGroups() as $oInGroup) {
                    if ($blSep) {
                        $sSelectGroups .= ",";
                    }
                    $sSelectGroups .= $oDB->quote($oInGroup->oxgroups__oxid->value);
                    $blSep = true;
                }

                $sSelectGroups .= " ) ) ";

                // no group selected
                if (!$blSep) {
                    $sSelectGroups = " oxobject2group.oxobjectid is null ";
                }
                $sShopId = $this->getConfig()->getShopID();
                $sQ = "select count(*) from ( select oxnewssubscribed.oxemail as _icnt from oxnewssubscribed left join
                   oxobject2group on oxobject2group.oxobjectid = oxnewssubscribed.oxuserid
                   where ( oxobject2group.oxshopid = '{$sShopId}'
                   or oxobject2group.oxshopid is null ) and {$sSelectGroups} and
                   oxnewssubscribed.oxdboptin = 1 and ( not ( oxnewssubscribed.oxemailfailed = '1') )
                   and (not(oxnewssubscribed.oxemailfailed = '1')) and oxnewssubscribed.oxshopid = '{$sShopId}'
                   group by oxnewssubscribed.oxemail ) as _tmp";

                $this->_iUserCount = $oDB->getOne($sQ, false, false);
            }
        }

        return $this->_iUserCount;
    }

    /**
     * Saves newsletter selection changes.
     */
    public function save()
    {
        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        $aParams['oxnewsletter__oxshopid'] = $this->getConfig()->getShopId();

        $oNewsletter = oxNew("oxNewsLetter");
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
