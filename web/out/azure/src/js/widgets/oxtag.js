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

    oxTag = {

         highTag : function() {

            var oSelf = $(this);

            $("p.tagError").hide();

            oxAjax.ajax(
                $("#tagsForm"),
                {//targetEl, onSuccess, onError, additionalData
                    'targetEl' : $("#tags"),
                    'additionalData' : {'highTags' : oSelf.prev().text(), 'blAjax' : '1'},
                    'onSuccess' : function(response, params) {
                        oSelf.prev().addClass('taggedText');
                        oSelf.hide();
                    }
                }
            );
            return false;
        },

        saveTag : function() {
            $("p.tagError").hide();

            oxAjax.ajax(
                $("#tagsForm"),
                {//targetEl, onSuccess, onError, additionalData
                    'targetEl' : $("#tags"),
                    'additionalData' : {'blAjax' : '1'},
                    'onSuccess' : function(response, params) {
                        response = JSON.parse(response);
                        if ( response.tags.length > 0 ) {
                            $(".tagCloud").append("<span class='taggedText'>, " + response.tags + "</span> ");
                        }
                        if ( response.invalid.length > 0 ) {
                            var tagError = $("p.tagError.invalid").show();
                            $("span", tagError).text( response.invalid );
                        }
                        if ( response.inlist.length > 0 ) {
                            var tagError = $("p.tagError.inlist").show();
                            $("span", tagError).text( response.inlist );
                        }
                    }
                }
            );
            return false;
        },

        cancelTag : function () {
            oxAjax.ajax(
                $("#tagsForm"),
                {//targetEl, onSuccess, onError, additionalData
                    'targetEl' : $("#tags"),
                    'additionalData' : {'blAjax' : '1', 'fnc' : 'cancelTags'},
                    'onSuccess' : function(response, params) {
                        if ( response ) {
                            $('#tags').html(response);
                            $("#tags #editTag").click(oxTag.editTag);
                        }
                    }
                }
            );
            return false;
        },

        editTag : function() {

            oxAjax.ajax(
                $("#tagsForm"),
                { //targetEl, onSuccess, onError, additionalData
                    'targetEl' : $("#tags"),
                    'additionalData' : {'blAjax' : '1'},
                    'onSuccess' : function(response, params) {

                        if ( response ) {
                            $('#tags').html(response);
                            $("#tags .tagText").click(oxTag.highTag);
                            $('#tags #saveTag').click(oxTag.saveTag);
                            $('#tags #cancelTag').click(oxTag.cancelTag);
                        }
                    }
                }
            );

            return false;
        }
    }

    $.widget("ui.oxTag", oxTag );

})( jQuery );