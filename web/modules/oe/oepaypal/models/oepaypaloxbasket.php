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
 * PayPal oxBasket class
 */
class oePayPalOxBasket extends oePayPalOxBasket_parent
{
    /**
     * Checks if products in basket ar virtual and does not require real delivery.
     * Returns TRUE if virtual
     *
     * @return bool
     */
    public function isVirtualPayPalBasket()
    {
        $blVirtual = true;

        $aProducts = $this->getBasketArticles();
        foreach ($aProducts as $oProduct) {
            if (!$oProduct->isVirtualPayPalArticle()) {
                $blVirtual = false;
                break;
            }
        }

        return $blVirtual;
    }

    /**
     * Checks if fraction quantity items (with 1.3 amount) exists in basket.
     *
     * @return bool
     */
    public function isFractionQuantityItemsPresent()
    {
        $blFractionItemsPresent = false;

        foreach ($this->getContents() as $oBasketItem) {
            $dAmount = $oBasketItem->getAmount();
            if ((int) $dAmount != $dAmount) {
                $blFractionItemsPresent = true;
                break;
            }
        }

        return $blFractionItemsPresent;
    }

    /**
     * Returns wrapping cost value
     *
     * @return double
     */
    public function getPayPalWrappingCosts()
    {
        $dWrappingPrice = 0;

        $oWrappingCost = $this->getCosts('oxwrapping');
        if ($oWrappingCost) {
            $dWrappingPrice = $this->isCalculationModeNetto() ? $oWrappingCost->getNettoPrice() : $oWrappingCost->getBruttoPrice();
        }

        return $dWrappingPrice;
    }

    /**
     * Returns greeting card cost value
     *
     * @return double
     */
    public function getPayPalGiftCardCosts()
    {
        $dGiftCardPrice = 0;

        $oGiftCardCost = $this->getCosts('oxgiftcard');
        if ($oGiftCardCost) {
            $dGiftCardPrice = $this->isCalculationModeNetto() ? $oGiftCardCost->getNettoPrice() : $oGiftCardCost->getBruttoPrice();
        }

        return $dGiftCardPrice;
    }

    /**
     * Returns payment costs netto or brutto value.
     *
     * @return double
     */
    public function getPayPalPaymentCosts()
    {
        $dPaymentCost = 0;

        $oPaymentCost = $this->getCosts('oxpayment');
        if ($oPaymentCost) {
            $dPaymentCost = $this->isCalculationModeNetto() ? $oPaymentCost->getNettoPrice() : $oPaymentCost->getBruttoPrice();
        }

        return $dPaymentCost;
    }

    /**
     * Returns Trusted shops costs netto or brutto value.
     *
     * @return double
     */
    public function getPayPalTsProtectionCosts()
    {
        $dTsPaymentCost = 0;

        $oTsPaymentCost = $this->getCosts('oxtsprotection');
        if ($oTsPaymentCost) {
            $dTsPaymentCost = $this->isCalculationModeNetto() ? $oTsPaymentCost->getNettoPrice() : $oTsPaymentCost->getBruttoPrice();
        }

        return $dTsPaymentCost;
    }

    /**
     * Collects all basket discounts (basket, payment and vouchers)
     * and returns sum of collected discounts.
     *
     * @return double
     */
    public function getDiscountSumPayPalBasket()
    {
        // collect discounts
        $dDiscount = 0;

        $oTotalDiscount = $this->getTotalDiscount();

        if ($oTotalDiscount) {
            $dDiscount += $oTotalDiscount->getBruttoPrice();
        }

        //if payment costs are negative, adding them to discount
        if (($dCosts = $this->getPaymentCosts()) < 0) {
            $dDiscount += ($dCosts * -1);
        }

        // vouchers..
        $aVouchers = (array) $this->getVouchers();
        foreach ($aVouchers as $oVoucher) {
            $dDiscount += round($oVoucher->dVoucherdiscount, 2);
        }

        return $dDiscount;
    }

    /**
     * Calculates basket costs (payment, GiftCard and gift card)
     * and returns sum of all costs.
     *
     * @return double
     */
    public function getSumOfCostOfAllItemsPayPalBasket()
    {
        // basket items sum
        $dAllCosts = $this->getProductsPrice()->getSum($this->isCalculationModeNetto());

        //adding to additional costs only if payment is > 0
        if (($dCosts = $this->getPayPalPaymentCosts()) > 0) {
            $dAllCosts += $dCosts;
        }

        // wrapping costs
        $dAllCosts += $this->getPayPalWrappingCosts();

        // greeting card costs
        $dAllCosts += $this->getPayPalGiftCardCosts();

        // Trusted shops protection cost
        $dAllCosts += $this->getPayPalTsProtectionCosts();

        return $dAllCosts;
    }

    /**
     * Returns absolute VAT value.
     *
     * @return float
     */
    public function getPayPalBasketVatValue()
    {
        $flBasketVatValue = 0;
        $flBasketVatValue += $this->getPayPalProductVat();
        $flBasketVatValue += $this->getPayPalWrappingVat();
        $flBasketVatValue += $this->getPayPalGiftCardVat();
        $flBasketVatValue += $this->getPayPalPayCostVat();
        $flBasketVatValue += $this->getPayPalTsProtectionCostVat();

        return $flBasketVatValue;
    }

    /**
     * Return products VAT.
     *
     * @return double
     */
    public function getPayPalProductVat()
    {
        $aProductVatValue = $this->getProductVats(false);
        $dProductVatValue = array_sum($aProductVatValue);

        return $dProductVatValue;
    }

    /**
     * Return wrapping VAT.
     *
     * @return double
     */
    public function getPayPalWrappingVat()
    {
        $dWrappingVat = 0;

        $oWrapping = $this->getCosts('oxwrapping');
        if ($oWrapping && $oWrapping->getVatValue()) {
            $dWrappingVat = $oWrapping->getVatValue();
        }

        return $dWrappingVat;
    }

    /**
     * Return gift card VAT.
     *
     * @return double
     */
    public function getPayPalGiftCardVat()
    {
        $dGiftCardVat = 0;

        $oGiftCard = $this->getCosts('oxgiftcard');
        if ($oGiftCard && $oGiftCard->getVatValue()) {
            $dGiftCardVat = $oGiftCard->getVatValue();
        }

        return $dGiftCardVat;
    }

    /**
     * Return payment VAT.
     *
     * @return double
     */
    public function getPayPalPayCostVat()
    {
        $dPayVAT = 0;

        $oPaymentCost = $this->getCosts('oxpayment');
        if ($oPaymentCost && $oPaymentCost->getVatValue()) {
            $dPayVAT = $oPaymentCost->getVatValue();
        }

        return $dPayVAT;
    }

    /**
     * Return payment VAT.
     *
     * @return double
     */
    public function getPayPalTsProtectionCostVat()
    {
        $dVAT = 0;
        $oCost = $this->getCosts('oxtsprotection');
        if ($oCost && $oCost->getVatValue()) {
            $dVAT = $oCost->getVatValue();
        }

        return $dVAT;
    }
}
