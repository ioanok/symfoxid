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
 * Class oxBasketContentMarkGenerator which forms explanation marks.
 */
class oxBasketContentMarkGenerator
{

    /**
     * Default value for explanation mark.
     */
    const DEFAULT_EXPLANATION_MARK = '**';

    /**
     * Marks added to array by article type.
     *
     * @var array
     */
    private $_aMarks;

    /**
     * Basket that is used to get article type(downloadable, intangible etc..).
     *
     * @var oxBasket
     */
    private $_oBasket;

    /**
     * Sets basket that is used to get article type(downloadable, intangible etc..).
     *
     * @param oxBasket $oBasket
     */
    public function __construct(oxBasket $oBasket)
    {
        $this->_oBasket = $oBasket;
    }

    /**
     * Returns explanation mark by given mark identification (skippedDiscount, downloadable, intangible).
     *
     * @param string $sMarkIdentification Mark identification.
     *
     * @return string
     */
    public function getMark($sMarkIdentification)
    {
        if (is_null($this->_aMarks)) {
            $sCurrentMark = self::DEFAULT_EXPLANATION_MARK;
            $aMarks = $this->_formMarks($sCurrentMark);
            $this->_aMarks = $aMarks;
        }

        return $this->_aMarks[$sMarkIdentification];
    }

    /**
     * Basket that is used to get article type(downloadable, intangible etc..).
     *
     * @return oxBasket
     */
    private function _getBasket()
    {
        return $this->_oBasket;
    }

    /**
     * Forms marks for articles.
     *
     * @param string $sCurrentMark Current mark.
     *
     * @return array
     */
    private function _formMarks($sCurrentMark)
    {
        $oBasket = $this->_getBasket();
        $aMarks = array();
        if ($oBasket->hasSkipedDiscount()) {
            $aMarks['skippedDiscount'] = $sCurrentMark;
            $sCurrentMark .= '*';
        }
        if ($oBasket->hasArticlesWithDownloadableAgreement()) {
            $aMarks['downloadable'] = $sCurrentMark;
            $sCurrentMark .= '*';
        }
        if ($oBasket->hasArticlesWithIntangibleAgreement()) {
            $aMarks['intangible'] = $sCurrentMark;
        }

        return $aMarks;
    }
}
