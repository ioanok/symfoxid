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
 * Class dedicated to article tags handling.
 * Is responsible for saving, returning and adding tags for given article.
 *
 */
class oxTagSet extends oxSuperCfg implements Iterator
{

    /**
     * Tag separator.
     *
     * @var string
     */
    protected $_sSeparator = ',';

    /**
     * oxTag objects array
     *
     * @var array
     */
    protected $_aTags = array();

    /**
     * Stores invalid tags
     *
     * @var array
     */
    protected $_aInvalidTags = array();

    /**
     * Sets tag separator
     *
     * @param string $sSeparator tags separator character
     */
    public function setSeparator($sSeparator)
    {
        $this->_sSeparator = $sSeparator;
    }

    /**
     * Returns tag separator
     *
     * @return mixed
     */
    public function getSeparator()
    {
        return $this->_sSeparator;
    }

    /**
     * Clears the list and adds specified tags
     *
     * @param string $sTags     article tag
     * @param bool   $blPrepare if false, separate tags will not be parsed and stored as is
     */
    public function set($sTags, $blPrepare = true)
    {
        $this->clear();
        $this->add($sTags, $blPrepare);
    }

    /**
     * Returns article tags set string
     *
     * @return string;
     */
    public function get()
    {
        return $this->_aTags;
    }

    /**
     * Returns article tags set string
     *
     * @return string;
     */
    public function getInvalidTags()
    {
        return $this->_aInvalidTags;
    }

    /**
     * Adds tag
     *
     * @param string $sTags     tags to add to list
     * @param bool   $blPrepare if false, separate tags will not be parsed and stored as is
     */
    public function add($sTags, $blPrepare = true)
    {
        $aTags = explode($this->getSeparator(), $sTags);
        foreach ($aTags as $sTag) {
            $this->addTag($sTag, $blPrepare);
        }
    }

    /**
     * Adds tag
     *
     * @param mixed $mTag      tag as a string or as oxTag object
     * @param bool  $blPrepare if false, tag will not be parsed and stored as is
     *
     * @return bool
     */
    public function addTag($mTag, $blPrepare = true)
    {
        $oTag = $this->_formTag($mTag, $blPrepare);
        $sTagName = $oTag->get();
        if (!$oTag->isValid()) {
            if ($sTagName !== "") {
                $this->_aInvalidTags[$sTagName] = $oTag;
            }

            return false;
        }
        if ($this->_aTags[$sTagName] === null) {
            $this->_aTags[$sTagName] = $oTag;
        } else {
            $this->_aTags[$sTagName]->increaseHitCount();
        }

        return true;
    }

    /**
     * Clears tags set
     */
    public function clear()
    {
        $this->_aTags = array();
    }

    /**
     * Returns formed string of tags
     *
     * @return string
     */
    public function formString()
    {
        $aTags = array();
        foreach ($this->get() as $oTag) {
            $aTags = array_merge($aTags, array_fill(0, $oTag->getHitCount(), $oTag->get()));
        }

        return implode($this->getSeparator(), $aTags);
    }

    /**
     * Returns tag list as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->formString();
    }

    /**
     * Slices tags from the list
     *
     * @param int $offset offset
     * @param int $length length of tags set
     *
     * @return array
     */
    public function slice($offset, $length)
    {
        $this->_aTags = array_slice($this->get(), $offset, $length, true);

        return $this->_aTags;
    }

    /**
     * Sorts current tag set
     */
    public function sort()
    {
        $oStr = getStr();
        uksort($this->_aTags, array($oStr, 'strrcmp'));
    }

    /**
     * Sorts current tag set
     */
    public function sortByHitCount()
    {
        uasort($this->_aTags, array($this, '_tagHitsCmp'));
    }

    /**
     * Return the current element
     *
     * @return oxTag
     */
    public function current()
    {
        return current($this->_aTags);
    }

    /**
     * Return the key of the current element
     */
    public function next()
    {
        next($this->_aTags);
    }

    /**
     * Return the key of the current element
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_aTags);
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->_aTags[$this->key()]);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        reset($this->_aTags);
    }

    /**
     * Forms and returns tag
     *
     * @param mixed $mTag      tag as a string or as oxTag object
     * @param bool  $blPrepare if false, tag will not be parsed and stored as is
     *
     * @return oxTag
     */
    protected function _formTag($mTag, $blPrepare = true)
    {
        if ($mTag instanceof oxTag) {
            $oTag = $mTag;
        } else {
            $oTag = oxNew("oxTag");
            $oTag->set($mTag, $blPrepare);
        }

        return $oTag;
    }

    /**
     * Compares two tags by hit count
     *
     * @param oxTag $oTag1 tag to compare
     * @param oxTag $oTag2 tag to compare
     *
     * @return int < 0 if tag1 is less than tag2; > 0 if tag1 is greater than tag2, and 0 if they are equal.
     */
    protected function _tagHitsCmp($oTag1, $oTag2)
    {
        return $oTag2->getHitCount() - $oTag1->getHitCount();
    }
}
