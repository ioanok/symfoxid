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
     * Show password field if email will be changed
     */
    oxEnterPassword = {
        options: {
            metodEnterPasswd      : "oxValidate_enterPass"
        },

        _create: function()
        {
            var self    = this,
            options = self.options,
            el      = self.element;

            el.bind ( "keyup", function() {
                self.showInput( el, el.val() != el.prop( "defaultValue" ), options.metodEnterPasswd );
            });
        },

        /**
         * Shows/hides given element
         */
        showInput: function( oSource, blShow, sClass )
        {
            var oRegexp  = new RegExp( sClass + "Target\\[(.+)\\]", "g" );
            var sClasses = oRegexp.exec( oSource.attr( "class" ) );
            if ( sClasses && sClasses.length ) {
                var aClasses = sClasses[1].split(",");

                for (var i = 0; i < aClasses.length; i++) {
                    if (blShow) {
                        $("." + aClasses[i]).show();
                    }
                    else {
                        $("." + aClasses[i]).hide();
                    }
                }
            }
        }

    }

    $.widget( "ui.oxEnterPassword", oxEnterPassword );

} )( jQuery );