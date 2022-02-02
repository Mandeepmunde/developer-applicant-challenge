( function ( $ ) {
    "use strict";

    function dappc () {
        this.ajaxNonce = dappcObj.ajaxNonce;
        this.ajaxUrl = dappcObj.ajaxUrl;
        this.shortCodeSelector = '.dappc-container';
    }

    dappc.prototype.init = function () {

        if( $( this.shortCodeSelector ).get().length )
            this.callAjaxHandler();
            
    }

    dappc.prototype.callAjaxHandler = function () {
        var that = this, request;

        request = $.post (
            this.ajaxUrl,
            {
                action: 'dappc_fetch_data',
                security: this.ajaxNonce
            }
        );

        request.done( function ( response ) {

            var results = response.data.results;
            
            if ( false === response.data.success ) {
                $( that.shortCodeSelector ).html( results.message );
                return;
            }
            
            var template = $( '#dappc-template' ).html();
            var templateFunc = _.template( template );

            $( that.shortCodeSelector ).html(
                templateFunc({
                    title: results.title,
                    headers: results.data.headers,
                    rows: results.data.rows 
                }) 
            );

        } );

        request.fail( function ( xhr, textStatus, errorThrown ) {
            //Show errors.
        } );

    }

    if ( 'undefined' !== typeof dappcObj ) {

        var frontend = new dappc();
        frontend.init();

    }

} )( jQuery );