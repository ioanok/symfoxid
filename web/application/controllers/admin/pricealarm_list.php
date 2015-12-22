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
 * Admin pricealarm list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Customer News -> pricealarm.
 */
class PriceAlarm_List extends oxAdminList
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'pricealarm_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxpricealarm';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = "oxuserid";

    /**
     * Modifying SQL query to load additional article and customer data
     *
     * @param object $oListObject list main object
     *
     * @return string
     */
    protected function _buildSelectString($oListObject = null)
    {
        $sViewName = getViewName("oxarticles", (int) $this->getConfig()->getConfigParam("sDefaultLang"));
        $sSql = "select oxpricealarm.*, {$sViewName}.oxtitle AS articletitle, ";
        $sSql .= "oxuser.oxlname as userlname, oxuser.oxfname as userfname ";
        $sSql .= "from oxpricealarm left join {$sViewName} on {$sViewName}.oxid = oxpricealarm.oxartid ";
        $sSql .= "left join oxuser on oxuser.oxid = oxpricealarm.oxuserid WHERE 1 ";

        return $sSql;
    }

    /**
     * Builds and returns array of SQL WHERE conditions
     *
     * @return array
     */
    public function buildWhere()
    {
        $this->_aWhere = parent::buildWhere();
        $sViewName = getViewName("oxpricealarm");
        $sArtViewName = getViewName("oxarticles");

        // updating price fields values for correct search in DB
        if (isset($this->_aWhere[$sViewName . '.oxprice'])) {
            $sPriceParam = (double) str_replace(array('%', ','), array('', '.'), $this->_aWhere[$sViewName . '.oxprice']);
            $this->_aWhere[$sViewName . '.oxprice'] = '%' . $sPriceParam . '%';
        }

        if (isset($this->_aWhere[$sArtViewName . '.oxprice'])) {
            $sPriceParam = (double) str_replace(array('%', ','), array('', '.'), $this->_aWhere[$sArtViewName . '.oxprice']);
            $this->_aWhere[$sArtViewName . '.oxprice'] = '%' . $sPriceParam . '%';
        }


        return $this->_aWhere;
    }
}
