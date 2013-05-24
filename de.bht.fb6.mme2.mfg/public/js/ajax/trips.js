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

define(["jquery"], 
		(function($) {
			var mapCtrl;
			
			/*
			 * Create a new TripsController.
			 * - param options an array:
			 * 	 getTripsUrl - the url to the json endpoint for the listing of all vehicles
			 * 	 mapCtrl - the map controller
			 */
			var TripsController = function(options) {
				this.options = options;
				mapCtrl = options.mapCtrl;
			};
			
			/*
			 * HandleServerResponse will be called after a successfull request.
			 * @param data
			 */
			TripsController.prototype.handleServerResponse = function(data) {	
				// bad request?
				console.log(data);
				// inform gui
				if(data.trips.length > 0) {
					for(var tripIndex = 0; tripIndex < data.trips.length; tripIndex++) {
						var trip = data.trips[tripIndex];
						var id = trip.id;
						var startPoint = trip.startPoint;
						var endPoint = trip.endPoint;
						var startDate = trip.startDate;
						var price = trip.price;
						var driver = trip.driver; // currently: prename and lastname
						var startCoord = trip.startCoord;
						var endCoord = trip.endCoord;
						var overviewPath = trip.overviewPath;
						var viaWaypoints = trip.viaWaypoints;
						var numberBookings = trip.numberBookings;
						var maxSeats = trip.maxSeats;
						
						// TODO get multiple routes working.
						if(null != startCoord && null != endCoord) {
							mapCtrl.showRoute(startCoord, endCoord, null, true);
						}
						// TODO build selection view for user
						
					}; 
					
				};
			};
			
			/*
			 * Error-Event method if the ajax request below fails.
			 */
			TripsController.prototype.handleError = function(xhr, ajaxOptions, thrownError) {
				console.log("TripsController: handleError");
				console.log(xhr);
			};
			
			/*
			 * Fetch the trips to a given id.
			 */
			TripsController.prototype.fetchTrips = function(startCoord, endCoord, dateFrom, dateTo, priceFrom, priceTo) {
				console.log("TripsController: fetchTrips");
				console.log("startCoord: "+startCoord);
				console.log("endCoord: "+endCoord);
				console.log("startDate: "+startDate);
				console.log("endDate: "+endDate);
				console.log("priceFrom: "+priceFrom);
				console.log("priceTo: "+priceTo);
				
				$.ajax( {
					url: this.options.getTripsUrl,
					data: {
						"startCoord" : startCoord,						
						"endCoord" : endCoord,						
						"startDate" : startDate,						
						"endDate" : endDate,						
						"priceFrom" : priceFrom,						
						"priceTo" : priceTo,						
					},
					dataType: 'json',
					type: "POST",
					success: this.handleServerResponse,
					error: this.handleError,
				});
			};
			
			
			return TripsController;			
		})
		
);