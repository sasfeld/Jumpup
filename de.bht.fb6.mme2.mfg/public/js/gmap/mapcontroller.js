/*
 * Project: JumpUp
 * Realm: Frontend
 * 
 * This is the handlemap module. It is used as controller for the Google map.
 *
 * gmap\handlemap.js
 * Copyright (c) 2013 Martin Schultz & Sascha Feldmann
 * license    GNU license
 * version    1.0
 * since      26.04.2013
 */

define( [ "gmap/googlemap", "jquery", "gmap/overviewPathStrategy" ], ( function(
		GoogleMap, $, OverviewPathStrategy) {
	// --> hidden input fields which needs to be stored in DB
	const
	REF_ADDTRIP_INPUT_STARTCOORD_VISIBLE = 'input[name="startPoint"]';
	const
	REF_ADDTRIP_INPUT_ENDCOORD_VISIBLE = 'input[name="endPoint"]';
	const
	REF_ADDTRIP_INPUT_STARTCOORD = 'input[name="startCoordinate"]';
	const
	REF_ADDTRIP_INPUT_ENDCOORD = 'input[name="endCoordinate"]';
	const
	REF_ADDTRIP_INPUT_DURATION = 'input[name="duration"]';
	const
	REF_ADDTRIP_INPUT_DISTANCE = 'input[name="distance"]';
	const
	REF_ADDTRIP_INPUT_OVERVIEW_PATH = 'input[name="overviewPath"]';
	const
	REF_ADDTRIP_INPUT_VIA_WAYPOINTS = 'input[name="viaWaypoints"]';
	var _this;

	/*
	 * Constructor function for this module. The google map will be initialized in
	 * the constructor. - param options, see the google map reference - throws an
	 * exception if the map isn't loaded
	 */
	var MapController = function(mapsOptions, ctrlOptions) {
		_this = this;

		try {
			this.gmap = new GoogleMap( mapsOptions );
			if ( null != ctrlOptions ) {
				this.inputStartCoord = ctrlOptions.input_start_coord
						|| window.REF_ADDTRIP_INPUT_STARTCOORD;
				this.inputEndCoord = ctrlOptions.input_end_coord
						|| window.REF_ADDTRIP_INPUT_ENDCOORD;
			}
		} catch ( e ) {
			throw e;
		}
		;

		/*
		 * fetch and store coordinate of points
		 */
		this.inputStartVisible = $( REF_ADDTRIP_INPUT_STARTCOORD_VISIBLE );
		this.inputEndVisible = $( REF_ADDTRIP_INPUT_ENDCOORD_VISIBLE );
		this.inputStartCoord = $( REF_ADDTRIP_INPUT_STARTCOORD );
		this.inputEndCoord = $( REF_ADDTRIP_INPUT_ENDCOORD );
		this.inputDuration = $( REF_ADDTRIP_INPUT_DURATION );
		this.inputDistance = $( REF_ADDTRIP_INPUT_DISTANCE );
		this.inputOverviewPath = $( REF_ADDTRIP_INPUT_OVERVIEW_PATH );
		this.inputViaWaypoints = $( REF_ADDTRIP_INPUT_VIA_WAYPOINTS );
	};

	/*
	 * Handle the response of Google's DirectionsService.
	 * 
	 * @deprecated
	 */
	MapController.prototype.handleRouteResponse = function(directionsResult) {
		console.log( "Map controller -> handling route response" );

		var singleRoute = directionsResult.routes[ 0 ];

		if ( 1 == singleRoute.legs.length ) { // no waypoints, only start
			// and endpoint
			var singleLeg = singleRoute.legs[ 0 ];
			var startLatLng = singleLeg.start_location;
			var endLatLng = singleLeg.end_location;
			var duration = singleLeg.duration.value; // seconds
			var distance = singleLeg.distance.value; // meter
			var overviewPath = singleRoute.overview_path;
			var viaWaypoints = singleLeg.via_waypoints;
			/*
			 * ..:: OverviewPath strategy ::..
			 */
			// change: active strategy in module overviewPathStrategy
			// (return type)
			var overviewStrategy = new OverviewPathStrategy();
			var overviewString = overviewStrategy.toString( overviewStrategy
					.execute( overviewPath ) );
			/*
			 * ..::::::::::::::::::::::::::::..
			 */
			/*
			 * ..:: ViaWaypoints ::..
			 */
			var waypointsStringConcat = "";
			for ( var waypointIndex = 0; waypointIndex < viaWaypoints.length; waypointIndex++ ) {
				// kb = latitude / breite; lb = longitude / länge
				waypointsStringConcat += viaWaypoints[ waypointIndex ].lat() + ","
						+ viaWaypoints[ waypointIndex ].lng() + ";";
			}
			/*
			 * ..::::::::::::::::::..
			 */

			console.log( "Map controller -> startLatLng: \n" + startLatLng );
			console.log( "Map controller -> endLatLng: \n" + endLatLng );
			console.log( "Map controller -> duration: \n" + duration );
			console.log( "Map controller -> overviewPath: \n" + overviewString );
			console
					.log( "Map controller -> viaWaypoints: \n" + waypointsStringConcat );

			/*
			 * ..:: fill hidden input fields ::..
			 */
			if ( _this.gmap.isDraggable() ) {
				_this.inputStartVisible.val( singleLeg.start_address );
				_this.inputEndVisible.val( singleLeg.end_address );
			}
			_this.inputStartCoord.val( startLatLng );
			_this.inputEndCoord.val( endLatLng );
			_this.inputDuration.val( duration );
			_this.inputDistance.val( distance );
			_this.inputOverviewPath.val( overviewString );
			_this.inputViaWaypoints.val( waypointsStringConcat );
			/*
			 * ..::::::::::::::::::::::::::::::..
			 */
			console.log( "value of input field: " + _this.inputStartCoord.val() );
		}
		;
	};

	MapController.prototype.select = function(tripid) {
			_this.gmap.selectByTripId( tripid );
	};

	/*
	 * Show a single route on the map. - param start, the value of the starting
	 * point, must be a coordinate or a valid location. - param destination, the
	 * value of the destination point, must be a coordinate or a valid location. -
	 * param waypoints, an array of waypoints, must be coordinates or valid
	 * locations - param multiple set true if you want several routes to be
	 * rendered.
	 */
	MapController.prototype.showRoute = function(id, start, destination,
			waypoints, multiple, callbackSelect) {

		// remove rendered routes
		if ( !multiple ) {
			this.gmap.removeRoutes();
		}

		// show new route
		// this.gmap.showRoute( start, destination, $( "#sendBt" ),
		// this.handleRouteResponse );

		this.gmap
				.showRoute( id, start, destination, waypoints, this.handleRouteResponse, callbackSelect );
	};
	return MapController; // return constructor function
} ) );