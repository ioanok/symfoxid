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
 * Online check base request class.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 *
 * @ignore   This class will not be included in documentation.
 */
class oxOnlineRequest
{

    /**
     * OXID eShop servers cluster id.
     *
     * @var string
     */
    public $clusterId;

    /**
     * OXID eShop edition.
     *
     * @var string
     */
    public $edition;

    /**
     * Shops version number.
     *
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $shopUrl;

    /**
     * Web service protocol version.
     *
     * @var string
     */
    public $pVersion;

    /**
     * Product ID. Intended for possible partner modules in future.
     *
     * @var string
     */
    public $productId = 'eShop';

    /**
     * Class constructor, initiates public class parameters.
     */
    public function __construct()
    {
        $oConfig = oxRegistry::getConfig();
        $this->clusterId = $this->_getClusterId();
        $this->edition = $oConfig->getEdition();
        $this->version = $oConfig->getVersion();
        $this->shopUrl = $oConfig->getShopUrl();
    }

    /**
     * Returns cluster id.
     * Takes cluster id from configuration if set, otherwise generates it.
     *
     * @return string
     */
    private function _getClusterId()
    {
        $oConfig = oxRegistry::getConfig();
        $sBaseShop = $oConfig->getBaseShopId();
        $sClusterId = $oConfig->getShopConfVar('sClusterId', $sBaseShop);
        if (!$sClusterId) {
            $oUUIDGenerator = oxNew('oxUniversallyUniqueIdGenerator');
            $sClusterId = $oUUIDGenerator->generate();
            $oConfig->saveShopConfVar("str", 'sClusterId', $sClusterId, $sBaseShop);
        }

        return $sClusterId;
    }
}
