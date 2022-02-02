( function( $ ) {
    "use strict";

    function dappc() { }

    dappc.prototype.init = function() {
        
        $( '.dappc-page-container' ).on( 'click', '#refresh-table-data:not(disabled)', this.callAjaxHandler );

    }

    dappc.prototype.callAjaxHandler = function(event) {
        
        event.preventDefault();
        $( event.target ).prop( 'disabled', true );
        $( '.dappc-loading' ).show();

        var request = $.post( 
            dappcObj.ajaxUrl,
            {
                action: 'dappc_admin_ajax',
                security: dappcObj.ajaxNonce
            }
        );

        request.done( function ( response ) {

            $( event.target ).prop( 'disabled', false );
            $( '.dappc-loading' ).hide();

            var results = response.data.results;

            if ( false === response.data.success ) {
                $( '#daapc-admin-table' ).html( results.message );
                return;
            }

            var template = $( '#dappc-admin-template' ).html();
            var templateFunc = _.template( template );

            $( '#daapc-admin-table' ).html( 
                templateFunc ({
                    title: results.title,
                    headers: results.data.headers,
                    rows: results.data.rows
                })
            );

        } );

        request.fail( function( xhr, textStatus, errorThrown ) {

            $( '.dappc-loading' ).hide();
            $( event.target ).prop( 'disabled', false );

            //Show errors

        });

    }

    if ( 'undefined' !== typeof dappcObj ) {

        var frontend = new dappc();
        frontend.init();

    }

}
)( jQuery );
