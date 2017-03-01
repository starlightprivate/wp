(function( $, ajaxurl, wpScanner ) {

	$( document ).ready( function() {
		$( '#wp-scanner-refresh' ).on( 'click', function( e ) {
			e.preventDefault();

			var $button  = $( this );
			var $spinner = $( '.wp-scanner-form .spinner' );

			$button.attr( 'disabled', true );
			$spinner.css( 'visibility', 'visible' );

			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: {
					nonce: wpScanner.nonces.refresh,
					action: 'wp_scanner_refresh_status',
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					console.log( 'Error refreshing API status.' );
				},
				success: function( data ) {
					var $status = $( '.wp-scanner-form .status' );

					$button.attr( 'disabled', false );
					$spinner.css( 'visibility', 'hidden' );

					if ( data.success ) {
						$status.removeClass( 'disconnected' ).addClass( 'connected' ).text( wpScanner.strings.connected );
					} else {
						$status.removeClass( 'connected' ).addClass( 'disconnected' ).text( wpScanner.strings.disconnected );
					}
				}
			} );
		} );
	} );

})( jQuery, ajaxurl, wpScanner );