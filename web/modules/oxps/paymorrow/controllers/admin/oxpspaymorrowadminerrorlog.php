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
 * Class OxpsPaymorrowAdminErrorLog
 */
class OxpsPaymorrowAdminErrorLog extends oxAdminView
{

    /**
     * Current class template.
     *
     * @var string
     */
    protected $_sThisTemplate = 'paymorrow_errorlog.tpl';


    /**
     * Get Paymorrow Error Log contents
     *
     * @return string - error log file contents
     */
    public function getPaymorrowErrorLog()
    {
        /** @var OxpsPaymorrowLogger $oLogger */
        $oLogger = oxRegistry::get( 'OxpsPaymorrowLogger' );

        return $oLogger->getAllContents();
    }
}
