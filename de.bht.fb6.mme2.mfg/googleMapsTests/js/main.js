requirejs.config( {
	paths : {
		// jquery library
		"jquery" : [
		// try content delivery network location first
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min',
		// If the load via CDN fails, load locally
		'lib/jquery.min' ],

		// async library
		"async" : 'lib/async'
	}
} );

require( [ "controller" ], function(Controller) {
	$( document ).ready( ( function() {

		try {
			var ctrl = new Controller();

			ctrl.insertRoute();
		} catch ( e ) {
			// console.log( 'No Map to display: ' + e );
			throw e;
		}
	} ) );
} );
