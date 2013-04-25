// define( [ "async!http://maps.google.com/maps/api/js?key=AIzaSyBsZwdJI29OQJgyUNPbucRH_l5r_NqSuH4&sensor=true" ], ( function() {
define( [ "async!http://maps.google.com/maps/api/js?key=AIzaSyBsZwdJI29OQJgyUNPbucRH_l5r_NqSuH4&sensor=true" ], ( function() {

	/**
	 * @param divs
	 *          Is a assosiativ array. Avalabile options: 'map_canvas', 'textbox',
	 *          'geocoding'
	 */
	var GoogleMap = function(divs) {

		this.map_canvas = divs[ 'map_canvas' ];
		this.textbox = divs[ 'textbox' ];
		this.geocoding = divs[ 'geocoding' ];

		if ( !this.map_canvas || !this.geocoding )
			throw "map_canvas and geocoding is required for the map.";

		this.map;
		this.infowindow;
		this.directionsService;
		this.directionsDisplay;
		this.geocoder;
		this.infoWindow;
		this.marker;

		this.mapsLoaded();
	}; // GoogleMap constructor

	GoogleMap.prototype.mapsLoaded = function() {
		directionsService = new google.maps.DirectionsService();
		geocoder = new google.maps.Geocoder();
		infowindow = new google.maps.InfoWindow();
		var torun = new google.maps.LatLng( 53.01357, 18.597665 );
		map = new google.maps.Map( this.map_canvas, {
			mapTypeId : google.maps.MapTypeId.ROADMAP,
			center : torun,
			zoom : 5
		} );
		google.maps.event.addListener( map, 'click', function(e) {
			geocoder.geocode( {
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
						this.geocoding.innerHTML = 'No results found';
					}
				} else {
					this.geocoding.innerHTML = 'Geocoder failed due to: ' + status;
				}
			} );
		} );
		this.showDirections();
	}; // mapsLoaded()

	GoogleMap.prototype.showDirections = function() {
		directionsDisplay = new google.maps.DirectionsRenderer( {
			map : map,
			preserveViewport : true,
			draggable : true
		} );

		if ( this.textbox )
			directionsDisplay.setPanel( this.textbox );

		var sampleRequest = {
			origin : 'Warschau',
			destination : 'Berlin, Germany',
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
