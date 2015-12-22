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
 * Category seo config class
 */
class Category_Seo extends Object_Seo
{

    /**
     * Updating showsuffix field
     *
     * @return null
     */
    public function save()
    {
        $sOxid = $this->getEditObjectId();
        $oCategory = oxNew('oxCategory');
        if ($oCategory->load($sOxid)) {
            $blShowSuffixParameter = oxRegistry::getConfig()->getRequestParameter('blShowSuffix');
            $sShowSuffixField = 'oxcategories__oxshowsuffix';
            $oCategory->$sShowSuffixField = new oxField((int) $blShowSuffixParameter);
            $oCategory->save();

            $this->_getEncoder()->markRelatedAsExpired($oCategory);
        }

        return parent::save();
    }

    /**
     * Returns current object type seo encoder object
     *
     * @return oxSeoEncoderCategory
     */
    protected function _getEncoder()
    {
        return oxRegistry::get("oxSeoEncoderCategory");
    }

    /**
     * This SEO object supports suffixes so return TRUE
     *
     * @return bool
     */
    public function isSuffixSupported()
    {
        return true;
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxcategory';
    }

    /**
     * Returns true if SEO object id has suffix enabled
     *
     * @return bool
     */
    public function isEntrySuffixed()
    {
        $oCategory = oxNew('oxcategory');
        if ($oCategory->load($this->getEditObjectId())) {
            return (bool) $oCategory->oxcategories__oxshowsuffix->value;
        }
    }

    /**
     * Returns seo uri
     *
     * @return string
     */
    public function getEntryUri()
    {
        $oCategory = oxNew('oxcategory');
        if ($oCategory->load($this->getEditObjectId())) {
            return $this->_getEncoder()->getCategoryUri($oCategory, $this->getEditLang());
        }
    }
}
