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
 * Manages product assignment to category.
 */
class oxObject2Category extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxobject2category';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()) and sets table name.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxobject2category');
    }


    /**
     * Returns assigned product id
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->oxobject2category__oxobjectid->value;
    }

    /**
     * Sets assigned product id
     *
     * @param string $sId assigned product id
     */
    public function setProductId($sId)
    {
        $this->oxobject2category__oxobjectid = new oxField($sId);
    }

    /**
     * Returns assigned category id
     *
     * @return string
     */
    public function getCategoryId()
    {
        return $this->oxobject2category__oxcatnid->value;
    }

    /**
     * Sets assigned category id
     *
     * @param string $sId assigned category id
     */
    public function setCategoryId($sId)
    {
        $this->oxobject2category__oxcatnid = new oxField($sId);
    }
}
