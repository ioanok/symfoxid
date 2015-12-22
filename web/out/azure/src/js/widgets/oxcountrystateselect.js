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

   oxCountryStateSelect = {
        options: {
            listItem        : "li",
            select          : "select",
            span            : "span",
            selectedStateId : "selectedStateId"
        },

        _create: function() {
            var self = this,
            options = self.options,
            el         = self.element;

            var stateSelect = self.getStateSelect(el);

            self.manageStateSelect(stateSelect
                    , self.getStates(el.val(), allStates, allCountryIds)
                    , self.getStatesValues(el.val(), allStateIds, allCountryIds)
                    , options.selectedStateId);

            el.change(function() {
                self.manageStateSelect(stateSelect
                    , self.getStates(el.val(), allStates, allCountryIds)
                    , self.getStatesValues(el.val(), allStateIds, allCountryIds)
                    , options.selectedStateId);
            });
        },

        /**
         * show / hide select add/remove options
         *
         * @return object
         */
        manageStateSelect: function(oSelect, aStates, aStatesValues, selectedStateId)
        {
             this.removeSelectOptions(oSelect);

             var stateSelectSpan = this.getStateSelectSpan(oSelect);

             if (aStates != null && aStates.length > 0) {
                 this.addSelectOptions(oSelect, aStatesValues, aStates, selectedStateId);
                 stateSelectSpan.parent().show();
             } else {
                 stateSelectSpan.parent().hide();
                 this.removeSelectOptions(oSelect);
             }

             return oSelect;
        },

        /**
         * get state select
         *
         * @return object
         */
        getStateSelect: function(oCountrySelect)
        {
            oOptions = this.options;
            return     $( oCountrySelect ).parent(oOptions.listItem).next(oOptions.listItem).children(oOptions.span).children(oOptions.select);
        },

        /**
         * get state select span
         *
         * @return object
         */
        getStateSelectSpan: function(oStateSelect)
        {
            oOptions = this.options;
            return     $( oStateSelect ).parent(oOptions.span);
        },

        /**
         * add options
         *
         * @return object
         */
        addSelectOptions: function(oSelect, aValues, aLables, selectedStateId)
        {
            for(var x = 0; x < aValues.length; x++) {
                if (selectedStateId == aValues[x]) {
                    oSelect.
                    append($('<option selected="selected"></option>').
                    attr("value",aValues[x]).
                    text(aLables[x]));
                } else {
                    oSelect.
                    append($("<option></option>").
                    attr("value",aValues[x]).
                    text(aLables[x]));
                }
            }
            return oSelect;
        },

        /**
         * remove all select options except first list promt string
         *
         * @return object
         */
        removeSelectOptions: function(oSelect)
        {
            oSelect.find('option[value!=""]').remove().end();
            return oSelect;
        },

        /**
         * get Country state names
         *
         * @return array
         */
        getStates: function(sCountry, allStates, allCountryIds)
        {
            return allStates[allCountryIds[sCountry]];
        },

        /**
         * get Country state ids
         *
         * @return array
         */
        getStatesValues: function(sCountry, allStatesIds, allCountryIds)
        {
            return allStatesIds[allCountryIds[sCountry]];
        }

    };

    $.widget("ui.oxCountryStateSelect", oxCountryStateSelect );

} )( jQuery );