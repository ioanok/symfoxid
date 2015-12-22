<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.

 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 */

/**
 * Class handling shop themes
 *
 */
class oeThemeSwitcherTheme extends oeThemeSwitcherTheme_parent
{
    /**
     * Get theme info item
     *
     * @param string $sName name of info item to retrieve
     *
     * @return mixed
     */
    public function getInfo($sName)
    {
        $sValue = parent::getInfo($sName);

        if ($sName == 'active' && $this->getId() == $this->getConfig()->getConfigParam('sOEThemeSwitcherMobileTheme')) {
            return true;
        }

        return $sValue;
    }
}
