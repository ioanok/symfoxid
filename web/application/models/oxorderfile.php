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
 * Article file link manager.
 *
 */
class oxOrderFile extends oxBase
{

    /**
     * Object core table name
     *
     * @var string
     */
    protected $_sCoreTable = 'oxorderfiles';

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxorderfile';


    /**
     * Initialises the instance
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxorderfiles');
    }

    /**
     * reset order files downloadcount and / or expration times
     */
    public function reset()
    {
        $oArticleFile = oxNew('oxFile');
        $oArticleFile->load($this->oxorderfiles__oxfileid->value);
        if (file_exists($oArticleFile->getStoreLocation())) {
            $this->oxorderfiles__oxdownloadcount = new oxField(0);
            $this->oxorderfiles__oxfirstdownload = new oxField('0000-00-00 00:00:00');
            $this->oxorderfiles__oxlastdownload = new oxField('0000-00-00 00:00:00');
            $iExpirationTime = $this->oxorderfiles__oxlinkexpirationtime->value * 3600;
            $sNow = oxRegistry::get("oxUtilsDate")->getTime();
            $sDate = date('Y-m-d H:i:s', $sNow + $iExpirationTime);
            $this->oxorderfiles__oxvaliduntil = new oxField($sDate);
            $this->oxorderfiles__oxresetcount = new oxField($this->oxorderfiles__oxresetcount->value + 1);
        }
    }

    /**
     * set order id
     *
     * @param string $sOrderId - order id
     */
    public function setOrderId($sOrderId)
    {
        $this->oxorderfiles__oxorderid = new oxField($sOrderId);
    }

    /**
     * set order article id
     *
     * @param string $sOrderArticleId - order article id
     */
    public function setOrderArticleId($sOrderArticleId)
    {
        $this->oxorderfiles__oxorderarticleid = new oxField($sOrderArticleId);
    }

    /**
     * set shop id
     *
     * @param string $sShopId - shop id
     */
    public function setShopId($sShopId)
    {
        $this->oxorderfiles__oxshopid = new oxField($sShopId);
    }

    /**
     * Set file and download options
     *
     * @param string $sFileName               file name
     * @param string $sFileId                 file id
     * @param int    $iMaxDownloadCounts      max download count
     * @param int    $iExpirationTime         main download time after order in times
     * @param int    $iExpirationDownloadTime download time after first download in hours
     */
    public function setFile($sFileName, $sFileId, $iMaxDownloadCounts, $iExpirationTime, $iExpirationDownloadTime)
    {
        $sNow = oxRegistry::get("oxUtilsDate")->getTime();
        $sDate = date('Y-m-d G:i', $sNow + $iExpirationTime * 3600);

        $this->oxorderfiles__oxfileid = new oxField($sFileId);
        $this->oxorderfiles__oxfilename = new oxField($sFileName);
        $this->oxorderfiles__oxmaxdownloadcount = new oxField($iMaxDownloadCounts);
        $this->oxorderfiles__oxlinkexpirationtime = new oxField($iExpirationTime);
        $this->oxorderfiles__oxdownloadexpirationtime = new oxField($iExpirationDownloadTime);
        $this->oxorderfiles__oxvaliduntil = new oxField($sDate);
    }

    /**
     * Returns downloadable file size in bytes.
     *
     * @return int
     */
    public function getFileSize()
    {
        $oFile = oxNew("oxfile");
        $oFile->load($this->oxorderfiles__oxfileid->value);

        return $oFile->getSize();
    }

    /**
     * returns long name
     *
     * @param string $sFieldName - field name
     *
     * @return string
     */
    protected function _getFieldLongName($sFieldName)
    {
        $aFieldNames = array(
            'oxorderfiles__oxarticletitle',
            'oxorderfiles__oxarticleartnum',
            'oxorderfiles__oxordernr',
            'oxorderfiles__oxorderdate',
            'oxorderfiles__oxispaid',
            'oxorderfiles__oxpurchasedonly'
        );

        if (in_array($sFieldName, $aFieldNames)) {
            return $sFieldName;
        }

        return parent::_getFieldLongName($sFieldName);
    }

    /**
     * Checks if order file is still available to download
     *
     * @return bool
     */
    public function isValid()
    {
        if (!$this->oxorderfiles__oxmaxdownloadcount->value || ($this->oxorderfiles__oxdownloadcount->value < $this->oxorderfiles__oxmaxdownloadcount->value)) {

            if (!$this->oxorderfiles__oxlinkexpirationtime->value && !$this->oxorderfiles__oxdownloadxpirationtime->value) {
                return true;
            } else {
                $sNow = oxRegistry::get("oxUtilsDate")->getTime();
                $iTimestamp = strtotime($this->oxorderfiles__oxvaliduntil->value);
                if (!$iTimestamp || ($iTimestamp > $sNow)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * returns state payed or not the order
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->oxorderfiles__oxispaid->value;
    }

    /**
     * returns date ant time
     *
     * @return bool
     */
    public function getValidUntil()
    {
        return substr($this->oxorderfiles__oxvaliduntil->value, 0, 16);
    }

    /**
     * returns date ant time
     *
     * @return bool
     */
    public function getLeftDownloadCount()
    {
        $iLeft = $this->oxorderfiles__oxmaxdownloadcount->value - $this->oxorderfiles__oxdownloadcount->value;
        if ($iLeft < 0) {
            $iLeft = 0;
        }

        return $iLeft;
    }

    /**
     * Checks if download link is valid, changes count, if first download changes valid until
     *
     * @return bool
     */
    public function processOrderFile()
    {
        if ($this->isValid()) {
            //first download
            if (!$this->oxorderfiles__oxdownloadcount->value) {
                $this->oxorderfiles__oxdownloadcount = new oxField(1);

                $iExpirationTime = $this->oxorderfiles__oxdownloadexpirationtime->value * 3600;
                $iTime = oxRegistry::get("oxUtilsDate")->getTime();
                $this->oxorderfiles__oxvaliduntil = new oxField(date('Y-m-d H:i:s', $iTime + $iExpirationTime));

                $this->oxorderfiles__oxfirstdownload = new oxField(date('Y-m-d H:i:s', $iTime));
                $this->oxorderfiles__oxlastdownload = new oxField(date('Y-m-d H:i:s', $iTime));
            } else {
                $this->oxorderfiles__oxdownloadcount = new oxField($this->oxorderfiles__oxdownloadcount->value + 1);

                $iTime = oxRegistry::get("oxUtilsDate")->getTime();
                $this->oxorderfiles__oxlastdownload = new oxField(date('Y-m-d H:i:s', $iTime));
            }
            $this->save();

            return $this->oxorderfiles__oxfileid->value;
        }

        return false;
    }

    /**
     * Gets field id.
     *
     * @return mixed
     */
    public function getFileId()
    {
        return $this->oxorderfiles__oxfileid->value;
    }
}
