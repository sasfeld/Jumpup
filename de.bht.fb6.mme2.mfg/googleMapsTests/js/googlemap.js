define( [
		"jquery",
		"async!http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBsZwdJI29OQJgyUNPbucRH_l5r_NqSuH4&sensor=true" ], ( function(
		$) {

	/**
	 * @param divs
	 *          Is a assosiativ array. Avalabile options: 'mode', 'map_canvas',
	 *          'textbox', 'geocoding'
	 */
	var GoogleMap = function(options) {

		this.map_canvas = options[ 'map_canvas' ];
		this.textbox = options[ 'textbox' ];

		if ( !this.map_canvas )
			throw "map_canvas is required for the map.";

		this.map;
		this.infowindow;
		this.directionsService;
		this.directionsDisplay;
		this.geocoder;
		this.infoWindow;

		this.mapsLoaded();
	}; // GoogleMap constructor

	GoogleMap.prototype.mapsLoaded = function() {
		// var input = /** @type {HTMLInputElement} */
		// ( document.getElementById( 'searchTextField' ) );
		// var autocomplete = new google.maps.places.Autocomplete( input );
		//
		// autocomplete.bindTo( 'bounds', map );

		directionsService = new google.maps.DirectionsService();
		geocoder = new google.maps.Geocoder();
		infowindow = new google.maps.InfoWindow();
		var torun = new google.maps.LatLng( 53.01357, 18.597665 );
		map = new google.maps.Map( this.map_canvas, {
			mapTypeId : google.maps.MapTypeId.ROADMAP,
			center : torun,
			zoom : 5
		} );
	}; // mapsLoaded()

	GoogleMap.prototype.setAutocomplete = function(input, placeChanged) {
		var autocomplete = new google.maps.places.Autocomplete( input[ 0 ] );
		google.maps.event.addListener( autocomplete, 'place_changed', function() {
			placeChanged( autocomplete.getPlace() );
		} );
		// autocomplete.bindTo( 'bounds', map );
	}; // setAutocomplete()

	GoogleMap.prototype.getLatLng = function(address) {
		var latLng;

		geocoder.geocode( {
			'address' : start
		}, function(results, status) {
			if ( status == google.maps.GeocoderStatus.OK ) {
				if ( results.length > 0 ) {

					for ( var result in results ) {
						console.log( result );
					}

				} else {
					throw 'No results found for: ' + start;
				}
			} else {
				throw 'Geocoder failed due to: ' + status;
			}
		} );

		return latLng;
	}; // getLatLng()

	GoogleMap.prototype.showRoute = function(startLatLng, endLatLng) {

		directionsDisplay = new google.maps.DirectionsRenderer( {
			map : map,
			preserveViewport : true,
			draggable : true
		} );

		google.maps.event
				.addListener( directionsDisplay, "directions_changed", function() {
					console.log( directionsDisplay.getDirections() );
				} );

		if ( this.textbox )
			directionsDisplay.setPanel( this.textbox );

		// map.setCenter( new google.maps.LatLng( start ) );

		var sampleRequest = {
			origin : startLatLng,
			destination : endLatLng,
			travelMode : google.maps.TravelMode.DRIVING,
			unitSystem : google.maps.UnitSystem.METRIC
		};
		directionsService.route( sampleRequest, function(response, status) {
			if ( status == google.maps.DirectionsStatus.OK ) {
				directionsDisplay.setDirections( response );
			}
		} );

	}; // showDirections()

	return GoogleMap;

} ) ); // define module
