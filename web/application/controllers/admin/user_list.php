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
 * Admin user list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: User Administration -> Users.
 */
class User_List extends oxAdminList
{

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxuser';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = "oxusername";

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'oxuserlist';

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'user_list.tpl';

    /**
     * Executes parent::render(), sets blacklist and preventdelete flag
     *
     * @return null
     */
    public function render()
    {
        foreach ($this->getItemList() as $sId => $oUser) {
            if ($oUser->inGroup("oxidblacklist") || $oUser->inGroup("oxidblocked")) {
                $oUser->blacklist = "1";
            }
            $oUser->blPreventDelete = false;
            if (!$this->_allowAdminEdit($sId)) {
                $oUser->blPreventDelete = true;
            }
        }

        return parent::render();
    }

    /**
     * Admin user is allowed to be deleted only by mall admin
     *
     * @return null
     */
    public function deleteEntry()
    {
        if ($this->_allowAdminEdit($this->getEditObjectId())) {
            $this->_oList = null;
            return parent::deleteEntry();
        }
    }

    /**
     * Prepares SQL where query according SQL condition array and attaches it to SQL end.
     * For each search value if german umlauts exist, adds them
     * and replaced by spec. char to query
     *
     * @param array  $aWhere     SQL condition array
     * @param string $sQueryFull SQL query string
     *
     * @return string
     */
    public function _prepareWhereQuery($aWhere, $sQueryFull)
    {
        $aNameWhere = null;
        if (isset($aWhere['oxuser.oxlname']) && ($sName = $aWhere['oxuser.oxlname'])) {
            // check if this is search string (contains % sign at begining and end of string)
            $blIsSearchValue = $this->_isSearchValue($sName);
            $sName = $this->_processFilter($sName);
            $aNameWhere['oxuser.oxfname'] = $aNameWhere['oxuser.oxlname'] = $sName;

            // unsetting..
            unset($aWhere['oxuser.oxlname']);
        }
        $sQ = parent::_prepareWhereQuery($aWhere, $sQueryFull);

        if ($aNameWhere) {

            $aVal = explode(' ', $sName);
            $sQ .= ' and (';
            $sSqlBoolAction = '';
            $myUtilsString = oxRegistry::get("oxUtilsString");

            foreach ($aNameWhere as $sFieldName => $sValue) {

                //for each search field using AND anction
                foreach ($aVal as $sVal) {

                    $sQ .= " {$sSqlBoolAction} {$sFieldName} ";

                    //for search in same field for different values using AND
                    $sSqlBoolAction = ' or ';

                    $sQ .= $this->_buildFilter($sVal, $blIsSearchValue);

                    // trying to search spec chars in search value
                    // if found, add cleaned search value to search sql
                    $sUml = $myUtilsString->prepareStrForSearch($sVal);
                    if ($sUml) {
                        $sQ .= " or {$sFieldName} ";
                        $sQ .= $this->_buildFilter($sUml, $blIsSearchValue);
                    }
                }
            }

            // end for AND action
            $sQ .= ' ) ';
        }


        return $sQ;
    }

}
