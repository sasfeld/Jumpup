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
		this.accordion.appendChild("<p>Bla</p>");
	};
	
	TripInfo.prototype.addHeadline = function(title) {
		this.accordion.appendChild("<h3>"+title+"</h3>");
	};
	
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
			
		
		
	};
	
	
	return TripInfo;
	

})

);
