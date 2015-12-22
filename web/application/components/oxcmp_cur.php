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
 * Currency manager class.
 *
 * @subpackage oxcmp
 */
class oxcmp_cur extends oxView
{

    /**
     * Array of available currencies.
     *
     * @var array
     */
    public $aCurrencies = null;

    /**
     * Active currency object.
     *
     * @var object
     */
    protected $_oActCur = null;

    /**
     * Marking object as component
     *
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Checks for currency parameter set in URL, session or post
     * variables. If such were found - loads all currencies possible
     * in shop, searches if passed is available (if no - default
     * currency is set the first defined in admin). Then sets currency
     * parameter so session ($myConfig->setActShopCurrency($iCur)),
     * loads basket and forces ir to recalculate (oBasket->blCalcNeeded
     * = true). Finally executes parent::init().
     *
     * @return null
     */
    public function init()
    {
        // Performance
        $myConfig = $this->getConfig();
        if (!$myConfig->getConfigParam('bl_perfLoadCurrency')) {
            //#861C -  show first currency
            $aCurrencies = $myConfig->getCurrencyArray();
            $this->_oActCur = current($aCurrencies);

            return;
        }

        $iCur = oxRegistry::getConfig()->getRequestParameter('cur');
        if (isset($iCur)) {
            $aCurrencies = $myConfig->getCurrencyArray();
            if (!isset($aCurrencies[$iCur])) {
                $iCur = 0;
            }

            // set new currency
            $myConfig->setActShopCurrency($iCur);

            // recalc basket
            $oBasket = $this->getSession()->getBasket();
            $oBasket->onUpdate();
        }

        $iActCur = $myConfig->getShopCurrency();
        $this->aCurrencies = $myConfig->getCurrencyArray($iActCur);

        $this->_oActCur = $this->aCurrencies[$iActCur];

        //setting basket currency (M:825)
        if (!isset($oBasket)) {
            $oBasket = $this->getSession()->getBasket();
        }
        $oBasket->setBasketCurrency($this->_oActCur);
        parent::init();
    }

    /**
     * Executes parent::render(), passes currency object to template
     * engine and returns currencies array.
     *
     * Template variables:
     * <b>currency</b>
     *
     * @return array
     */
    public function render()
    {
        parent::render();
        $oParentView = $this->getParent();
        $oParentView->setActCurrency($this->_oActCur);

        $oUrlUtils = oxRegistry::get("oxUtilsUrl");
        $sUrl = $oUrlUtils->cleanUrl($this->getConfig()->getTopActiveView()->getLink(), array("cur"));

        if ($this->getConfig()->getConfigParam('bl_perfLoadCurrency')) {
            reset($this->aCurrencies);
            while (list(, $oItem) = each($this->aCurrencies)) {
                $oItem->link = $oUrlUtils->processUrl($sUrl, true, array("cur" => $oItem->id));
            }
        }

        return $this->aCurrencies;
    }
}
