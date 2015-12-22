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

var oxShopSelect = (function($) {

  return {
    options: {
      'width': '165px',
      'disable_search_threshold': 9,
      'no_results_text': '-'
    },
    init: function() {
      var oSelectShop = $('#selectshop');
      oSelectShop.chosen(oxShopSelect.options);
    }
  };

})(jQuery);

jQuery.noConflict();

jQuery(document).ready(function() {
  oxShopSelect.init();
});
