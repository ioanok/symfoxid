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
 * PayPal oxArticle class
 */
class oePayPalOxArticle extends oePayPalOxArticle_parent
{
    /**
     * Check if Product is virtual: is non material and is downloadable
     *
     * @return bool
     */
    public function isVirtualPayPalArticle()
    {
        $blVirtual = true;

        // non material products
        if (!$this->oxarticles__oxnonmaterial->value) {
            $blVirtual = false;
        } elseif (isset($this->oxarticles__oxisdownloadable) &&
                  !$this->oxarticles__oxisdownloadable->value
        ) {
            $blVirtual = false;
        }

        return $blVirtual;
    }

    /**
     * Gets stock amount for article
     *
     * @return float
     */
    public function getStockAmount()
    {
        return $this->oxarticles__oxstock->value;
    }
}
