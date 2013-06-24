/*
 * Project: JumpUp
 * Realm: Frontend
 * 
 * The TripInfo is a view helper which is responsible for building the view for a trip's information.
 *
 * gmap\handlemap.js
 * Copyright (c) 2013 Martin Schultz & Sascha Feldmann
 * license    GNU license
 * version    1.0
 * since      03.06.2013
 */

define([ "jquery" ], (function($) {
	const
	BOOKING_ACTION = "booktrip";
	const
	PARAM_TRIP_ID = "tripId";
	const
	PARAM_RECOM_PRICE = "recommendedPrice";
	const
	PARAM_RECOM_START_POINT = "startPoint";
	const
	PARAM_RECOM_START_COORD = "startCoord";
	const
	PARAM_RECOM_END_POINT = "endPoint";
	const
	PARAM_RECOM_END_COORD = "endCoord";
	const
	PARAM_DATE_FROM = "startDate";
	const
	PARAM_DATE_TO = "endDate";
	const
	PARAM_PRICE_FROM = "priceFrom";
	const
	PARAM_PRICE_TO = "priceTo";
	const
	PARAM_MAX_DISTANCE = "maxDistance";
	const
	TRIPS_REF_FORM = 'form[name="LookUpTripsForm"]';
	const
	REF_TRIPS_START_COORD = TRIPS_REF_FORM + ' input[name="startCoord"]';
	const
	REF_TRIPS_END_COORD = TRIPS_REF_FORM + ' input[name="endCoord"]';
	const
	REF_TRIPS_START_POINT = TRIPS_REF_FORM + ' input[name="startPoint"]';
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
	REF_TRIPS_MAX_DISTANCE = TRIPS_REF_FORM + ' input[name="maxDistance"]';

	// was the accordion already initialized? important for the destroy()
	// function on the accordion
	var alreadyInit = false;
	var _this;

	/**
	 * Create a new TripInfo view helper.
	 * 
	 * @param options
	 *            a plain old object with the following attributes: - accordion :
	 *            the JQuery DOM Element (NOT only the selector)
	 */
	var TripInfo = function(options, callbackSelect) {
		_this = this;
		this.accordion = options.accordion || "#accordion";

		this.idMap = new Object();
		this.idMapReversed = new Object();
		this.length = 0;
		this.callbackSelect = callbackSelect;
		this.inputStartPoint = $(REF_TRIPS_START_POINT);
		this.inputEndPoint = $(REF_TRIPS_END_POINT);
		this.inputDateFrom = $(REF_TRIPS_START_DATE);
		this.inputDateTo = $(REF_TRIPS_END_DATE);
		this.inputPriceFrom = $(REF_TRIPS_PRICE_FROM);
		this.inputPriceTo = $(REF_TRIPS_PRICE_TO);
		this.inputMaxDistance = $(REF_TRIPS_MAX_DISTANCE);
		this.options = options;

		this.tooltipItems = "";
		this.tooltipTexts = new Array();

		this.vehTooltipItems = "";
		this.vehTooltipTexts = new Array();

		// empty accordion node
		this.accordion.empty();
	};

	TripInfo.prototype.addBody = function(content) {
		this.accordion.append("<div>" + content + "</div>");
	};

	TripInfo.prototype.addHeadline = function(title) {
		this.accordion.append("<h3>" + title + "</h3>");
	};

	TripInfo.prototype.addBookingForm = function(tripId, bodyStr, systemPrice) {
		var messages = this.options.messages;
		bodyStr += '<form action="' + BOOKING_ACTION + '" method="POST">' + '<input type="hidden" name="'
				+ PARAM_TRIP_ID + '" value="' + tripId + '" />'
				+ messages.price_recom +': <input type="text" name="' + PARAM_RECOM_PRICE + '" value="'
				+ systemPrice + '" />' + '<input type="hidden" name="' + PARAM_RECOM_START_POINT + '" value="'
				+ this.inputStartPoint.val() + '" />' + '<input type="hidden" name="' + PARAM_RECOM_END_POINT
				+ '" value="' + this.inputEndPoint.val() + '" />' + '<input type="hidden" name="' + PARAM_DATE_FROM
				+ '" value="' + this.inputDateFrom.val() + '" />' + '<input type="hidden" name="' + PARAM_DATE_TO
				+ '" value="' + this.inputDateTo.val() + '" />' + '<input type="hidden" name="' + PARAM_PRICE_FROM
				+ '" value="' + this.inputPriceFrom.val() + '" />' + '<input type="hidden" name="' + PARAM_PRICE_TO
				+ '" value="' + this.inputPriceTo.val() + '" />' + '<input type="hidden" name="'
				+ PARAM_RECOM_START_COORD + '" value="' + this.options.startLatLng + '" />'
				+ '<input type="hidden" name="' + PARAM_RECOM_END_COORD + '" value="' + this.options.endLatLng + '" />'
				+ '<input type="hidden" name="' + PARAM_MAX_DISTANCE + '" value="' + this.inputMaxDistance.val()
				+ '" />' + '<input type="submit" value="'+messages.book+'" />' + '</form>';
		return bodyStr;
	};

	TripInfo.prototype.clearContents = function() {
		this.idMap = {};
		this.idMapReversed = new Object();
		this.length = 0;
		this.accordion.empty();
	};

	TripInfo.prototype.reloadAccordion = function() {
		// destroy accordion so it goes back to its init state
		if (!alreadyInit) {
			alreadyInit = true;
		} else { // reset accordion
			this.accordion.accordion("destroy");
		}
		this.accordion.accordion({
			collapsible : true,
			activate : function(event, ui) {
				_this.callbackSelect(_this.idMapReversed[_this.accordion.accordion("option", "active")]);
			},
		});
	};

	TripInfo.prototype.select = function(tripid) {
		_this.accordion.accordion("option", "active", _this.idMap[tripid]);
	};

	/**
	 * Build a jquery UI tooltip for the given driver.
	 */
	TripInfo.prototype.buildTooltip = function(id, driver) {
		var messages = this.options.messages;		
		var prefix = "";
		if ("" != this.tooltipItems) {
			prefix = ", ";
		}

		/* crazy shit I know, but it didn't work any other way... */
		this.tooltipItems += prefix + "li[class=drivertooltip][id=" + id + "]";
		this.tooltipTexts[id] = "<p>"+messages.birth_date+": " + driver.birthDate + "</p>" + "<p>"+messages.email+": " + driver.eMail + "</p>"
				+ "<p>"+messages.spoken_langs+": " + driver.spokenLanguages + "</p>" + "<p>"+messages.home_town+": " + driver.homeCity
				+ "</p>" + "<p><img src=\"" + driver.pathProfilePic + "\" /></p>";
		;
		var __this = this;
		$(document).tooltip({
			items : __this.tooltipItems,
			position : {
				my : "right-5 center",
				at : "right center"
			},
			content : function() {
				var $this = $(this);
				var id = $this.attr("id");

				console.log("_buildToolTip: " + id);
				if (undefined != id) {
					return __this.tooltipTexts[id];
				} else {
					return "no chance...";
				}
				;
			}
		});
	};

	/**
	 * Build a jquery UI tooltip for the given vehicle.
	 */
	TripInfo.prototype.buildVehicleTooltip = function(id, vehicle) {
		var messages = this.options.messages;
		var prefix = "";
		if ("" != this.tooltipItems) {
			prefix = ", ";
		}

		/* crazy shit I know, but it didn't work any other way... */
		this.tooltipItems += prefix + "li[class=vehicletooltip][id=" + id + "]";
		this.tooltipTexts[id] = "<p>"+messages.leg_space+": " + vehicle.legspace + "</p>" + "<p>"+messages.wastage+": " + vehicle.wastage
				+ "</p>" + "<p>"+messages.avg_speed+": " + vehicle.avgspeed + "</p>" + "<p>"+messages.number_seats+": "
				+ vehicle.numberseats + "</p>" + "<p>"+messages.number_seats+": " + vehicle.aircondition + "</p>"
				+ "<p>"+messages.actual_wheel+": " + vehicle.actualwheel + "</p>" + "<p><img src=\"" + vehicle.pathPic
				+ "\" /></p>";
		;
		var __this = this;
		$(document).tooltip({
			items : __this.tooltipItems,
			position : {
				my : "right-5 center",
				at : "right center"
			},
			content : function() {
				var $this = $(this);
				var id = $this.attr("id");

				if (undefined != id) {
					return __this.tooltipTexts[id];
				} else {
					return "no chance...";
				}
				;
			}
		});
	};

	/**
	 * Render one incoming trip.
	 * @param trip the returned trip.
	 */
	TripInfo.prototype.addTrip = function(trip) {
		var messages = this.options.messages;
		var id = trip.id;
		var startPoint = trip.startPoint;
		var endPoint = trip.endPoint;
		var startDate = trip.startDate;
		var priceForPassenger = trip.priceRecommendation; // price
		// recommendation by
		// the backend
		var driversPrice = trip.price;
		var driver = trip.driver;
		var startCoord = trip.startCoord;
		var endCoord = trip.endCoord;
		var overviewPath = trip.overviewPath;
		var numberBookings = trip.numberBookings;
		var maxSeats = trip.maxSeats;
		var vehicle = trip.vehicle;		
		this.idMap[id] = this.length;
		this.idMapReversed[this.length++] = id;
		var distFromPassLoc = Math.round(trip.distanceFromPassengersLocation);
		var distFromPassDest = Math.round(trip.distanceFromPassengersDestination);

		this.addHeadline("<span class=\"highlighting\">" + startPoint
				+ "</span> to <span class=\"highlighting\">" + endPoint + "</span>");
		var bodyStr = "<ul>" 
				+ "<li><span class=\"ui-accordion-content-key\">"+messages.location_distance+":</span>" + distFromPassLoc + "</li>"
				+ "<li><span class=\"ui-accordion-content-key\">"+messages.destination_distance+":</span>" + distFromPassDest + "</li>"
				+ "<li class=\"drivertooltip\" id=\"" + id + '\">'
				+ "<span class=\"ui-accordion-content-key\">"+messages.driver+":</span>" + driver.prename + " " + driver.lastname
				+ "</li>" + "<li><span class=\"ui-accordion-content-key\">"+messages.start_date+":</span>" + startDate + "</li>"
				+ "<li><span class=\"ui-accordion-content-key\">"+messages.overall_price+":</span>" + driversPrice + "</li>"
				+ "<li><span class=\"ui-accordion-content-key\">"+messages.current_bookings+":</span>" + numberBookings + "/"
				+ maxSeats + "</li>" + "<li class=\"vehicletooltip\" id=\"" + (id + 100)
				+ "\"><span class=\"ui-accordion-content-key\">"+messages.vehicle+":</span>" + vehicle.brand + " " + vehicle.type
				+ "</li> " + "</ul>";
		bodyStr = this.addBookingForm(id, bodyStr, priceForPassenger);
		this.addBody(bodyStr);

		this.buildTooltip(id, driver);
		this.buildVehicleTooltip(id + 100, vehicle);
	};

	return TripInfo;

})

);
