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
 * Class OxpsPaymorrowOxBasketItem extends oxBasketItem
 *
 * @see oxBasketItem
 */
class OxpsPaymorrowOxBasketItem extends OxpsPaymorrowOxBasketItem_parent
{

    const PAYMORROW_LINE_ITEM_PREFIX = "lineItem_%d_";


    /**
     * Paymorrow Line Item prefix builder
     *
     * @param $iLineItemCount
     *
     * @return string
     */
    public static function getPaymorrowBasketSummaryLineItemPrefix( $iLineItemCount )
    {
        return sprintf( self::PAYMORROW_LINE_ITEM_PREFIX, $iLineItemCount );
    }

    /**
     * Get related article number.
     *
     * @return string
     */
    public function getProductNumber()
    {
        /** @var $this OxpsPaymorrowOxBasketItem|oxBasketItem */

        /** @var oxArticle $oArticle */
        $oArticle = $this->getArticle();

        return isset( $oArticle->oxarticles__oxartnum->value ) ? (string) $oArticle->oxarticles__oxartnum->value : '';
    }

    /**
     * Compiles summary data array of basket item for Paymorrow.
     *
     * @param int $iLineItemCount
     *
     * @return array
     */
    public function getPaymorrowBasketItemSummary( $iLineItemCount )
    {
        /** @var OxpsPaymorrowOxBasketItem|oxBasketItem $this */

        $sPaymorrowLineItemPrefix = self::getPaymorrowBasketSummaryLineItemPrefix( $iLineItemCount );

        return array(
            $sPaymorrowLineItemPrefix . 'quantity'       => (double) $this->getAmount(),
            $sPaymorrowLineItemPrefix . 'articleId'      => $this->_toUtf( $this->getProductNumber() ),
            $sPaymorrowLineItemPrefix . 'name'           => $this->_toUtf( $this->getTitle(), 50 ),
            $sPaymorrowLineItemPrefix . 'type'           => 'GOODS',
            $sPaymorrowLineItemPrefix . 'unitPriceGross' => (double) $this->getUnitPrice()->getBruttoPrice(),
            $sPaymorrowLineItemPrefix . 'grossAmount'    => (double) $this->getPrice()->getBruttoPrice(),
            $sPaymorrowLineItemPrefix . 'vatAmount'      => (double) $this->getPrice()->getVatValue(),
            $sPaymorrowLineItemPrefix . 'vatRate'        => (double) $this->getVatPercent(),
        );
    }

    /**
     * Alias for encoding casting method.
     *
     * @codeCoverageIgnore
     * @see OxpsPaymorrowEshopDataProvider::toUtf
     *
     * @param string   $sString
     * @param null|int $mLimitLength
     *
     * @return string
     */
    protected function _toUtf( $sString, $mLimitLength = null )
    {
        return OxpsPaymorrowEshopDataProvider::toUtf( $sString, $mLimitLength );
    }
}
