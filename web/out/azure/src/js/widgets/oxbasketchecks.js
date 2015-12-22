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

    oxBasketChecks = {

        _create: function(){

            var self = this,
                options = self.options,
                el      = self.element;

            el.click(function(){
                if(el.is('input')){
                    self.toggleChecks( el.prop('checked') );
                    return true;
                } else {
                    self.toggleChecks( self.toggleMainCheck() );
                    return false;
                }
            });
        },

        toggleChecks : function( blChecked ){
            $( ".basketitems .checkbox input" ).prop( "checked", blChecked );
        },

        toggleMainCheck : function(){
            if ( $( "#checkAll" ).prop( "checked" ) ) {
                $( "#checkAll" ).prop( "checked", false );
                return false;
            } else {
                $( "#checkAll" ).prop( "checked", true );
                return true;
            }
        }
    }

    $.widget( "ui.oxBasketChecks", oxBasketChecks );

} )( jQuery );