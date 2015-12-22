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
 * Category tree widget.
 * Forms category tree.
 */
class oxwCategoryTree extends oxWidget
{

    /**
     * Names of components (classes) that are initiated and executed
     * before any other regular operation.
     * Cartegory component used in template.
     *
     * @var array
     */
    protected $_aComponentNames = array('oxcmp_categories' => 1);

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/sidebar/categorytree.tpl';

    /**
     * Executes parent::render(), assigns template name and returns it
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        if ($sTpl = $this->getViewParameter("sWidgetType")) {
            $sTemplateName = 'widget/' . basename($sTpl) . '/categorylist.tpl';
            if ($this->getConfig()->getTemplatePath($sTemplateName, $this->isAdmin())) {
                $this->_sThisTemplate = $sTemplateName;
            }
        }

        return $this->_sThisTemplate;
    }

    /**
     * Returns the deep level of category tree
     *
     * @return null
     */
    public function getDeepLevel()
    {
        return $this->getViewParameter("deepLevel");
    }
}
