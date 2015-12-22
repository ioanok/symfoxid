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
 * Translarent shop manager (executed automatically), sets
 * registration information and current shop object.
 *
 * @subpackage oxcmp
 */
class oxcmp_shop extends oxView
{

    /**
     * Marking object as component
     *
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Executes parent::render() and returns active shop object.
     *
     * @return  object  $this->oActShop active shop object
     */
    public function render()
    {
        parent::render();

        $myConfig = $this->getConfig();

        // is shop active?
        $oShop = $myConfig->getActiveShop();
        $sActiveField = 'oxshops__oxactive';
        $sClassName = $myConfig->getActiveView()->getClassName();

        if (!$oShop->$sActiveField->value && 'oxstart' != $sClassName && !$this->isAdmin()) {
            // redirect to offline if there is no active shop
            oxRegistry::getUtils()->redirectOffline();
        }

        return $oShop;
    }
}
