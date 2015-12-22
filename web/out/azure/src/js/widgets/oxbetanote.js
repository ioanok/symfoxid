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
( function ( $ ) {

    /**
     * Beta note handler
     */
    oxBetaNote = {
        options: {
            cookieName  : "hideBetaNote",
            closeButton : ".dismiss"
        },

        /**
         * Enable beta note dismiss and set cookie to keep it hidden on next pages
         *
         * @return integer
         */
        _create: function() {
            
            var self = this;
            $(self.options.closeButton, self.element).click(
                function(){
                    self.element.fadeOut('slow').remove();
                    $.cookie(self.options.cookieName,1,{path: '/'});
                    
                    if(  $('#cookieNote:visible') ) {
                        $('#cookieNote').animate({ "top": "-=40px" }, 500);
                    }
                    
                    return false;
                }
            );
            
            if( !$.cookie("hideBetaNote") ) {
                $('#betaNote').show();
            } 
            
            if(  $('#cookieNote:visible') ) {
                $('#cookieNote').css('top', '40px');
            }
            
        }
    };

    /**
     * BetaNote widget
     */
    $.widget("ui.oxBetaNote", oxBetaNote );

})( jQuery );
