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
define(
		[ "jquery", "viewhelper/tripinfo" ],
		(function($, TripInfo) {
			const
			REF_ACCORDION = "#accordion";
			// stores the only existing instance
			var _this;

			/*
			 * Create a new TripsController. - param options an array:
			 * getTripsUrl - the url to the json endpoint for the listing of all
			 * vehicles mapCtrl - the map controller, userId the id of the
			 * current logged in user
			 */
			var TripsController = function(options) {
				_this = this;

				this.options = options;
				_this = this;
			};

			TripsController.prototype.setStartCoord = function(location) {
				this.startLatLng = location;
				console.log("trips.js -> setStartCoord -> location: "
						+ location);
			};

			TripsController.prototype.setEndCoord = function(location) {
				this.endLatLng = location;
				console.log("trips.js -> setEndCoord -> location: " + location);
			};

			/*
			 * HandleServerResponse will be called after a successfull request.
			 * @param data
			 */
			TripsController.prototype.handleServerResponse = function(data) {
				// TripInfo view helper
				var mapCtrl = _this.options.mapCtrl;
				// clear map
				mapCtrl.gmap.removeRoutes();
				var messages = data.messages;
				var viewOptions = {
					"accordion" : $(REF_ACCORDION),
					"startLatLng" : _this.startLatLng,
					"endLatLng" : _this.endLatLng,
					"messages" : messages,
				};
				
				var tripInfoView = new TripInfo(viewOptions, mapCtrl.select);

				if (data.validationFail == true) {
					console.log(data.validationMessages);
					// @TODO show validation fails to the user...
					alert(data.userMessage);
				} else if (data.noTrips == true) {
					console.log(data);
					alert(data.userMessage);
				}				
				else {
					// bad request?
					console.log(data);
					var multiple = false;
					// inform gui
					if (data.trips.length > 0) {
						for ( var tripIndex = 0; tripIndex < data.trips.length; tripIndex++) {
							var trip = data.trips[tripIndex];

							var viaWaypoints = trip.viaWaypoints;

							var waypointsArray = new Array();
							if (viaWaypoints != null) {
								waypointsArray = viaWaypoints.split(";");
								waypointsArray.pop(); // last empty element
								for ( var i = 0; i < waypointsArray.length; i++) {
									waypointsArray[i] = "(" + waypointsArray[i]
											+ ")";
								}
								console.log("trips.js: waypoints array: "
										+ waypointsArray);
							}
							// TODO get multiple routes working.
							if (null != startCoord && null != endCoord) {
								mapCtrl.showRoute(trip.id, trip.startCoord,
										trip.endCoord, waypointsArray,
										multiple, tripInfoView.select);
								multiple = true;
							}
							// build selection view for user
							
							tripInfoView.addTrip(trip)
						}
						;

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
				console.log("TripsController: handleError");
				console.log(xhr);
			};

			/*
			 * Fetch the trips to a given id.
			 */
			TripsController.prototype.fetchTrips = function(startPoint, endPoint, startCoord,
					endCoord, dateFrom, dateTo, priceFrom, priceTo, maxDistance) {
				console.log("TripsController: fetchTrips");
				console.log("startCoord: " + startCoord);
				console.log("endCoord: " + endCoord);
				console.log("startDate: " + startDate);
				console.log("endDate: " + endDate);
				console.log("priceFrom: " + priceFrom);
				console.log("priceTo: " + priceTo);
				console.log("userId: " + this.options.userId);
				console.log("maxDistance: "+maxDistance);

				var __this = this;

				$.ajax({
					url : this.options.getTripsUrl,
					data : {
						"startPoint" : startPoint,
						"endPoint"   : endPoint,
						"startCoord" : startCoord,
						"endCoord" : endCoord,
						"startDate" : startDate,
						"endDate" : endDate,
						"priceFrom" : priceFrom,
						"priceTo" : priceTo,
						"userId" : __this.options.userId,
						"maxDistance" : maxDistance,
					},
					dataType : 'json',
					type : "POST",
					success : this.handleServerResponse,
					error : this.handleError,
				});
			};

			return TripsController;
		})

);
