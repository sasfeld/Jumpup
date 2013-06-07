/*
 * Project: JumpUp
 * Realm: Frontend
 * 
 * This is the vehicle module. It is used to handle all ajax stuff for vehicles.
 *
 * gmap\handlemap.js
 * Copyright (c) 2013 Martin Schultz & Sascha Feldmann
 * license    GNU license
 * version    1.0
 * since      05.05.2013
 */

define( [ "jquery", "viewhelper/tripinfo" ], ( function($, TripInfo) {
	const REF_ACCORDION = "#accordion";
	var mapCtrl;
	var _this;

	/*
	 * Create a new TripsController. - param options an array: getTripsUrl - the
	 * url to the json endpoint for the listing of all vehicles mapCtrl - the map
	 * controller
	 */
	var TripsController = function(options) {
		_this = this;
		
		this.options = options;
		if ( options.mapCtrl )
			mapCtrl = options.mapCtrl;
		else
			throw "Illegal Argument";
	};

	/*
	 * HandleServerResponse will be called after a successfull request. @param
	 * data
	 */
	TripsController.prototype.handleServerResponse = function(data) {
		// TripInfo view helper
		var viewOptions = {
			"accordion" : $( REF_ACCORDION ),
		};
		var tripInfoView = new TripInfo( viewOptions, mapCtrl.select );
		// bad request?
		console.log( data );
		// inform gui
		if ( data.trips.length > 0 ) {
			for ( var tripIndex = 0; tripIndex < data.trips.length; tripIndex++ ) {
				var trip = data.trips[ tripIndex ];

				var viaWaypoints = trip.viaWaypoints;

				var waypointsArray = new Array();
				if ( viaWaypoints != null ) {
					waypointsArray = viaWaypoints.split( ";" );
					waypointsArray.pop(); // last empty element
					for ( var i = 0; i < waypointsArray.length; i++ ) {
						waypointsArray[ i ] = "(" + waypointsArray[ i ] + ")";
					}
					console.log( "trips.js: waypoints array: " + waypointsArray );
				}
				// TODO get multiple routes working.
				if ( null != startCoord && null != endCoord ) {
					mapCtrl.showRoute( trip.id, trip.startCoord, trip.endCoord, waypointsArray, true, tripInfoView.select );
				}
				// build selection view for user
				tripInfoView.addTrip( trip );
			}
			;

		}
		;

		// activate accordion
		tripInfoView.reloadAccordion();
	};

	/*
	 * Error-Event method if the ajax request below fails.
	 */
	TripsController.prototype.handleError = function(xhr, ajaxOptions,
			thrownError) {
		console.log( "TripsController: handleError" );
		console.log( xhr );
	};

	/*
	 * Fetch the trips to a given id.
	 */
	TripsController.prototype.fetchTrips = function(startCoord, endCoord,
			dateFrom, dateTo, priceFrom, priceTo) {
		console.log( "TripsController: fetchTrips" );
		console.log( "startCoord: " + startCoord );
		console.log( "endCoord: " + endCoord );
		console.log( "startDate: " + startDate );
		console.log( "endDate: " + endDate );
		console.log( "priceFrom: " + priceFrom );
		console.log( "priceTo: " + priceTo );

		$.ajax( {
			url : this.options.getTripsUrl,
			data : {
				"startCoord" : startCoord,
				"endCoord" : endCoord,
				"startDate" : startDate,
				"endDate" : endDate,
				"priceFrom" : priceFrom,
				"priceTo" : priceTo,
			},
			dataType : 'json',
			type : "POST",
			success : this.handleServerResponse,
			error : this.handleError,
		} );
	};

	return TripsController;
} )

);