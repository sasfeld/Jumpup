requirejs.config( {
	paths : {
		// jquery library
		"jquery" : [
		// try content delivery network location first
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min',
		// If the load via CDN fails, load locally
		'lib/jquery.min' ],
		// async library
		"async" : 'lib/async',		
	}
} );

require( [ "jquery", "gmap/googlemap", "gmap/mapcontroller", 
           "ajax/vehicle"], 
           function($, GoogleMap, MapController, VehicleController) {
	
	
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
	const REF_ADDTRIP_INPUT_DATE = 'input[name="startDate"]';
	const REF_ADDTRIP_INPUT_VEHICLE = 'select[name="vehicle"]';
	// --> hidden input fields which needs to be stored in DB
	const REF_ADDTRIP_INPUT_STARTCOORD = 'input[name="startCoordinate"]';
	const REF_ADDTRIP_INPUT_ENDCOORD = 'input[name="endCoordinate"]';
	// --> submit
	const REF_ADDTRIP_SUBMIT = 'input[name="submit"]';
	
	
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
		 * ..:: initialize vehicleController ::..
		 */	
		var vehOptions = {
			"listVehiclesUrl" : "driverjson",	
			"vehicleParam" : "vehicleId",	
		};
		var vehicleCtrl = new VehicleController(vehOptions);		
		/* 
		 * ..::::::::::::::::::::::::::::::::::..
		 */	
		/* 
		 * ..:: map events ::..
		 */	
		// auto completion for start point
		mapCtrl.gmap.setAutocomplete( $( REF_ADDTRIP_INPUT_START ), function(place) {
			validStart = place.geometry.location;
			$( REF_ADDTRIP_INPUT_START ).val(validStart);
		} );
		// auto completion for end point
		mapCtrl.gmap.setAutocomplete( $( REF_ADDTRIP_INPUT_END ), function(place) {
			validEnd = place.geometry.location;
			$( REF_ADDTRIP_INPUT_END ).val(validEnd);
		} );
		
		/* 
		 * ..::::::::::::::::..
		 */	
		
		
		/* 
		 * ..:: addtrip -> endPoint input field event handler ::..
		 */			
		$( REF_ADDTRIP_INPUT_VEHICLE ).change( ( function() {
			vehId = $.trim($( REF_ADDTRIP_INPUT_VEHICLE + ' option:selected ').val());
			console.log('veh id: '+vehId);
			vehicleCtrl.fetchVehicles(vehId);
		}));
		/* 
		 * ..:: addtrip -> vehicle input field event handler ::..
		 */			
		$( REF_ADDTRIP_INPUT_DATE ).focus( ( function() {
			console.log("main.js: date changed");
			var startPointValue = $( REF_ADDTRIP_INPUT_START ).val();
			var endPointValue = $( REF_ADDTRIP_INPUT_END ).val();
			if(0 != startPointValue.length && 0 != endPointValue) {
				console.log("main.js: showing new route");
				mapCtrl.showSingleRoute(startPointValue, endPointValue);
			};
			
		}));
		
		
	} ) );
	
	
} );
