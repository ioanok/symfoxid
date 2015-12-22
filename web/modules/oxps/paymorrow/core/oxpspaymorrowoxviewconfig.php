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
 */

/**
 * Class OxpsPaymorrowOxViewConfig extends oxViewConfig
 *
 * @see oxViewConfig
 */
class OxpsPaymorrowOxViewConfig extends OxpsPaymorrowOxViewConfig_parent
{

    /**
     * Get configured Paymorrow merchant ID for active mode.
     *
     * @return null|string
     */
    public function getPaymorrowMerchantId()
    {
        /** @var OxpsPaymorrowSettings $oSettings */
        $oSettings = oxRegistry::get( 'OxpsPaymorrowSettings' );

        return $oSettings->getMerchantId();
    }

    /**
     * Get active admin interface language abbreviation.
     *
     * @return string
     */
    public function getActiveInterfaceLanguageAbbr()
    {
        /** @var oxUtilsServer $oServerUtils */
        $oServerUtils = oxRegistry::get( 'oxUtilsServer' );

        return (string) $oServerUtils->getOxCookie( 'oxidadminlanguage' );
    }
}
