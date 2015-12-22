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
    /*
     * Facebook related scripts
     */
    oxFacebook = {

        /*
         * FB widgets/buttons array
         */
        buttons: {
        },

        /*
         * Enables FB widgets
         */
        showFbWidgets: function ( sFbAppId, sLocale, sLoginUrl, sLogoutUrl ) {

            var self = this;
            self.key = null;

            for ( key in this.buttons ) {
                if ( this.buttons[key].script ) {
                    self.key = key;
                    $.getScript( this.buttons[key].script, function () {
                        $( self.key ).html( unescape( self.buttons[self.key].html ) );
                    } );
                } else {
                    $( key ).html( unescape( this.buttons[key].html ) );
                }
            }

            $.cookie( 'fbwidgetson',1, {path: '/'});
            this.fbInit( sFbAppId, sLocale, sLoginUrl, sLogoutUrl );
        },

        /*
         * Initing Facebook API
         *
         */
        fbInit: function ( sFbAppId, sLocale, sLoginUrl, sLogoutUrl ) {

            window.fbAsyncInit = function() {

                FB.init({appId: sFbAppId, status: true, cookie: true, xfbml: true, oauth: true});
                FB.Event.subscribe('auth.login', function(response) {
                    // redirecting after successfull login
                    setTimeout(function(){oxFacebook.redirectPage(sLoginUrl);}, 0);

                    if ( FB.XFBML.Host !== undefined && FB.XFBML.Host.parseDomTree )
                          setTimeout(function(){FB.XFBML.Host.parseDomTree;}, 0 );
                });

                FB.Event.subscribe('auth.logout', function(response) {
                    // redirecting after logout
                    setTimeout(function(){oxFacebook.redirectPage(sLogoutUrl);}, 0);
                });
            };

            // loading FB script file
            var e   = document.createElement('script');
            e.type  = 'text/javascript';
            e.async = true;
            e.src   = document.location.protocol + '//connect.facebook.net/' + sLocale + '/all.js';
            $('#fb-root').append(e);
        },

        /*
         * Redicrecting page to given url
         */
        redirectPage: function ( sUrl ) {

           sUrl = sUrl.toString().replace(/&amp;/g,"&");
           document.location.href = sUrl;
        },

        /*
         * Add scripts from tpl
         */
        initDetailsPagePartial : function () {
            if (window.fbAsyncInit) {
                window.fbAsyncInit();
            }
        }

    };

