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
 * user list "view" class.
 */
class List_Order extends Order_List
{

    /**
     * Enable/disable sorting by DESC (SQL) (defaultfalse - disable).
     *
     * @var bool
     */
    protected $_blDesc = false;

    /**
     * Returns sorting fields array
     *
     * @return array
     */
    public function getListSorting()
    {
        $aSort = oxRegistry::getConfig()->getRequestParameter('sort');
        if ($this->_aCurrSorting === null && isset($aSort[0]['oxorderdate'])) {
            $this->_aCurrSorting[]["max(oxorder.oxorderdate)"] = "desc";

            return $this->_aCurrSorting;
        } else {
            return parent::getListSorting();
        }
    }

    /**
     * Viewable list size getter
     *
     * @return int
     */
    protected function _getViewListSize()
    {
        return $this->_getUserDefListSize();
    }

    /**
     * Executes parent method parent::render(), passes data to Smarty engine
     * and returns name of template file "list_review.tpl".
     *
     * @return string
     */
    public function render()
    {
        oxAdminList::render();

        $this->_aViewData["menustructure"] = $this->getNavigation()->getDomXml()->documentElement->childNodes;

        return "list_order.tpl";
    }


    /**
     * Adding folder check
     *
     * @param array  $aWhere  SQL condition array
     * @param string $sqlFull SQL query string
     *
     * @return $sQ
     */
    public function _prepareWhereQuery($aWhere, $sqlFull)
    {
        $sQ = oxAdminList::_prepareWhereQuery($aWhere, $sqlFull);
        $sQ .= " group by oxorderarticles.oxartnum";

        return $sQ;
    }

    /**
     * Calculates list items count
     *
     * @param string $sSql SQL query used co select list items
     */
    protected function _calcListItemsCount($sSql)
    {
        $oStr = getStr();

        // count SQL
        $sSql = $oStr->preg_replace('/select .* from/', 'select count(*) from ', $sSql);

        // removing order by
        $sSql = $oStr->preg_replace('/order by .*$/', '', $sSql);

        // con of list items which fits current search conditions
        $this->_iListSize = oxDb::getDb()->getOne("select count(*) from ( $sSql ) as test", false, false);

        // set it into session that other frames know about size of DB
        oxRegistry::getSession()->setVariable('iArtCnt', $this->_iListSize);
    }

    /**
     * Returns select query string
     *
     * @param object $oObject Object
     *
     * @return string
     */
    protected function _buildSelectString($oObject = null)
    {
        return 'select oxorderarticles.oxid, oxorder.oxid as oxorderid, max(oxorder.oxorderdate) as oxorderdate, oxorderarticles.oxartnum, sum( oxorderarticles.oxamount ) as oxorderamount, oxorderarticles.oxtitle, round( sum(oxorderarticles.oxbrutprice*oxorder.oxcurrate),2) as oxprice from oxorderarticles left join oxorder on oxorder.oxid=oxorderarticles.oxorderid where 1 ';
    }

    /**
     * Adds order by to SQL query string.
     *
     * @param string $sSql sql string
     *
     * @return string
     */
    protected function _prepareOrderByQuery($sSql = null)
    {
        // calculating sum
        $sSumQ = getStr()->preg_replace(array("/select .*? from/", "/group by oxorderarticles.oxartnum/"), array("select round( sum(oxorderarticles.oxbrutprice*oxorder.oxcurrate),2) from", ""), $sSql);
        $this->_aViewData["sumresult"] = oxDb::getDb()->getOne($sSumQ, false, false);

        return parent::_prepareOrderByQuery($sSql);
    }
}
