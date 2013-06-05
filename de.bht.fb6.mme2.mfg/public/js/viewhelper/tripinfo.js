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

define( [ "jquery" ], ( function($) {
	const BOOKING_ACTION = "booktrip";
	const PARAM_TRIP_ID = "tripId";
	const PARAM_RECOM_PRICE = "recommendedPrice";
	var alreadyInit = false;
	
	/**
	 * Create a new TripInfo view helper.
	 * @param options a plain old object with the following attributes:
	 * 	- accordion : the JQuery DOM Element (NOT only the selector)
	 */
	var TripInfo = function(options) {
		this.accordion = options.accordion || "#accordion";
		
		
		// empty accordion node
		this.accordion.empty();
	};
	
	TripInfo.prototype.addBody = function(content) {
		this.accordion.append("<div>"+content+"</div>");
	};
	
	TripInfo.prototype.addHeadline = function(title) {
		this.accordion.append("<h3>"+title+"</h3>");
	};
	
	TripInfo.prototype.addBookingForm = function(tripId, bodyStr, systemPrice) {
		bodyStr += '<form action="'+BOOKING_ACTION
			+'" method="POST">'
			+'<input type="hidden" name="'+PARAM_TRIP_ID+'" value="'+tripId+'" />' 
			+'Your price recommendation: <input type="text" name="'+PARAM_RECOM_PRICE+'" value="'+systemPrice+'" />' 			
			+'<input type="submit" value="Book" />'
			+'</form>';
		return bodyStr;
	};
	
	TripInfo.prototype.clearContents = function() {
		this.accordion.empty();
	}
	
	TripInfo.prototype.reloadAccordion = function() {
		// destroy accordion so it goes back to its init state
		if(!alreadyInit) {
			alreadyInit = true;
		}
		else { // reset accordion
			this.accordion.accordion("destroy");
		}		
		this.accordion.accordion({
			collapsible: true,
		});
	}
	
	TripInfo.prototype.addTrip = function (trip) {
		var id = trip.id;
		var startPoint = trip.startPoint;
		var endPoint = trip.endPoint;
		var startDate = trip.startDate;
		var price = trip.price;
		var driver = trip.driver; // currently: prename and
		// lastname
		var startCoord = trip.startCoord;
		var endCoord = trip.endCoord;
		var overviewPath = trip.overviewPath;
		var numberBookings = trip.numberBookings;
		var maxSeats = trip.maxSeats;
			
		this.addHeadline("Trip from "+startPoint+" to "+endPoint);
		var bodyStr = "<ul>" 
			+ "<li>Driver: "+driver+"</li>"
			+ "<li>Start date: "+startDate+"</li>"
			+ "<li>Price: "+price+"</li>"
			+ "<li>Current bookings: "+numberBookings+"/"+maxSeats+"</li>"
			+ "</ul>";
		bodyStr = this.addBookingForm(id, bodyStr, price);
		this.addBody(bodyStr);		
	};
	
	
	return TripInfo;
	

})

);
