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
 * Beta note widget
 */
class oxwBetaNote extends oxWidget
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/header/betanote.tpl';

    /**
     * Beta Note link value. Has default value
     *
     * @var string
     */
    protected $_sBetaNoteLink = 'http://wiki.oxidforge.org/Development/Beta';

    /**
     * Gets beta note link
     *
     * @return string
     */
    public function getBetaNoteLink()
    {
        return $this->_sBetaNoteLink;
    }

    /**
     * Sets beta note link
     *
     * @param string $sLink link to set
     */
    public function setBetaNoteLink($sLink)
    {
        $this->_sBetaNoteLink = $sLink;
    }
}
