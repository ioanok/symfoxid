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
 * Class OxpsPaymorrowErrorHandler.
 */
class OxpsPaymorrowErrorHandler extends OxpsPaymorrowModule
{

    /**
     * List of public error codes.
     *
     * @var array
     */
    protected $_aPublicErrors = array(

        // Payment form submission/validation/other errors
        3000 => 'GENERAL_ERROR',
    );


    /**
     * Get human readable error message by error code.
     *
     * @param integer $iErrorCode
     *
     * @return string
     */
    public function getErrorByCode( $iErrorCode )
    {
        return array_key_exists( $iErrorCode, $this->_aPublicErrors )
            ? $this->translateError( $this->_aPublicErrors[$iErrorCode] )
            : $this->translateError( $this->_aPublicErrors[3000] ); // If exact error not exist throw general
    }

    /**
     * Redirect user to given controller and shows an error.
     * In case of 'RELOAD_CONFIGURATION_REQUIRED' error, update module settings and redirect.
     *
     * @codeCoverageIgnore
     *
     * @param        $iErrorCode
     * @param string $sController
     */
    public function redirectWithError( $iErrorCode, $sController = 'order' )
    {
        $sErrorMessage = $this->getErrorByCode( $iErrorCode );

        // Set error
        $oEx = oxNew( 'oxExceptionToDisplay' );
        $oEx->setMessage( $sErrorMessage );
        oxRegistry::get( "oxUtilsView" )->addErrorToDisplay( $oEx, false );

        // Redirect (refresh page)
        $sUrl = $this->getConfig()->getShopCurrentUrl() . "cl=" . $sController;
        $sUrl = oxRegistry::get( "oxUtilsUrl" )->processUrl( $sUrl );
        oxRegistry::getUtils()->redirect( $sUrl );

        return;
    }

    /**
     * Translate Paymorrow errors.
     * Alias for module `translate` method.
     *
     * @param string $sError
     *
     * @return string
     */
    public function translateError( $sError )
    {
        return $this->translate( $sError );
    }
}
