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

require( [ "jquery", "gmap/googlemap", "gmap/mapcontroller" ], function($, GoogleMap, MapController) {
	/* 
	 * ..:: global config ::..
	 * 
	 * consts can be accessed as window attributes:
	 * like: window.REF_MAP_CANVAS
	 */	
	// --> Map containers
	const REF_MAP_CANVAS = "#map_canvas";
	const REF_MAP_TEXTBOX = "#textbox";
	const REF_MAP_GEOCODING = "#geocoding";
	const REF_MAP_DIRECTIONS = "#directions";
	
	// --> input fields
	const REF_ADDTRIP_INPUT_START = 'input[name="startPoint"]';
	const REF_ADDTRIP_INPUT_END = 'input[name="endPoint"]';
	// --> hidden input fields which needs to be stored in DB
	const REF_ADDTRIP_INPUT_STARTCOORD = 'input[name="startCoordinate"]';
	const REF_ADDTRIP_INPUT_ENDCOORD = 'input[name="endCoordinate"]';
	
	/* 
	 * ..:::::::::::::::::::..
	 */	
	
	$( document ).ready( ( function() {
		/* 
		 * ..:: initialize mapController ::..
		 */		
		var mapOptions = {
			"map_canvas" : $( REF_MAP_CANVAS )[ 0 ],
			"textbox" : $( REF_MAP_TEXTBOX )[ 0 ],
			"geocoding" : $( REF_MAP_GEOCODING )[ 0 ],
			"directions" : $( REF_MAP_DIRECTIONS )[ 0 ],
		};
		var ctrlOptions = {
			"input_start_coord" : $ ( REF_ADDTRIP_INPUT_STARTCOORD ),
			"input_end_coord" : $ ( REF_ADDTRIP_INPUT_ENDCOORD ), 
		};

		try {
			var mapCtrl = new MapController(mapOptions, ctrlOptions); 			
		} catch ( e ) {
			// console.log( 'No Map to display: ' + e );
			throw e;
		}
		/* 
		 * ..:::::::::::::::::::::::::::::::..
		 */	
		
		
		/* 
		 * ..:: addtrip -> endPoint input field event handler ::..
		 */	
		$( REF_ADDTRIP_INPUT_END ).change( ( function() {
			console.log("main.js: end point changed");
			var startPointValue = $( REF_ADDTRIP_INPUT_START ).val();
			var endPointValue = $( this ).val();
			if(0 != startPointValue.length && 0 != endPointValue) {
				console.log("main.js: showing new route");
				mapCtrl.showSingleRoute(startPointValue, endPointValue);
			}
		}));
		/* 
		 * ..:::::::::::::::::::::::::::::::::::::::::::::::::::..
		 */	

		
	} ) );
	
	
} );
