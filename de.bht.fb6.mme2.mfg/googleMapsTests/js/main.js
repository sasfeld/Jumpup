requirejs.config( {
	paths : {
		// jquery library
		"jquery" : [
		// try content delivery network location first
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min',
		// If the load via CDN fails, load locally
		'lib/jquery.min' ],

		// async library
		"async" : [
		// try content delivery network location first
		'https://raw.github.com/caolan/async/master/lib/async',
		// If the load via CDN fails, load locally
		'lib/async' ]
	}
} );

require( [ "jquery", "googlemap" ], function($, GoogleMap) {
	$( document ).ready( ( function() {
		var options = {
			"map_canvas" : $( "#map_canvas" )[ 0 ],
			"textbox" : $( "#textbox" )[ 0 ],
			"geocoding" : $( "#geocoding" )[ 0 ],
			"directions" : $( "#directions" )[ 0 ]
		};

		try {
			new GoogleMap( options );
		} catch ( e ) {
			// console.log( 'No Map to display: ' + e );
			throw e;
		}
	} ) );
} );
