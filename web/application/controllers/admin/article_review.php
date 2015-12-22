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
 * Admin article review manager.
 * Collects customer review about article data. There ir possibility to update
 * review text or delete it.
 * Admin Menu: Manage Products -> Articles -> Review.
 */
class Article_Review extends oxAdminDetails
{

    /**
     * Loads selected article review information, returns name of template
     * file "article_review.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $this->_aViewData["edit"] = $oArticle = oxNew("oxarticle");

        $soxId = $this->getEditObjectId();
        $sRevoxId = oxRegistry::getConfig()->getRequestParameter('rev_oxid');
        if ($soxId != "-1" && isset($soxId)) {

            // load object
            $oArticle->load($soxId);


            $oRevs = $this->_getReviewList($oArticle);

            foreach ($oRevs as $oRev) {
                if ($oRev->oxreviews__oxid->value == $sRevoxId) {
                    $oRev->selected = 1;
                    break;
                }
            }
            $this->_aViewData["allreviews"] = $oRevs;
            $this->_aViewData["editlanguage"] = $this->_iEditLang;

            if (isset($sRevoxId)) {
                $oReview = oxNew("oxreview");
                $oReview->load($sRevoxId);
                $this->_aViewData["editreview"] = $oReview;

                $oUser = oxNew("oxuser");
                $oUser->load($oReview->oxreviews__oxuserid->value);
                $this->_aViewData["user"] = $oUser;
            }
            //show "active" checkbox if moderating is active
            $this->_aViewData["blShowActBox"] = $myConfig->getConfigParam('blGBModerate');

        }

        return "article_review.tpl";
    }

    /**
     * returns reviews list for article
     *
     * @param oxArticle $oArticle Article object
     *
     * @return oxList
     */
    protected function _getReviewList($oArticle)
    {
        $oDb = oxDb::getDb();
        $sSelect = "select oxreviews.* from oxreviews
                     where oxreviews.OXOBJECTID = " . $oDb->quote($oArticle->oxarticles__oxid->value) . "
                     and oxreviews.oxtype = 'oxarticle'";

        $aVariantList = $oArticle->getVariants();

        if ($this->getConfig()->getConfigParam('blShowVariantReviews') && count($aVariantList)) {

            // verifying rights
            foreach ($aVariantList as $oVariant) {
                $sSelect .= "or oxreviews.oxobjectid = " . $oDb->quote($oVariant->oxarticles__oxid->value) . " ";
            }

        }

        //$sSelect .= "and oxreviews.oxtext".oxRegistry::getLang()->getLanguageTag($this->_iEditLang)." != ''";
        $sSelect .= "and oxreviews.oxlang = '" . $this->_iEditLang . "'";
        $sSelect .= "and oxreviews.oxtext != '' ";

        // all reviews
        $oRevs = oxNew("oxlist");
        $oRevs->init("oxreview");
        $oRevs->selectString($sSelect);

        return $oRevs;
    }

    /**
     * Saves article review information changes.
     */
    public function save()
    {
        parent::save();

        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");
        // checkbox handling
        if ($this->getConfig()->getConfigParam('blGBModerate') && !isset($aParams['oxreviews__oxactive'])) {
            $aParams['oxreviews__oxactive'] = 0;
        }

        $oReview = oxNew("oxreview");
        $oReview->load(oxRegistry::getConfig()->getRequestParameter("rev_oxid"));
        $oReview->assign($aParams);
        $oReview->save();
    }

    /**
     * Deletes selected article review information.
     */
    public function delete()
    {
        $this->resetContentCache();

        $sRevoxId = oxRegistry::getConfig()->getRequestParameter("rev_oxid");
        $oReview = oxNew("oxreview");
        $oReview->load($sRevoxId);
        $oReview->delete();

        // recalculating article average rating
        $oRating = oxNew("oxRating");
        $sArticleId = $this->getEditObjectId();

        $oArticle = oxNew('oxArticle');
        $oArticle->load($sArticleId);

        $oArticle->setRatingAverage($oRating->getRatingAverage($sArticleId, 'oxarticle'));
        $oArticle->setRatingCount($oRating->getRatingCount($sArticleId, 'oxarticle'));
        $oArticle->save();

    }
}
