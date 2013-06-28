requirejs.config({
	paths : {
		// jquery library
		"jquery" : [
		// try content delivery network location first
		'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min',
		// If the load via CDN fails, load locally
		'lib/jquery-1.9.1' ],
		"jquery-ui" : "lib/jquery-ui-1.10.3.custom",
		"jmenu" : "lib/jMenu.jquery",
		// async library
		"async" : 'lib/async',
	}
});

require(
		[ "gmap/googlemap", "gmap/mapcontroller", "ajax/vehicle", "ajax/trips",
				"jquery", "jquery-ui", "jmenu" ],
		function(GoogleMap, MapController, VehicleController, TripsController,
				$, jmenu) {

			/*
			 * ..:: global config ::..
			 * 
			 * consts can be accessed as window attributes: like:
			 * window.REF_MAP_CANVAS
			 */
			// --> Map containers
			const
			REF_MAP_CANVAS = "#map_canvas";
			const
			REF_MAP_TEXTBOX = "#textbox";
			const
			REF_MAP_GEOCODING = "#geocoding";
			const
			REF_MAP_DIRECTIONS = "#directions";

			// page AddTrip
			// --> input fields
			const
			ADDTRIP_REF_FORM = 'form[name="AddTripForm"]';
			const
			REF_ADDTRIP_INPUT_START = ADDTRIP_REF_FORM
					+ ' input[name="startPoint"]';
			const
			REF_ADDTRIP_INPUT_END = ADDTRIP_REF_FORM
					+ ' input[name="endPoint"]';
			const
			REF_ADDTRIP_INPUT_DATE = ADDTRIP_REF_FORM
					+ ' input[name="startDate"]';
			const
			REF_ADDTRIP_INPUT_VEHICLE = ADDTRIP_REF_FORM
					+ ' select[name="vehicle"]';
			// --> hidden input fields which needs to be stored in DB
			const
			REF_ADDTRIP_INPUT_STARTCOORD = ADDTRIP_REF_FORM
					+ ' input[name="startCoordinate"]';
			const
			REF_ADDTRIP_INPUT_ENDCOORD = ADDTRIP_REF_FORM
					+ ' input[name="endCoordinate"]';
			const
			REF_ADDTRIP_INPUT_PRICE = ADDTRIP_REF_FORM + ' input[name="price"]';
			const
			REF_ADDTRIP_OVERVIEW_PATH = ADDTRIP_REF_FORM
					+ ' input[name="overviewPath"]';
			const
			REF_ADDTRIP_VIA_WAYPOINTS = ADDTRIP_REF_FORM
					+ ' input[name="viaWaypoints"]';
			// --> submit
			const
			REF_ADDTRIP_SUBMIT = ADDTRIP_REF_FORM + ' input[name="submit"]';

			// page ViewTrips
			const
			TRIPS_REF_FORM = 'form[name="LookUpTripsForm"]';
			const
			REF_TRIPS_START_COORD = TRIPS_REF_FORM
					+ ' input[name="startCoord"]';
			const
			REF_TRIPS_END_COORD = TRIPS_REF_FORM + ' input[name="endCoord"]';
			const
			REF_TRIPS_START_POINT = TRIPS_REF_FORM
					+ ' input[name="startPoint"]';
			const
			REF_TRIPS_END_POINT = TRIPS_REF_FORM + ' input[name="endPoint"]';
			const
			REF_TRIPS_START_DATE = TRIPS_REF_FORM + ' input[name="startDate"]';
			const
			REF_TRIPS_END_DATE = TRIPS_REF_FORM + ' input[name="endDate"]';
			const
			REF_TRIPS_PRICE_FROM = TRIPS_REF_FORM + ' input[name="priceFrom"]';
			const
			REF_TRIPS_PRICE_TO = TRIPS_REF_FORM + ' input[name="priceTo"]';
			const
			REF_TRIPS_BTN = TRIPS_REF_FORM + ' input[name="tripsBtn"]';
			const
			REF_TRIPS_USER_ID = TRIPS_REF_FORM + ' input[name="userId"]';
			const
			REF_TRIPS_MAX_DISTANCE = TRIPS_REF_FORM
					+ ' input[name="maxDistance"]';

			// page JumpUpPassenger/ViewBookings
			const
			DRIVER_BOOKINGS_REF = '#driver_view_bookings';
			const
			DRIVER_BOOKINGS_ACCORDION = DRIVER_BOOKINGS_REF + ' > #accordion';
			const
			PASS_BOOKINGS_REF = '#passenger_view_bookings';
			const
			PASS_BOOKINGS_ACCORDION = PASS_BOOKINGS_REF + ' > #accordion';

			/*
			 * ..:::::::::::::::::::..
			 */
			$(".navigation").jMenu({
				openClick : false,
				ulWidth : 'auto',
				// effects : {
				// effectSpeedOpen : 400,
				// effectSpeedClose : 200,
				// effectTypeOpen : 'slide',
				// effectTypeClose : 'hide',
				// effectOpen : 'easeOutBounce',
				// effectClose : 'easeOutBounce'
				// },
				TimeBeforeOpening : 100,
				TimeBeforeClosing : 100,
				animatedText : false,
				paddingLeft : 1,
			});

			$(document)
					.ready(

							(function() {

								/*
								 * ..:: initialize mapController ::..
								 */
								var mapOptions = {
									"map_canvas" : $(REF_MAP_CANVAS)[0],
									"textbox" : $(REF_MAP_TEXTBOX)[0],
									"geocoding" : $(REF_MAP_GEOCODING)[0],
									"directions" : $(REF_MAP_DIRECTIONS)[0],
								};

								try {
									var mapCtrl = null;
									var vehicleCtrl = null;
									/*
									 * ..::------------> page: AddTrip
									 * <------------::..
									 */
									if ($(ADDTRIP_REF_FORM).length > 0) { // element
										// exists
										/*
										 * ..:: initialize mapController::..
										 */
										console
												.log("main.js: creating map controller for AddTrip");
										var ctrlOptions = {
											"input_start_coord" : $(REF_ADDTRIP_INPUT_STARTCOORD),
											"input_end_coord" : $(REF_ADDTRIP_INPUT_ENDCOORD),
										};
										mapOptions["draggable"] = true;
										mapOptions["selectable"] = false;
										mapOptions["showDirectionsPanel"] = true;

										mapCtrl = new MapController(mapOptions,
												ctrlOptions);

										/*
										 * ..:: initialize vehicleController::..
										 */
										var vehOptions = {
											"listVehiclesUrl" : "driverjson",
											"vehicleParam" : "vehicleId",
											// "tooltips" : tooltips,
											"inputPrice" : $(REF_ADDTRIP_INPUT_PRICE),
										};
										vehicleCtrl = new VehicleController(
												vehOptions);

										/*
										 * Draw Route and Display
										 */
										function updateRoute() {
											console
													.log("main.js: date changed");
											var startPointValue = $(
													REF_ADDTRIP_INPUT_START)
													.val();
											var endPointValue = $(
													REF_ADDTRIP_INPUT_END)
													.val();
											var viaWaypoints = $(
													REF_ADDTRIP_VIA_WAYPOINTS)
													.val();

											var waypointsArray = null;
											if ("" != viaWaypoints) {
												waypointsArray = mapCtrl
														.toOverviewArray(viaWaypoints);
											}

											if (0 != startPointValue.length
													&& 0 != endPointValue) {
												console
														.log("main.js: showing new route");
												mapCtrl.showRoute(null,
														startPointValue,
														endPointValue,
														waypointsArray, false);
											}
											;
										}

										// check whether a route shall be
										// displayed (reconstructed)

										var startCoordDom = $(REF_ADDTRIP_INPUT_STARTCOORD);
										var endCoordDom = $(REF_ADDTRIP_INPUT_ENDCOORD);
										if ("" != startCoordDom.val()
												&& "" != endCoordDom.val()) {
											updateRoute();
										}

										/*
										 * ..:: AutoComplete Start ::..
										 */
										if ($(REF_ADDTRIP_INPUT_START).length > 0) {
											console
													.log("main.js: Binding input field for start in Addtrip");
											// auto completion for start point
											mapCtrl.gmap
													.setAutocomplete(
															$(REF_ADDTRIP_INPUT_START),
															function(place) {
																// start place
																// changed

																validStart = place.geometry.location;
																$(
																		REF_ADDTRIP_INPUT_START)
																		.val(
																				validStart);
																updateRoute();
															});
										}
										;

										/*
										 * ..:: AutoComplete End ::..
										 */
										if ($(REF_ADDTRIP_INPUT_END).length > 0) {
											console
													.log("main.js: Binding input field for end in Addtrip");
											// auto completion for end point
											mapCtrl.gmap
													.setAutocomplete(
															$(REF_ADDTRIP_INPUT_END),
															function(place) {
																// end place
																// changed

																validEnd = place.geometry.location;
																$(
																		REF_ADDTRIP_INPUT_END)
																		.val(
																				validEnd);
																updateRoute();
															});
										}
										;
										/*
										 * ..::::::::::::::::::::::::::::::::::..
										 */
										/*
										 * ..:: addtrip -> endPoint input field
										 * event handler ::..
										 */
										$(REF_ADDTRIP_INPUT_VEHICLE)
												.change(
														(function() {
															vehId = $
																	.trim($(
																			REF_ADDTRIP_INPUT_VEHICLE
																					+ ' option:selected ')
																			.val());
															console
																	.log('veh id: '
																			+ vehId);
															vehicleCtrl
																	.fetchVehicles(vehId);
														}));

										/*
										 * ..:: addtrip -> vehicle input field
										 * event handler ::..
										 */
										// $( REF_ADDTRIP_INPUT_DATE ).focus( (
										// updateRoute ));

									}
									/*
									 * ..::------------> <------------::..
									 */

									/*
									 * ..::------------> page: LookUpTrips
									 * <------------::..
									 */
									var tripsCtrl = null;
									if ($(TRIPS_REF_FORM).length > 0) {
										console
												.log("main.js: creating map controller for LookUpTrips");

										mapOptions["draggable"] = false;
										mapOptions["selectable"] = true;
										mapOptions["showDirectionsPanel"] = false;
										mapCtrl = new MapController(mapOptions,
												null);
										/*
										 * ..:: initialize tripsController ::..
										 */
										var tripsOptions = {
											"getTripsUrl" : "tripsjson",
											"mapCtrl" : mapCtrl,
											"userId" : $(REF_TRIPS_USER_ID)
													.val(),
										};
										tripsCtrl = new TripsController(
												tripsOptions);

										/*
										 * ..::::::::::::::::::::::::::::::::::..
										 */
										if ($(REF_TRIPS_START_POINT).length > 0) {
											console
													.log("main.js: Binding input field for start in LookUpTrips");
											// auto completion for start point
											mapCtrl.gmap
													.setAutocomplete(
															$(REF_TRIPS_START_POINT),
															function(place) {
																validStart = place.geometry.location;
																$(
																		REF_TRIPS_START_POINT)
																		.val(
																				validStart);
																$(
																		REF_TRIPS_START_COORD)
																		.val(
																				place.geometry.location);
																tripsCtrl
																		.setStartCoord(place.geometry.location);
															});
										}
										;
										if ($(REF_TRIPS_END_POINT).length > 0) {
											console
													.log("main.js: Binding input field for end in LookUpTrips");
											// auto completion for start point
											mapCtrl.gmap
													.setAutocomplete(
															$(REF_TRIPS_END_POINT),
															function(place) {
																validStart = place.geometry.location;
																$(
																		REF_TRIPS_END_POINT)
																		.val(
																				validStart);
																$(
																		REF_TRIPS_END_COORD)
																		.val(
																				place.geometry.location);
																tripsCtrl
																		.setEndCoord(place.geometry.location);
															});
										}
										;
										/*
										 * ..:: lookUpTrips -> button event
										 * handler ::..
										 */
										$(REF_TRIPS_BTN)
												.click(
														(function() {
															console
																	.log("lookuptrips ... button clicked...");
															startPoint = $(
																	REF_TRIPS_START_POINT)
																	.val();
															endPoint = $(
																	REF_TRIPS_END_POINT)
																	.val();
															startCoord = $(
																	REF_TRIPS_START_COORD)
																	.val();
															endCoord = $(
																	REF_TRIPS_END_COORD)
																	.val();
															startDate = $(
																	REF_TRIPS_START_DATE)
																	.val();
															endDate = $(
																	REF_TRIPS_END_DATE)
																	.val();
															priceFrom = $(
																	REF_TRIPS_PRICE_FROM)
																	.val();
															priceTo = $(
																	REF_TRIPS_PRICE_TO)
																	.val();
															maxDistance = $(
																	REF_TRIPS_MAX_DISTANCE)
																	.val();
															if (null != tripsCtrl) {
																tripsCtrl
																		.fetchTrips(
																				startPoint,
																				endPoint,
																				startCoord,
																				endCoord,
																				startDate,
																				endDate,
																				priceFrom,
																				priceTo,
																				maxDistance);
															}
														}));

										/*
										 * Route information
										 */
										// $( "#accordion" ).accordion({
										// collapsible: true
										// });
									}
									;
									/*
									 * ..::------------> <------------::..
									 */

									/*
									 * ..::::::::::::::::..
									 */
									/*
									 * ..::------------> page:
									 * JumpUpPassenger\ViewBookings
									 * <------------::..
									 */
									if ($(DRIVER_BOOKINGS_REF).length > 0) {
										$(DRIVER_BOOKINGS_ACCORDION).accordion(
												{
													collapsible : true,
												});
									}
									;
									/*
									 * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
									 */
									/*
									 * ..::------------> page:
									 * JumpUpPassenger\ViewBookings
									 * <------------::..
									 */
									if ($(PASS_BOOKINGS_REF).length > 0) {
										$(PASS_BOOKINGS_ACCORDION).accordion({
											collapsible : true,
										});
									}
									;
									/*
									 * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
									 */
								} catch (e) {
									console.log('No Map to display: ' + e);
									// throw e;
								}
								;
								/*
								 * ..:::::::::::::::::::::::::::::::..
								 */

							}));

		});
