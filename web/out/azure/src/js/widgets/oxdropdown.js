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

    oxDropDown = {

        options: {
            sSubmitActionClass  : 'js-fnSubmit',
            sLinkActionClass    : 'js-fnLink',
            sDisabledClass      : 'js-disabled'
        },

         _create: function(){

            var self = this,
                options = self.options;

            this.head               = this.element;
            this.oxDropDown         = this.head.parent( 'div' );
            this.valueList          = this.oxDropDown.children( 'ul' );
            this.selectedValueLabel = $( 'p span', this.oxDropDown );
            this.selectedValue      = $( 'input', this.oxDropDown );
            this.blSubmit           = this.oxDropDown.hasClass( options.sSubmitActionClass );
            this.blLink             = this.oxDropDown.hasClass( options.sLinkActionClass );
            this.actionForm         = this.oxDropDown.closest( 'form' );

            // clicking on drop down header
            this.head.click(function() {
                self.toggleDropDown();
                return false;
            });

            // selecting value
            $( 'a', this.valueList ).click( function (){
                self.select( $(this) );
                self.hideDropDown();
                return self.action();
            });

            // clicking else where
            $( document ).click( function(){
                self.hideAll();
            });
        },

        /**
         * execute action after select: do nothing, submit, go link
         *
         * @return boolean
         */
        action : function(){

            // on submit
            if( this.blSubmit ){
                this.actionForm.submit();
                return false;
            }

            // on link
            if( this.blLink ){
               return true;
            }

            // just setting
            return false;
        },

        /**
         * set selected value
         *
         * @return null
         */
        select : function( oSelectLink ) {
            this.selectedValue.val( oSelectLink.attr('data-selection-id') );
            this.selectedValueLabel.text( oSelectLink.text() );
            $('a', this.valueList).removeClass('selected');
            oSelectLink.addClass('selected');
        },

        /**
         * toggle oxDropDown
         *
         * @return null
         */
        toggleDropDown : function() {
            if ( !this.isDisabled() ) {
                if (this.valueList.is(':visible')) {
                    this.hideDropDown();
                }
                else {
                    this.showDropDown();
                }
            }
        },

        /**
         * show value list
         *
         * @return null
         */
        showDropDown : function (){

           this.hideAll();

           //adding additional <li> for default value from dropbox header
           this.valueList.prepend('<li class="value"></li>');
           this.head.clone().appendTo($("li.value", this.valueList));
           $('li.value p', this.valueList ).removeClass('underlined');
           $('li.value p', this.valueList ).css('padding-right', '5px');

           // set width
           this.valueList.css("width", this.oxDropDown.outerWidth());

           this.valueList.show();
        },

        /**
         * hide values list
         *
         * @return null
         */
        hideDropDown : function() {
            this.valueList.hide();
            $("li.value").remove();
        },

        /**
         * hide all opend oxDropDown
         *
         * @return null
         */
        hideAll : function() {
            $("li.value").remove();
            $('ul.drop').hide();
        },

        /**
         * check is dropdown disabled
         *
         * @return boolean
         */
        isDisabled : function() {
            return this.head.hasClass( this.options.sDisabledClass );
        }
    }

   $.widget("ui.oxDropDown", oxDropDown );

})( jQuery );
