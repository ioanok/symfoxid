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

/**
 * jQuery plugin for countdown
 * usage is:
 * $(SELECTOR)
 *      .countdown(
 *          function(count, element, container) {
 *              CALLBACK FUNCTION CODE
 *          }
 *      );
 * where the SELECTOR should return elements with time information in format hh:mm:ss
 * if CALLBACK function returns a jQuery container, it is replaced from the next iteration
 */
jQuery.fn.countdown = function(callback, start) {

    if(jQuery(this).length == 0 ){ return false;}
    var rs = this;
    var go = false;
    var dt = new Date();

    if(start) {
        this.each(
            function(){
                var ms = this.innerHTML.split(':');
                var sc = Number(ms[0]) * 60 * 60 + Number(ms[1]) * 60 + Number(ms[2]);
                var rs_new = null;
                if (callback) {
                    rs_new = callback(sc, this, rs);
                    if (rs_new) {
                        rs = rs_new;
                    }
                }
                if(sc>=1){
                    sc--;
                    dt.setTime(sc*1000);
                    var hh = dt.getUTCHours();   if(hh < 10){hh = '0'+hh;}
                    var mm = dt.getUTCMinutes(); if(mm < 10){mm = '0'+mm;}
                    var ss = dt.getUTCSeconds(); if(ss < 10){ss = '0'+ss;}
                    this.innerHTML = String( hh+":"+mm+":"+ss );
                    go = true;
                }
            }
        );
    }else{
        go = true;
    }
    if(go){
        window.setTimeout( function() {jQuery(rs).countdown(callback, true);}, 1000);
    }
    return this;
};
