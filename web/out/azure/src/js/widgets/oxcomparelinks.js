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
    oxCompareLinks = {

        /**
         * Update compare links, hide add link and show remove link.
         *
         * @param id
         * @param state 1=hide add, 0=hide remove
         *
         * @return void
         */
        updateLinks: function(list)
        {
            if (list) {
                $.each(list, function(id) {
                    $("a.compare.add[data-aid='"+id+"']").css('display','none');
                    $("a.compare.remove[data-aid='"+id+"']").css('display','block');
                });
            }
        }
    };

    /**
     * CompareLinks widget
     */
    $.widget("ui.oxCompareLinks", oxCompareLinks );

})( jQuery );
