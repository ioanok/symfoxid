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
 * Shop language manager.
 * Performs language manager function: changes template settings, modifies URL's.
 *
 * @subpackage oxcmp
 */
class oxcmp_lang extends oxView
{

    /**
     * Marking object as component
     *
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Executes parent::render() and returns array with languages.
     *
     * @return array $this->aLanguages languages
     */
    public function render()
    {
        parent::render();

        // Performance
        if ($this->getConfig()->getConfigParam('bl_perfLoadLanguages')) {
            $aLanguages = oxRegistry::getLang()->getLanguageArray(null, true, true);
            reset($aLanguages);
            while (list($sKey, $oVal) = each($aLanguages)) {
                $aLanguages[$sKey]->link = $this->getConfig()->getTopActiveView()->getLink($oVal->id);
            }

            return $aLanguages;
        }
    }
}
