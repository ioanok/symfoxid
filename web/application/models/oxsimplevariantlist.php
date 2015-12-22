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
 * Simple variant list.
 *
 */
class oxSimpleVariantList extends oxList
{

    /**
     * Parent article for list variants
     */
    protected $_oParent = null;

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'oxsimplevariant';

    /**
     * Sets parent variant
     *
     * @param oxArticle $oParent Parent article
     */
    public function setParent($oParent)
    {
        $this->_oParent = $oParent;
    }

    /**
     * Sets parent for variant. This method is invoked for each element in oxList::assign() loop.
     *
     * @param oxSimleVariant $oListObject Simple variant
     * @param array          $aDbFields   Array of available
     */
    protected function _assignElement($oListObject, $aDbFields)
    {
        $oListObject->setParent($this->_oParent);
        parent::_assignElement($oListObject, $aDbFields);
    }
}
