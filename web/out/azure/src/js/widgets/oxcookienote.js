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
     * Cookie note handler
     */
    oxCookieNote = {
        options: {
            closeButton : ".dismiss"
        },
        /**
         * Enable cookie note dismiss
         *
         * @return false
         */
        _create: function() {
            var self = this;

            $.cookie('cookiesEnabledCheck', 'yes');

            if ($.cookie('cookiesEnabledCheck')) {
                $.cookie('cookiesEnabledCheck', null, -1);

                if( !$.cookie("displayedCookiesNotification") ) {
                    $.cookie("displayedCookiesNotification", 1, { path: '/', expires: 30 });
                    $('#cookieNote').show();

                    // need to add this even only if we decide to show cookie note
                    $(self.options.closeButton, self.element).click(
                        function(){
                            self.element.fadeOut('slow').remove();
                            return false;
                        }
                    );
                } else {
                    self.element.remove();
                    return false;
                }
            }
        }
    };

    /**
     * CookieNote widget
     */
    $.widget("ui.oxCookieNote", oxCookieNote );

})( jQuery );