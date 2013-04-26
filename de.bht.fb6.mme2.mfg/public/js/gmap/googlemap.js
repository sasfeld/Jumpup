define( [
		"jquery",
		"async!http://maps.google.com/maps/api/js?key=AIzaSyBsZwdJI29OQJgyUNPbucRH_l5r_NqSuH4&sensor=true" ], ( function(
		$) {

	/**
	 * @param divs
	 *          Is a assosiativ array. Avalabile options: 'mode', 'map_canvas',
	 *          'textbox', 'geocoding'
	 */
	var GoogleMap = function(options) {

		this.mode = options[ 'mode' ];
		this.map_canvas = options[ 'map_canvas' ];
		this.textbox = options[ 'textbox' ];
		this.geocoding = options[ 'geocoding' ];

		if ( !this.map_canvas || !this.geocoding )
			throw "mode, map_canvas and geocoding are required for the map.";

		this.map;
		this.infowindow;
		this.directionsService;
		this.directionsDisplay;
		this.geocoder;
		this.infoWindow;
		this.marker;
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
		// google.maps.event.addListener( map, 'click', function(e) {
		// geocoder.geocode( {
		// 'latLng' : e.latLng
		// }, function(results, status) {
		// if ( status == google.maps.GeocoderStatus.OK ) {
		// if ( results[ 0 ] ) {
		// if ( marker ) {
		// marker.setPosition( e.latLng );
		// } else {
		// marker = new google.maps.Marker( {
		// position : e.latLng,
		// map : map
		// } );
		// }
		// infowindow.setContent( results[ 0 ].formatted_address );
		// infowindow.open( map, marker );
		// } else {
		// this.geocoding.innerHTML = 'No results found';
		// }
		// } else {
		// this.geocoding.innerHTML = 'Geocoder failed due to: ' + status;
		// }
		// } );
		// } );
	}; // mapsLoaded()

	/*
	 * Show a route.
	 * - param startInput, the value of the start location
	 * - param endInput, the value of the end location
	 * - param callbackFnc, the callback function which will be called after a successfull route request
	 */
	GoogleMap.prototype.showRoute = function(startInput, endInput, sendBt, callbackFnc) {

		this.directionsDisplay = new google.maps.DirectionsRenderer( {
			map : map,
			preserveViewport : true,
			draggable : true
		} );

		if ( this.textbox )
			this.directionsDisplay.setPanel( this.textbox );

		var _this = this; // closure
		function _showRoute(e) {
			var code = -1;
			if ( e )
				code = ( e.keyCode ? e.keyCode : e.which );

			// - no key ------ mouseclick - enter
			if ( code == -1 || code == 1 || code == 13 ) {
				var sampleRequest = {
					origin : startInput,
					destination : endInput,
					travelMode : google.maps.TravelMode.DRIVING,
					unitSystem : google.maps.UnitSystem.METRIC
				};
				directionsService.route( sampleRequest, function(response, status) {
					console.log( response );

					if ( status == google.maps.DirectionsStatus.OK ) {
						_this.directionsDisplay.setDirections( response );
						// call callback function
						if(undefined != callbackFnc && 'function' == typeof callbackFnc) {
							callbackFnc(response);
						}
					}
				} );
			}
		}
		;

		console.log( _showRoute );

		//startInput.keyup( _showRoute );
		//endInput.keyup( _showRoute );
		if ( sendBt )
			sendBt.click( _showRoute );

		_showRoute();

	}; // showDirections()

	return GoogleMap;

} ) ); // define module
