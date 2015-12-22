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
 * Manages application servers information.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 */
class oxServersManager
{

    /**
     * Time in seconds, server node information life time.
     */
    const NODE_AVAILABILITY_CHECK_PERIOD = 86400;

    /**
     * Time in seconds, server node information life time.
     */
    const INACTIVE_NODE_STORAGE_PERIOD = 259200;

    /**
     * Servers data array.
     *
     * @var array
     */
    private $_aServersData = array();

    /**
     * Initiates servers array with content from configuration.
     */
    public function __construct()
    {
        $this->_aServersData = (array) oxRegistry::getConfig()->getConfigParam('aServersData');
    }

    /**
     * Returns server based on server id.
     *
     * @param string $sServerId
     *
     * @return oxApplicationServer
     */
    public function getServer($sServerId)
    {
        $aServerData = $this->_getServerData($sServerId);

        return $this->_createServer($sServerId, $aServerData);
    }

    /**
     * Saves given server information to config.
     *
     * @param oxApplicationServer $oServer
     */
    public function saveServer($oServer)
    {
        $aServersData = $this->_getServersData();
        $aServersData[$oServer->getId()] = array(
            'id'                => $oServer->getId(),
            'timestamp'         => $oServer->getTimestamp(),
            'ip'                => $oServer->getIp(),
            'lastFrontendUsage' => $oServer->getLastFrontendUsage(),
            'lastAdminUsage'    => $oServer->getLastAdminUsage(),
            'isValid'           => $oServer->isValid()
        );

        $this->_save($aServersData);
    }

    /**
     * Returns servers information array.
     *
     * @return array
     */
    protected function _getServersData()
    {
        return $this->_aServersData;
    }

    /**
     * Returns server information from configuration.
     *
     * @param string $sServerId
     *
     * @return array
     */
    protected function _getServerData($sServerId)
    {
        $aServers = $this->_getServersData();

        return array_key_exists($sServerId, $aServers) ? $aServers[$sServerId] : array();
    }

    /**
     * Creates oxApplicationServer from given server id and data.
     *
     * @param string $sServerId
     * @param array  $aData
     *
     * @return oxApplicationServer
     */
    protected function _createServer($sServerId, $aData = array())
    {
        /** @var oxApplicationServer $oAppServer */
        $oAppServer = oxNew('oxApplicationServer');

        $oAppServer->setId($sServerId);
        $oAppServer->setTimestamp($this->_getServerParameter($aData, 'timestamp'));
        $oAppServer->setIp($this->_getServerParameter($aData, 'serverIp'));
        $oAppServer->setLastFrontendUsage($this->_getServerParameter($aData, 'lastFrontendUsage'));
        $oAppServer->setLastAdminUsage($this->_getServerParameter($aData, 'lastAdminUsage'));
        $oAppServer->setIsValid($this->_getServerParameter($aData, 'isValid'));

        return $oAppServer;
    }

    /**
     * Gets server parameter.
     *
     * @param array  $aData Data
     * @param string $sName Name
     *
     * @return mixed
     */
    protected function _getServerParameter($aData, $sName)
    {
        return array_key_exists($sName, $aData) ? $aData[$sName] : null;
    }

    /**
     * Return active server nodes
     *
     * @return array
     */
    public function getServers()
    {
        $this->markInActiveServers();
        $this->deleteInActiveServers();

        $aServers = $this->_getServersData();
        $aValidServers = array();

        foreach ($aServers as $aServer) {
            if ($aServer['isValid']) {
                unset($aServer['isValid']);
                unset($aServer['timestamp']);
                $aValidServers[] = $aServer;
            }
        }

        return $aValidServers;
    }

    /**
     * Removes server node information
     *
     * @param string $sServerId Server id
     */
    public function deleteServer($sServerId)
    {
        $aServersData = $this->_getServersData();
        unset($aServersData[$sServerId]);
        $this->_save($aServersData);
    }

    /**
     * Mark servers as inactive if they are not used anymore
     */
    public function markInActiveServers()
    {
        $aServersData = $this->_getServersData();

        foreach ($aServersData as $sServerId => $aServerData) {
            if ($aServerData['timestamp'] < oxRegistry::get("oxUtilsDate")->getTime() - self::NODE_AVAILABILITY_CHECK_PERIOD) {
                $oServer = $this->getServer($sServerId);
                $oServer->setIsValid(false);
                $this->saveServer($oServer);
            }
        }
    }

    /**
     * Removes information about old and not used servers
     */
    public function deleteInActiveServers()
    {
        $aServersData = $this->_getServersData();

        foreach ($aServersData as $sServerId => $aServerData) {
            if ($aServerData['timestamp'] < oxRegistry::get("oxUtilsDate")->getTime() - self::INACTIVE_NODE_STORAGE_PERIOD) {
                $this->deleteServer($sServerId);
            }
        }
    }

    /**
     * Saves servers data.
     *
     * @param array $aServersData Servers data
     */
    protected function _save($aServersData)
    {
        oxRegistry::getConfig()->saveSystemConfigParameter('arr', 'aServersData', $aServersData);
        $this->_aServersData = $aServersData;
    }
}
