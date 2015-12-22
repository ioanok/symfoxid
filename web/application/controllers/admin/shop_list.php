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
 * Admin shop list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Main Menu -> Core Settings.
 */
class Shop_List extends oxAdminList
{

    /**
     * Forces main frame update is set TRUE
     *
     * @var bool
     */
    protected $_blUpdateMain = false;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = 'oxname';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxshop';

    /**
     * Navigation frame reload marker
     *
     * @var bool
     */
    protected $_blUpdateNav = null;

    /**
     * Sets SQL query parameters (such as sorting),
     * executes parent method parent::Init().
     */
    public function init()
    {
        parent::Init();

    }

    /**
     * Executes parent method parent::render() and returns name of template
     * file "shop_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ($soxId != '-1' && isset($soxId)) {
            // load object
            $oShop = oxNew('oxshop');
            if (!$oShop->load($soxId)) {
                $soxId = $myConfig->getBaseShopId();
                $oShop->load($soxId);
            }
            $this->_aViewData['editshop'] = $oShop;
        }

        // default page number 1
        $this->_aViewData['default_edit'] = 'shop_main';
        $this->_aViewData['updatemain'] = $this->_blUpdateMain;

        if ($this->_aViewData['updatenav']) {
            //skipping requirements checking when reloading nav frame
            oxRegistry::getSession()->setVariable("navReload", true);
        }

        //making sure we really change shops on low level
        if ($soxId && $soxId != '-1') {
            $myConfig->setShopId($soxId);
            oxRegistry::getSession()->setVariable('currentadminshop', $soxId);
        }

        return 'shop_list.tpl';
    }

    /**
     * Sets SQL WHERE condition. Returns array of conditions.
     *
     * @return array
     */
    public function buildWhere()
    {
        // we override this to add our shop if we are not malladmin
        $this->_aWhere = parent::buildWhere();
        if (!oxRegistry::getSession()->getVariable('malladmin')) {
            // we only allow to see our shop
            $this->_aWhere[getViewName("oxshops") . ".oxid"] = oxRegistry::getSession()->getVariable("actshop");
        }

        return $this->_aWhere;
    }

}
