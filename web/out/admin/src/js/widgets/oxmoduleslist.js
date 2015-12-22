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

    oxModulesList = {

        _create: function() {

            var self = this,
                options = self.options,
                el      = self.element;

            $(".sortable,.sortable2").sortable({
                 opacity: 0.5,
                 update: function() {
                     $("#myedit [name=saveButton]").attr("disabled", "");
                 }
            });

            $("#myedit [name=saveButton]").click(function() {
                var aClasses = $(".sortable").sortable('toArray');

                // make array from current order
                var aModules = {};

                $.each(aClasses, function(key, elem) {
                    sIndex = "#" + elem + "_modules";
                    aModules[elem] = $(sIndex).sortable('toArray');
                });

                $("#myedit [name=aModules]").val(JSON.stringify(aModules));
                $("#myedit").submit();
            })
      }
  }

    $.widget( "ui.oxModulesList", oxModulesList );

} )( jQuery );