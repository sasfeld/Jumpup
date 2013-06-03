requirejs.config( {
	paths : {
		// jquery library
		"jquery" : [
			// try content delivery network location first
			'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min',
			// If the load via CDN fails, load locally
			'lib/jquery-1.9.1' ],
		"jquery-ui": "lib/jquery-ui-1.10.3.custom",		
		// async library
		"async" : 'lib/async',
	}
} );

require( [ "gmap/googlemap", "gmap/mapcontroller", 
           "ajax/vehicle", "ajax/trips","jquery", "jquery-ui" ], 
           function(GoogleMap, MapController, VehicleController, TripsController, $) {
	
	
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
		

		try {
			var mapCtrl = null;			
			var vehicleCtrl = null;
			/* 
			 * ..::------------> page: AddTrip <------------::..
			 */		
			if ($(ADDTRIP_REF_FORM).length > 0) { // element exists
				/* 
				 * ..:: initialize mapController::..
				 */
				console.log("main.js: creating map controller for AddTrip");
				var ctrlOptions = {
						"input_start_coord" : $ ( REF_ADDTRIP_INPUT_STARTCOORD ),
						"input_end_coord" : $ ( REF_ADDTRIP_INPUT_ENDCOORD ), 
					};
				mapOptions["draggable"] = true;
				mapOptions["selectable"] = false;
				mapOptions["showDirectionsPanel"] = true;
				
				mapCtrl = new MapController(mapOptions, ctrlOptions); 	
				
				/* 
				 * ..:: initialize vehicleController::..
				 */	
				var vehOptions = {
					"listVehiclesUrl" : "driverjson",	
					"vehicleParam" : "vehicleId",	
				};
				vehicleCtrl = new VehicleController(vehOptions);	
				
				/*
				 * Draw Route and Display 
				 */
				function updateRoute() {
					console.log("main.js: date changed");
					var startPointValue = $( REF_ADDTRIP_INPUT_START ).val();
					var endPointValue = $( REF_ADDTRIP_INPUT_END ).val();
					
					if(0 != startPointValue.length && 0 != endPointValue) {
						console.log("main.js: showing new route");
						mapCtrl.showRoute(startPointValue, endPointValue, null, false);
					};
				}
				
				/* 
				 * ..:: AutoComplete Start ::..
				 */	
				if ($(REF_ADDTRIP_INPUT_START).length > 0) {
					console.log("main.js: Binding input field for start in Addtrip");
					// auto completion for start point
					mapCtrl.gmap.setAutocomplete( $( REF_ADDTRIP_INPUT_START ), function(place) {
						// start place changed
						
						validStart = place.geometry.location;
						$( REF_ADDTRIP_INPUT_START ).val(validStart);
						updateRoute();
					} );
				};
				
				/* 
				 * ..:: AutoComplete End ::..
				 */	
				if ($(REF_ADDTRIP_INPUT_END).length > 0) {
					console.log("main.js: Binding input field for end in Addtrip");
					// auto completion for end point
					mapCtrl.gmap.setAutocomplete( $( REF_ADDTRIP_INPUT_END ), function(place) {
						// end place changed
						
						validEnd = place.geometry.location;
						$( REF_ADDTRIP_INPUT_END ).val(validEnd);
						updateRoute();
					} );
				};
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
				// $( REF_ADDTRIP_INPUT_DATE ).focus( ( updateRoute ));
			}	
			/* 
			 * ..::------------>             <------------::..
			 */
		
			
			/* 
			 * ..::------------> page: LookUpTrips <------------::..
			 */
			var tripsCtrl = null;
			if($(TRIPS_REF_FORM).length > 0) {	
				console.log("main.js: creating map controller for LookUpTrips");
				
				mapOptions["draggable"] = false;
				mapOptions["selectable"] = true;
				mapOptions["showDirectionsPanel"] = false;
				mapCtrl = new MapController(mapOptions, null); 		
				/* 
				 * ..:: initialize tripsController ::..
				 */	
				var tripsOptions = {
					"getTripsUrl" : "tripsjson",	
					"mapCtrl" : mapCtrl, 
				};
				 tripsCtrl = new TripsController(tripsOptions);		
				/* 
				 * ..::::::::::::::::::::::::::::::::::..
				 */	
				 	if ($(REF_TRIPS_START_POINT).length > 0) {
				    	console.log("main.js: Binding input field for start in LookUpTrips");
						// auto completion for start point
						mapCtrl.gmap.setAutocomplete( $( REF_TRIPS_START_POINT ), function(place) {
							validStart = place.geometry.location;
							$( REF_TRIPS_START_POINT ).val(validStart);
						} );
					};
					if ($(REF_TRIPS_END_POINT).length > 0) {
						console.log("main.js: Binding input field for end in LookUpTrips");
						// auto completion for start point
						mapCtrl.gmap.setAutocomplete( $( REF_TRIPS_END_POINT ), function(place) {
							validStart = place.geometry.location;
							$( REF_TRIPS_END_POINT ).val(validStart);
						} );
					};
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
						if(null != tripsCtrl) {
							tripsCtrl.fetchTrips(startCoord, endCoord, startDate, endDate, priceFrom, priceTo);
						}
					}));
					
					/*
					 * Route information
					 */
					$( "#accordion" ).accordion({
						collapsible: true
					});
			};			
			/* 
			 * ..::------------>             <------------::..
			 */
			
			/* 
			 * ..::::::::::::::::..
			 */	
		} catch ( e ) {
			console.log( 'No Map to display: ' + e );
			//throw e;
		};
		/* 
		 * ..:::::::::::::::::::::::::::::::..
		 */			
		
	} ) );
	
	
} );
