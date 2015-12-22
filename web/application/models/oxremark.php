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
 * Remark manager.
 *
 */
class oxRemark extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxremark';

    /**
     * Skip update fields
     *
     * @var array
     */
    protected $_aSkipSaveFields = array('oxtimestamp');

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxremark');
    }

    /**
     * Loads object information from DB. Returns true on success.
     *
     * @param string $oxID ID of object to load
     *
     * @return bool
     */
    public function load($oxID)
    {
        if ($blRet = parent::load($oxID)) {
            // convert date's to international format
            $this->oxremark__oxcreate = new oxField(oxRegistry::get("oxUtilsDate")->formatDBDate($this->oxremark__oxcreate->value), oxField::T_RAW);
        }

        return $blRet;
    }

    /**
     * Inserts object data fields in DB. Returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {
        // set oxcreate
        $sNow = date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime());
        $this->oxremark__oxcreate = new oxField($sNow, oxField::T_RAW);
        $this->oxremark__oxheader = new oxField($sNow, oxField::T_RAW);

        return parent::_insert();
    }
}
