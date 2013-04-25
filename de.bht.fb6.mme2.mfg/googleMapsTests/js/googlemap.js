// define( [ "async!http://maps.google.com/maps/api/js?key=AIzaSyBsZwdJI29OQJgyUNPbucRH_l5r_NqSuH4&sensor=true" ], ( function() {
define( [ "async!http://maps.google.com/maps/api/js?v=3&sensor=true" ], ( function() {

	/**
	 * @param divs
	 *          Is a assosiativ array. Avalabile options: 'map_canvas', 'textbox',
	 *          'geocoding', 'directions'
	 */
	var GoogleMap = function(divs) {

		console.log( google );

		this.map_canvas = divs[ 'map_canvas' ];
		this.textbox = divs[ 'textbox' ];
		this.geocoding = divs[ 'geocoding' ];
		this.directions = divs[ 'directions' ];

		if ( !map_canvas )
			throw "map_canvas is required for the map.";

		this.map;
		this.infowindow;
		this.directionsService;
		this.directionsDisplay;
		this.geocoder;
		this.infoWindow;
		this.marker;

		this.loadMap();
	}; // GoogleMap constructor

	GoogleMap.prototype.mapsLoaded = function() {
		directionsService = new this.google.maps.DirectionsService();
		geocoder = new this.google.maps.Geocoder();
		infowindow = new this.google.maps.InfoWindow();
		var torun = new this.google.maps.LatLng( 53.01357, 18.597665 );
		map = new this.google.maps.Map( this.map_canvas, {
			mapTypeId : this.google.maps.MapTypeId.ROADMAP,
			center : torun,
			zoom : 5
		} );
		this.google.maps.event
				.addListener( map, 'click', function(e) {
					geocoder
							.geocode( {
								'latLng' : e.latLng
							}, function(results, status) {
								if ( status == google.maps.GeocoderStatus.OK ) {
									if ( results[ 0 ] ) {
										if ( marker ) {
											marker.setPosition( e.latLng );
										} else {
											marker = new google.maps.Marker( {
												position : e.latLng,
												map : map
											} );
										}
										infowindow.setContent( results[ 0 ].formatted_address );
										infowindow.open( map, marker );
									} else {
										document.getElementById( 'geocoding' ).innerHTML = 'No results found';
									}
								} else {
									document.getElementById( 'geocoding' ).innerHTML = 'Geocoder failed due to: '
											+ status;
								}
							} );
				} );
		this.showDirections();
	}; // mapsLoaded()

	GoogleMap.prototype.showDirections = function() {
		directionsDisplay = new this.google.maps.DirectionsRenderer( {
			map : map,
			preserveViewport : true,
			draggable : true
		} );
		directionsDisplay.setPanel( document.getElementById( 'textbox' ) );
		var sampleRequest = {
			origin : 'Warschau',
			destination : 'Berlin, Germany',
			travelMode : this.google.maps.TravelMode.DRIVING,
			unitSystem : this.google.maps.UnitSystem.METRIC
		};
		directionsService.route( sampleRequest, function(response, status) {
			if ( status == this.google.maps.DirectionsStatus.OK ) {
				directionsDisplay.setDirections( response );
			}
		} );
	}; // showDirections()

	GoogleMap.prototype.loadMap = function() {
		google.load( 'maps', '3.7', {
			'other_params' : 'sensor=false&libraries=places',
			'callback' : this.mapsLoaded
		} );
	}; // loadMap()

	return GoogleMap;

} ) ); // define module
