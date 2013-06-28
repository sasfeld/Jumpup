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
	const PRICE_PER_DIESEL_LT = 1.42;
	const PRICE_PER_PETROL_92 = 1.58;
	const PRICE_PER_LITER_DEFAULT = 1.58;
	const PRICE_PER_PETROL_95 = 1.62;
	const PRICE_PER_PETROL_98 = 1.63;	

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
			var messages = data.messages;
			console.log( vehicle.wastage );
			// @TODO integrate wastage calculator			
			distance = $( REF_ADDTRIP_INPUT_DISTANCE ).val();
			if ( undefined != distance ) {
				var pricePerLt = PRICE_PER_LITER_DEFAULT;
				if(undefined != vehicle.engineType) {
					switch (vehicle.engineType) {
					case "Diesel":
						pricePerLt = PRICE_PER_DIESEL_LT;
						break;
					case "Petrol 92":
						pricePerLt = PRICE_PER_PETROL_92;
						break;
					case "Petrol 95":
						pricePerLt = PRICE_PER_PETROL_95; 
						break;
					case "Petrol 98":
						pricePerLt = PRICE_PER_PETROL_98; 
						break;
					default:
						pricePerLt = PRICE_PER_LITER_DEFAULT;
						break;
					};
				}
				var price = Math.round( ( vehicle.wastage / 100 ) * ( distance / 1000 )
						* pricePerLt); // vehicle wastage
				
				var text = messages.recommended + " "+ price + " "
				+ messages.recommended_your + 	" " + vehicle.wastage + "l / 100km (" + vehicle.engineType + "). "
				+ messages.calculation + ": (" + vehicle.wastage / 100 + "l ) * " + distance / 1000 + " km * " + 
				pricePerLt + " euro"; 
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
	 * Error-Event method if the ajax request below fails.
	 */
	VehicleController.prototype.handleError = function(xhr, ajaxOptions,
			thrownError) {
		console.log("TripsController: handleError");
		console.log(xhr);
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
			error : this.handleError,
		} );
	};

	return VehicleController;
} ) );