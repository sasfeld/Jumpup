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
define( [ "jquery" ], ( function($) {
	var _this = undefined;
	// where the tooltips already set?
	var tooltipsSet = false;
	
	const REF_RECOMM_PRICE = '#recommended_price';
	const REF_ADDTRIP_INPUT_DISTANCE = 'input[name="distance"]';
	const PRICE_PER_LITER = 1.6; // 1,6 € / l

	/*
	 * Create a new VehicleController. - param options an array: listVehiclesUrl -
	 * the url to the json endpoint for the listing of all vehicles ownerParam -
	 * the key for the param containing the owner's ID
	 * tooltips: jquery tooltips
	 * inputPrice: jquery selected element
	 */
	var VehicleController = function(options) {
		this.options = options;
		_this = this;
	};

	/*
	 * HandleServerResponse will be called after a successfull request. @param
	 * data
	 */
	VehicleController.prototype.handleServerResponse = function(data) {
		// bad request?
		console.log( data );
		// inform gui
		if ( data.vehicles.length > 0 ) {
			var vehicle = data.vehicles[ 0 ];
			console.log( vehicle.wastage );
			// @TODO integrate wastage calculator			
			distance = $( REF_ADDTRIP_INPUT_DISTANCE ).val();
			if ( undefined != distance ) {
				var price = Math.round( ( vehicle.wastage / 100 ) * ( distance / 1000 )
						* PRICE_PER_LITER); // vehicle wastage
				
				var text = "The recommended price for your vehicle is " + price
				+ " euro. Your wastage is " + vehicle.wastage + "l / 100km."; 
				if(tooltipsSet) {
					_this.options.tooltips.tooltip("destroy");
				}
				
				_this.options.inputPrice.attr("title", text);								
				_this.options.tooltips = _this.options.inputPrice.tooltip();				
				_this.options.tooltips.tooltip( "open" );
				tooltipsSet = true;
			}
			;
		}
		console.log( "Vehicle -- response from server: " + data.vehicles[ 0 ].id );
		// $(REF_VEHICLE_SELECTION).append('<option
		// value'+data.vehicles[0].id+'>'+data.vehicles[0].brand+'</option>');
	};

	/*
	 * Fetch the vehicles to a given id.
	 */
	VehicleController.prototype.fetchVehicles = function(vehicleId) {
		$.ajax( {
			url : this.options.listVehiclesUrl,
			data : {
				"vehicleId" : vehicleId,
			},
			dataType : 'json',
			type : "POST",
			success : this.handleServerResponse,
		} );
	};

	return VehicleController;
} ) );