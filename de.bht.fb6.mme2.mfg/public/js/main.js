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
           "ajax/vehicle", "ajax/trips"], 
           function($, GoogleMap, MapController, VehicleController, TripsController) {
	
	
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
	
	// page AddTrip
	// --> input fields
	const ADDTRIP_REF_FORM = 'form[name="AddTripForm"]';
	const REF_ADDTRIP_INPUT_START = ADDTRIP_REF_FORM + ' input[name="startPoint"]';
	const REF_ADDTRIP_INPUT_END = ADDTRIP_REF_FORM + ' input[name="endPoint"]';
	const REF_ADDTRIP_INPUT_DATE = ADDTRIP_REF_FORM + ' input[name="startDate"]';
	const REF_ADDTRIP_INPUT_VEHICLE = ADDTRIP_REF_FORM + ' select[name="vehicle"]';
	// --> hidden input fields which needs to be stored in DB
	const REF_ADDTRIP_INPUT_STARTCOORD = ADDTRIP_REF_FORM + ' input[name="startCoordinate"]';
	const REF_ADDTRIP_INPUT_ENDCOORD = ADDTRIP_REF_FORM + ' input[name="endCoordinate"]';
	// --> submit
	const REF_ADDTRIP_SUBMIT = ADDTRIP_REF_FORM + ' input[name="submit"]';
	
	// page ViewTrips
	const TRIPS_REF_FORM = 'form[name="LookUpTripsForm"]';
	const REF_TRIPS_START_COORD = TRIPS_REF_FORM + ' input[name="startCoord"]';
	const REF_TRIPS_END_COORD = TRIPS_REF_FORM + ' input[name="endCoord"]';
	const REF_TRIPS_START_POINT = TRIPS_REF_FORM + ' input[name="startPoint"]';
	const REF_TRIPS_END_POINT = TRIPS_REF_FORM + ' input[name="endPoint"]';
	const REF_TRIPS_START_DATE = TRIPS_REF_FORM + ' input[name="startDate"]';
	const REF_TRIPS_END_DATE = TRIPS_REF_FORM + ' input[name="endDate"]';
	const REF_TRIPS_PRICE_FROM = TRIPS_REF_FORM + ' input[name="priceFrom"]';
	const REF_TRIPS_PRICE_TO = TRIPS_REF_FORM + ' input[name="priceTo"]';
	const REF_TRIPS_BTN = TRIPS_REF_FORM + ' input[name="tripsBtn"]';
	
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
			if (undefined != $(ADDTRIP_REF_FORM)) {
				var mapCtrl = new MapController(mapOptions, ctrlOptions); 		
			}
			/* 
			 * ..:: map events ::..
			 */	
			if (undefined != $(REF_ADDTRIP_INPUT_START)) {
				// auto completion for start point
				mapCtrl.gmap.setAutocomplete( $( REF_ADDTRIP_INPUT_START ), function(place) {
					validStart = place.geometry.location;
					$( REF_ADDTRIP_INPUT_START ).val(validStart);
				} );
			};
			if (undefined != $(REF_ADDTRIP_INPUT_END)) {
				// auto completion for end point
				mapCtrl.gmap.setAutocomplete( $( REF_ADDTRIP_INPUT_END ), function(place) {
					validEnd = place.geometry.location;
					$( REF_ADDTRIP_INPUT_END ).val(validEnd);
				} );
			};
			
			/* 
			 * ..::::::::::::::::..
			 */	
		} catch ( e ) {
			// console.log( 'No Map to display: ' + e );
			//throw e;
		};
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
		 * ..:: initialize tripsController ::..
		 */	
		var tripsOptions = {
			"getTripsUrl" : "tripsjson",	
		};
		var tripsCtrl = new TripsController(tripsOptions);		
		/* 
		 * ..::::::::::::::::::::::::::::::::::..
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
		
		/* 
		 * ..:: lookUpTrips -> button event handler ::..
		 */	
		$( REF_TRIPS_BTN ).click( (function() {
			console.log("lookuptrips ... button clicked...");
			startCoord = $ ( REF_TRIPS_START_COORD ).val();
			endCoord = $ ( REF_TRIPS_END_COORD ).val();
			startDate = $ ( REF_TRIPS_START_DATE ).val();
			endDate = $ ( REF_TRIPS_END_DATE ).val();
			priceFrom = $ ( REF_TRIPS_PRICE_FROM ).val();
			priceTo = $ ( REF_TRIPS_PRICE_TO ).val();
			tripsCtrl.fetchTrips(startCoord, endCoord, startDate, endDate, priceFrom, priceTo);
		}));
		
		
	} ) );
	
	
} );
