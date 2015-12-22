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
 * Guestbook records list manager.
 * Returns template, that arranges guestbook records list.
 * Admin Menu: User information -> Guestbook.
 */
class AdminGuestbook_List extends oxAdminList
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'adminguestbook_list.tpl';

    /**
     * List item object type
     *
     * @var string
     */
    protected $_sListClass = 'oxgbentry';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = 'oxcreate';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_blDesc = true;

    /**
     * Executes parent method parent::render(), gets entries with authors
     * and returns template file name "admin_guestbook.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oList = $this->getItemList();
        if ($oList && $oList->count()) {

            $oDb = oxDb::getDb();
            foreach ($oList as $oEntry) {
                // preloading user info ..
                $sUserIdField = 'oxgbentries__oxuserid';
                $sUserLastNameField = 'oxuser__oxlname';
                if (isset($oEntry->$sUserIdField) && $oEntry->$sUserIdField->value) {
                    $sSql = "select oxlname from oxuser where oxid=" . $oDb->quote($oEntry->$sUserIdField->value);
                    $oEntry->$sUserLastNameField = new oxField($oDb->getOne($sSql, false, false));
                }
            }
        }

        $this->_aViewData["mylist"] = $oList;

        return $this->_sThisTemplate;
    }

}
