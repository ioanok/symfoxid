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

    oxInnerLabel = {

        options: {
                sDefaultValue  : 'innerLabel',
                sReloadElement : ''
        },

        _create: function(){

            var self = this,
                options = self.options,
                input = self.element,
                label = $("label[for='"+input.attr('id')+"']");

            self._reload( input, label );

            input.focus(function() {
                label.hide();
            });

            input.blur(function() {
                if ( $.trim(input.val()) == ''){
                    label.show();
                }
            });

            if ($.trim(input.val()) != '') {
                label.hide();
            }
            input.delay(500).queue(function(){
                if ($.trim(input.val()) != '') {
                    label.hide();
                }
            });

            $(options.sReloadElement).click(function() {
                setTimeout(function(){ self._reload( self.element, label ); }, 100);
            });
       },
       
       _reload : function( input, label ){
           var pos = input.position();
           label.css( { "left": (pos.left) + "px", "top":(pos.top) + "px" } );
       }
    }

    $.widget( "ui.oxInnerLabel", oxInnerLabel );

} )( jQuery );