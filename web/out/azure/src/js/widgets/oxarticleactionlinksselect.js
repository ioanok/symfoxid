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
    /**
     * Details article action links selector
     */
    oxArticleActionLinksSelect = {

        _create: function()
        {
            var self = this,
                options = self.options,
                el      = self.element;

            var targetWidth  = $("span", el).width();
            var linkboxWidth = self.getLinkboxWidth( targetWidth, $("span", el));
            var targetHeight = $("span", el).height();

            $("ul.actionLinks").css({
                "top": el.position().top - 7,
                "left": el.position().left - 10,
                "padding-top": targetHeight + 10,
                "width": linkboxWidth + 50
            });

            var arrowSrc = $(".selector img").attr("src");
            var arrow = $("#productLinks").children("img");

            $("#productLinks").css({
                "top": el.position().top - 3,
                "left": targetWidth + el.position().left + 10
            }).click(function(){
                if ( $(this).hasClass("selected") ) {
                    self.hideLinks(arrow, arrowSrc);
                } else {
                    self.showLinks(arrow);
                }
                return false;
            });

            $("#productLinks").mouseenter(function() {
                if (! $(this).hasClass("selected") ) {
                    self.showLinks(arrow);
                }
                return false;
            });

            $("ul.actionLinks").mouseleave( function() {
                self.hideLinks(arrow, arrowSrc);
                return false;
            });

            //if user comes first time to details shows action links box
            //and sets to cookie, not to show it later
            if( !$.cookie("showlinksonce") ) {
                $("ul.actionLinks").slideDown('normal').delay(2000).slideUp('normal', function(){
                  });
                $.cookie("showlinksonce", 1, { path: '/' });
            }

            $('select[id^=sellist]').change (function() {
                var oSelf = $(this);
                var oNoticeList = $('#linkToNoticeList');
                if ( oNoticeList ) {
                    oNoticeList.attr('href', oNoticeList.attr('href') + "&" + oSelf.attr('name') + "&" + oSelf.val());
                }
                var oWishList = $('#linkToWishList');
                if ( oWishList ) {
                    oWishList.attr('href', oWishList.attr('href') + "&" + oSelf.attr('name') + "&" + oSelf.val());
                }
            });

            //open price alart tab if pricealert link is clicked
            $("#priceAlarmLink").click(function() {
                $('div.tabbedWidgetBox').tabs('select', '#pricealarm');
                return false;
            });
        },

        /**
         * Shows action links list box in details
         *
         * @param object arrow img object
         *
         * @return null
         */
        showLinks : function( arrow )
        {
            var arrowSrc = arrow.attr("src");
            $("ul.actionLinks").slideDown("normal", function(){
                arrow.attr("src", arrowSrc.replace('selectbutton.png', 'selectbutton-on.png'));
                $('#productLinks').toggleClass("selected");
            });
        },

        /**
         * Hides action links list box in details
         *
         * @param object arrow    img object of selector
         * @param object arrowSrc img object of product links
         *
         * @return null
         */
        hideLinks : function( arrow, arrowSrc )
        {
            $("ul.actionLinks").animate({
                height: 0,
                opacity: 0.1
            }, 300, function(){
                $("ul.actionLinks").hide().css({
                    height: 'auto',
                    opacity: '1'
                });
                arrow.attr("src", arrowSrc);
                $('#productLinks').toggleClass("selected");
            });
        },

        /**
         * Shows action links list box in details
         *
         * @param integer iTargetWidth terget width
         * @param object oObject      object to set width
         *
         * @return integer
         */
        getLinkboxWidth : function( iTargetWidth, oObject )
        {
            if (iTargetWidth > 220) {
                return oObject.width();
            } else {
                return 220;
            }
        }
    }

    $.widget( "ui.oxArticleActionLinksSelect", oxArticleActionLinksSelect );

} )( jQuery );