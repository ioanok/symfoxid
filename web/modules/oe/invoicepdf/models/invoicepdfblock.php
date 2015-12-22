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
 * PDF renderer helper class, used to store data, sizes etc..
 */
class InvoicepdfBlock
{

    /**
     * array of data to render
     *
     * @var array
     */
    protected $_aCache = array();

    /**
     * string default Font
     *
     * @var array
     */
    protected $_sFont = 'Arial';

    /**
     * Stores cacheable parameters.
     *
     * @param string $sFunc   cacheable function name
     * @param array  $aParams cacheable parameters
     */
    protected function _toCache($sFunc, $aParams)
    {
        $oItem = new stdClass();
        $oItem->sFunc = $sFunc;
        $oItem->aParams = $aParams;
        $this->_aCache[] = $oItem;
    }

    /**
     * Runs and evaluates cached code.
     *
     * @param object $oPdf object which methods will be executed
     */
    public function run($oPdf)
    {
        foreach ($this->_aCache as $oItem) {
            $sFn = $oItem->sFunc;
            switch (count($oItem->aParams)) {
                case 0:
                    $oPdf->$sFn();
                    break;
                case 1:
                    $oPdf->$sFn($oItem->aParams[0]);
                    break;
                case 2:
                    $oPdf->$sFn($oItem->aParams[0], $oItem->aParams[1]);
                    break;
                case 3:
                    $oPdf->$sFn($oItem->aParams[0], $oItem->aParams[1], $oItem->aParams[2]);
                    break;
                case 4:
                    $oPdf->$sFn($oItem->aParams[0], $oItem->aParams[1], $oItem->aParams[2], $oItem->aParams[3]);
                    break;
            }
        }
    }

    /**
     * Caches Line call with parameters.
     *
     * @param int $iLPos    left position
     * @param int $iLHeight left height
     * @param int $iRPos    right position
     * @param int $iRHeight right height
     */
    public function line($iLPos, $iLHeight, $iRPos, $iRHeight)
    {
        $this->_toCache('Line', array($iLPos, $iLHeight, $iRPos, $iRHeight));
    }

    /**
     * Caches Text call with parameters.
     *
     * @param int    $iLPos    left position
     * @param int    $iLHeight height
     * @param string $sString  string to write
     */
    public function text($iLPos, $iLHeight, $sString)
    {
        $this->_toCache('Text', array($iLPos, $iLHeight, $sString));
    }

    /**
     * Caches SetFont call with parameters.
     *
     * @param string $sType   font type (Arial, Tahoma ...)
     * @param string $sWeight font weight ('', 'B', 'U' ...)
     * @param string $sSize   font size ('10', '8', '6' ...)
     */
    public function font($sType, $sWeight, $sSize)
    {
        $this->_toCache('SetFont', array($sType, $sWeight, $sSize));
    }

    /**
     * Adjusts height after new page addition.
     *
     * @param int $iDelta new height
     */
    public function ajustHeight($iDelta)
    {
        foreach ($this->_aCache as $key => $oItem) {
            switch ($oItem->sFunc) {
                case 'Line':
                    $this->_aCache[$key]->aParams[3] += $iDelta;
                    $this->_aCache[$key]->aParams[1] += $iDelta;
                    break;
                case 'Text':
                    $this->_aCache[$key]->aParams[1] += $iDelta;
                    break;
            }
        }
    }

    /**
     * Caches SetFont call with parameters.
     *
     * @return string
     */
    public function getFont()
    {
        return $this->_sFont;
    }
}
