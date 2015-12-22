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
( function( $ ) {

    oxZoomPictures = {

        options: {
            sMorePicsContainerId     : "#morePicsContainer",
            sMoreZoomPicsContainerId : "#moreZoomPicsContainer",
            sZoomImgId               : "#zoomImg",
            sZoomTriggerButtonId     : "#zoomTrigger"
        },

        _create: function() {

            var self    = this,
                options = self.options,
                el      = self.element;

            $("li a", el).click(function() {
                $(options.sZoomImgId).attr("src", $(this).attr("href"));

                return false;
            });

             // adding click event for zoom button
             $(options.sZoomTriggerButtonId).click(function() {
                 self._beforeShow();
             } );

        },

        /*
         * Checking which picture was selected in product details view
         * and zooming this selected picture
         */
        _beforeShow: function() {
            var self    = this,
                options = self.options,
                el      = self.element;

            iIndex = $(options.sMorePicsContainerId + " li a.selected").parent().index();
            $(options.sMoreZoomPicsContainerId).oxMorePictures({iDefaultIndex: iIndex});
        }
    }

    $.widget( "ui.oxZoomPictures", oxZoomPictures );

} )( jQuery );


