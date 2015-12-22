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

    oxCenterElementOnHover = {

        _create: function(){

            var self = this;
            var el   = self.element;

             el.hover(function(){
                  var targetObj = $(".viewAllHover", el);
                  var targetObjWidth = targetObj.outerWidth() / 2;
                  var parentObjWidth = el.width() / 2;

                  targetObj.css("left", parentObjWidth - targetObjWidth + "px");
                  targetObj.show();
              }, function(){
                  $(".viewAllHover", el).hide();
              });
        }
    }

    $.widget( "ui.oxCenterElementOnHover", oxCenterElementOnHover );

} )( jQuery );
