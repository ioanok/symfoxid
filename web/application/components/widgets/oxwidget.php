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
 * Widget parent.
 * Gather functionality needed for all widgets but not for other views.
 */
class oxWidget extends oxUBase
{

    /**
     * Names of components (classes) that are initiated and executed
     * before any other regular operation.
     * Widget should rewrite and use only those which  it needs.
     *
     * @var array
     */
    protected $_aComponentNames = array();

    /**
     * If active load components
     * Widgets loads active view components
     *
     * @var array
     */
    protected $_blLoadComponents = false;

    /**
     * Sets self::$_aCollectedComponentNames to null, as views and widgets
     * controllers loads different components and calls parent::init()
     */
    public function init()
    {
        self::$_aCollectedComponentNames = null;

        if (!empty($this->_aComponentNames)) {
            foreach ($this->_aComponentNames as $sComponentName => $sCompCache) {
                $oActTopView = $this->getConfig()->getTopActiveView();
                if ($oActTopView) {
                    $this->_oaComponents[$sComponentName] = $oActTopView->getComponent($sComponentName);
                    if (!isset($this->_oaComponents[$sComponentName])) {
                        $this->_blLoadComponents = true;
                        break;
                    } else {
                        $this->_oaComponents[$sComponentName]->setParent($this);
                    }
                }
            }
        }

        parent::init();

    }

    /**
     * In widgets we do not need to parse seo and do any work related to that
     * Shop main control is responsible for that, and that has to be done once
     */
    protected function _processRequest()
    {
    }
}
