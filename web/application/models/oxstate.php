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
 * State handler
 */
class oxState extends oxI18n
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxstate';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init("oxstates");
    }

    /**
     * Returns country id by code
     *
     * @param string $sCode      country code
     * @param string $sCountryId country id
     *
     * @return string
     */
    public function getIdByCode($sCode, $sCountryId)
    {
        $oDb = oxDb::getDb();

        return $oDb->getOne(
            "SELECT oxid FROM oxstates WHERE oxisoalpha2 = " . $oDb->quote(
                $sCode
            ) . " AND oxcountryid = " . $oDb->quote($sCountryId)
        );
    }

    /**
     * Get state title by id
     *
     * @param integer|string $iStateId
     *
     * @return string
     */
    public function getTitleById($iStateId)
    {
        $oDb = oxDb::getDb();
        $sQ = "SELECT oxtitle FROM " . getViewName("oxstates") . " WHERE oxid = " . $oDb->quote($iStateId);
        $sStateTitle = $oDb->getOne($sQ);

        return (string) $sStateTitle;
    }
}
