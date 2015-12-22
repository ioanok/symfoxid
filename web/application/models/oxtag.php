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

if (!defined('OXTAGCLOUD_MINTAGLENGTH')) {
    define('OXTAGCLOUD_MINTAGLENGTH', 4);
}
/**
 * Class dedicated to tags handling
 *
 */
class oxTag extends oxSuperCfg
{

    /**
     * Tag value
     *
     * @var string
     */
    protected $_sTag = '';

    /**
     * Forbidden tags
     */
    protected $_aForbiddenTags = array(
        'admin', 'application', 'core', 'export', 'modules', 'out', 'setup', 'tmp'
    );

    /**
     * Formatted tag link
     *
     * @var string
     */
    protected $_sTagLink = null;

    /**
     * Maximum tag's length
     * Maximum size of one tag in admin area and limits tag input field in front end
     *
     * @var int
     */
    protected $_iTagMaxLength = 60;

    /**
     * Tag frequency
     *
     * @var int
     */
    protected $_iHitCount = 1;

    /**
     * Meta characters.
     * Array of meta chars used for FULLTEXT index.
     *
     * @var array
     */
    protected $_aMetaChars = array('+', '-', '>', '<', '(', ')', '~', '*', '"', '\'', '\\', '[', ']', '{', '}', ';', ':', '.', '/', '|', '!', '@', '#', '$', '%', '^', '&', '?', '=', '`');

    /**
     * Sets tag value
     *
     * @param string $sTag tag value
     */
    public function __construct($sTag = null)
    {
        parent::__construct();
        if ($sTag !== null) {
            $this->set($sTag);
        }
    }

    /**
     * Sets maximum tag length
     *
     * @param int $iTagMaxLength Tag maximum length
     */
    public function setMaxLength($iTagMaxLength)
    {
        $this->_iTagMaxLength = $iTagMaxLength;
    }

    /**
     * Returns maximum tag length
     *
     * @return int
     */
    public function getMaxLength()
    {
        return $this->_iTagMaxLength;
    }

    /**
     * Sets tag value
     *
     * @param string $sTag      Tag value
     * @param bool   $blPrepare if false, no checks will be done when setting
     */
    public function set($sTag, $blPrepare = true)
    {
        $this->_sTag = $blPrepare ? $this->prepare($sTag) : $sTag;
        $this->setLink();
    }

    /**
     * Sets tag value
     *
     * @return string Tag value
     */
    public function get()
    {
        return $this->_sTag;
    }

    /**
     * Sets tag size value
     *
     * @param int $iHitCount size of tag
     */
    public function setHitCount($iHitCount)
    {
        $this->_iHitCount = $iHitCount;
    }

    /**
     * Returns tag size value
     *
     * @return int Tag size value
     */
    public function getHitCount()
    {
        return $this->_iHitCount;
    }

    /**
     * Increases tag size value
     */
    public function increaseHitCount()
    {
        $this->_iHitCount++;
    }

    /**
     * Checks if tag is valid
     *
     * @return bool
     */
    public function isValid()
    {
        $blValid = strlen($this->_sTag) > 0 ? true : false;
        if ($blValid && in_array($this->_sTag, $this->_aForbiddenTags)) {
            $blValid = false;
        }

        return $blValid;
    }

    /**
     * Returns tag url (seo or dynamic depends on shop mode)
     *
     * @return string
     */
    public function getLink()
    {
        if (is_null($this->_sTagLink)) {
            $this->_sTagLink = $this->formLink($this->get());
        }

        return $this->_sTagLink;
    }

    /**
     * Sets tag url. If nothing is passed, link is reset to null
     *
     * @param string $sTagLink formed tag link
     */
    public function setLink($sTagLink = null)
    {
        $this->_sTagLink = $sTagLink;
    }

    /**
     * Returns html safe tag title
     *
     * @return string
     */
    public function getTitle()
    {
        return getStr()->htmlentities($this->get());
    }

    /**
     * Renders tag
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }

    /**
     * Takes tag string, checks its length and makes longer tag shorter if needed.
     * Also trims it and removes unnecessary characters.
     *
     * @param string $sTag tag value
     *
     * @return object oxTag
     */
    public function prepare($sTag)
    {
        $sTag = $this->stripMetaChars($sTag);
        $oStr = getStr();
        $iLen = $oStr->strlen($sTag);
        if ($iLen > $this->getMaxLength()) {
            $sTag = trim($oStr->substr($sTag, 0, $this->getMaxLength()));
        }

        return $oStr->strtolower($sTag);
    }

    /**
     * Changes any mysql specific meta characters with spaces
     *
     * @param string $sText given text
     *
     * @return string
     */
    public function stripMetaChars($sText)
    {
        $oStr = getStr();

        // Remove meta chars
        $sText = str_replace($this->_aMetaChars, ' ', $sText);

        // Replace multiple spaces with single space
        $sText = $oStr->preg_replace("/\s+/", " ", trim($sText));

        return $sText;
    }

    /**
     * Returns tag url (seo or dynamic depends on shop mode)
     *
     * @param string $sTag tag
     *
     * @return string
     */
    public function formLink($sTag)
    {
        $oSeoEncoderTag = oxRegistry::get("oxSeoEncoderTag");

        $iLang = oxRegistry::getLang()->getBaseLanguage();

        $sUrl = false;
        if (oxRegistry::getUtils()->seoIsActive()) {
            $sUrl = $oSeoEncoderTag->getTagUrl($sTag, $iLang);
        }

        return $sUrl ? $sUrl : $this->getConfig()->getShopUrl() . $oSeoEncoderTag->getStdTagUri($sTag) . "&amp;lang=" . $iLang;
    }

    /**
     * Adds dashes to too short tag words, so that they would be equal to minTagLength
     */
    public function addUnderscores()
    {
        $oStr = getStr();
        $aTagParts = explode(' ', $this->get());
        foreach ($aTagParts as &$sTagPart) {
            if ($oStr->strlen($sTagPart) < OXTAGCLOUD_MINTAGLENGTH) {
                $sTagPart .= str_repeat("_", OXTAGCLOUD_MINTAGLENGTH - $oStr->strlen($sTagPart));
            }
        }
        unset($sTagPart);
        $this->set(implode(' ', $aTagParts), false);
    }


    /**
     * Removes dashes from tag words.
     */
    public function removeUnderscores()
    {
        $oStr = getStr();
        $sRes = '';
        if ($oStr->preg_match_all("/([\s\-]?)([^\s\-]+)([\s\-]?)/", $this->get(), $aMatches)) {
            foreach ($aMatches[2] as $iKey => $sMatch) {
                if ($oStr->strlen($sMatch) <= OXTAGCLOUD_MINTAGLENGTH) {
                    $sMatch = rtrim($sMatch, "_");
                }
                $sRes .= $aMatches[1][$iKey] . $sMatch . $aMatches[3][$iKey];
            }
        }
        $this->set($sRes, false);
    }
}
