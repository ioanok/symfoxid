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

    oxTsBadge = {

        options: {
            trustedShopId : "trustedShopId"
        },

        _create: function(){

            var self = this,
                options = self.options;

            var _ts = document.createElement('script');

            _ts.type = 'text/javascript';
            _ts.async = true;
            _ts.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widgets.trustedshops.com/js/'+options.trustedShopId+'.js';

            var __ts = document.getElementsByTagName('script')[0];
            __ts.parentNode.insertBefore(_ts, __ts);

        }
    }

    $.widget( "ui.oxTsBadge", oxTsBadge );

} )( jQuery );