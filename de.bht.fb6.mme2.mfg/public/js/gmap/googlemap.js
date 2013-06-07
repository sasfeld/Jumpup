define( [
		"jquery",
		"gmap/overviewPathStrategy",
		"async!http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBsZwdJI29OQJgyUNPbucRH_l5r_NqSuH4&sensor=true" ], ( function(
		$, OverviewPathStrategy) {

	/**
	 * @param divs
	 *          Is a assosiativ array. Avalabile options: 'mode', 'map_canvas',
	 *          'textbox', 'geocoding'
	 */
	var GoogleMap = function(options) {

		this.map_canvas = options[ 'map_canvas' ];
		this.textbox = options[ 'textbox' ];
		this.draggable = options[ "draggable" ] || false;
		this.selectable = options[ "selectable" ] || false;
		this.showDirectionsPanel = options[ "showDirectionsPanel" ] || false;

		if ( !this.map_canvas )
			throw "map_canvas is required for the map.";

		this.map;
		this.infowindow;
		this.directionsService;
		this.allRouteObjects = new Array();
		this.geocoder;
		this.infoWindow;

		this.directionsService = new google.maps.DirectionsService();
		this.geocoder = new google.maps.Geocoder();
		this.infowindow = new google.maps.InfoWindow();
		var torun = new google.maps.LatLng( 53.01357, 18.597665 );
		this.map = new google.maps.Map( this.map_canvas, {
			mapTypeId : google.maps.MapTypeId.ROADMAP,
			center : torun,
			zoom : 5
		} );
	}; // GoogleMap constructor

	GoogleMap.prototype.removeRoutes = function() {

		while ( this.allRouteObjects.length > 0 ) {
			var routeObject = this.allRouteObjects.pop();
			var display = routeObject[ "display" ];
			var polyline = routeObject[ "polyline" ];

			if ( display && !this.showDirectionsPanel ) {
				display.setMap( null );
				display.setPanel( null );
			}

			if ( polyline ) {
				polyline.setPath( [] );
				console.log( polyline );
			}
		}

	}; // removeRoutes()

	GoogleMap.prototype.setAutocomplete = function(input, placeChanged) {

		var autocomplete = new google.maps.places.Autocomplete( input[ 0 ] );
		google.maps.event.addListener( autocomplete, 'place_changed', function() {
			placeChanged( autocomplete.getPlace() );
		} );
		// autocomplete.bindTo( 'bounds', map );

	}; // setAutocomplete()

	GoogleMap.prototype.select = function(i) {

		// deselect all
		for ( var j = 0; j < this.allRouteObjects.length; ++j ) {
			this.deselect( j );
		}

		var routeObject = this.allRouteObjects[ i ];
		console.log( routeObject );

		if ( this.showDirectionsPanel && this.textbox && routeObject )
			routeObject[ "display" ].setPanel( this.textbox );

		if ( routeObject[ "polyline" ] )
			routeObject[ "polyline" ].setOptions( {
				strokeOpacity : 0.4,
			} );

	}; // select()

	GoogleMap.prototype.deselect = function(i) {

		var routeObject = this.allRouteObjects[ i ];

		if ( routeObject[ "polyline" ] )
			routeObject[ "polyline" ].setOptions( {
				strokeOpacity : 0,
			} );

		if ( routeObject[ "display" ] )
			routeObject[ "display" ].setPanel( null );

	}; // deselect()

	GoogleMap.prototype.showRoute = function(startLatLng, endLatLng, waypoints,
			callbackFnc) {

		console.log( google.maps );

		// convert waypoint array
		if ( waypoints )
			for ( var i = 0; i < waypoints.length; i++ ) {
				waypoints[ i ] = {
					"location" : waypoints[ i ],
					"stopover" : true,
				};
			}

		var _this = this;
		var routeObject = new Object();
		var i = this.allRouteObjects.length;

		routeObject[ "display" ] = new google.maps.DirectionsRenderer( {
			map : _this.map,
			preserveViewport : true,
			draggable : _this.draggable,
		} );

		if ( _this.selectable )
			routeObject[ "polyline" ] = new google.maps.Polyline( {
				strokeOpacity : 0,
				strokeColor : "red",
				strokeWeight : 10,
				map : _this.map,
				zIndex : 0,
			} );

		_this.allRouteObjects[ i ] = routeObject;

		// comment when not in debug
		var overviewStrategy = new OverviewPathStrategy();

		// direction changed
		google.maps.event
				.addListener( routeObject[ "display" ], "directions_changed", function() {
					// callback ( function param )
					var directions = routeObject[ "display" ].getDirections();
					callbackFnc( directions );

					if ( routeObject[ "polyline" ] )
						routeObject[ "polyline" ].setPath( overviewStrategy
								.execute( directions.routes[ 0 ].overview_path ) );
					// routeObject[ "polyline" ]
					// .setPath( directions.routes[ 0 ].overview_path );

				} );

		// mouseover route
		if ( routeObject[ "polyline" ] )
			google.maps.event
					.addListener( routeObject[ "polyline" ], "mouseover", function() {
						console.log( "over" );
						_this.select( i );
					} );

		// map.setCenter( new google.maps.LatLng( start ) );

		var sampleRequest = {
			"origin" : startLatLng,
			"destination" : endLatLng,
			"waypoints" : waypoints,
			"optimizeWaypoints" : true,
			"travelMode" : google.maps.TravelMode.DRIVING,
			"unitSystem" : google.maps.UnitSystem.METRIC,
		};

		this.directionsService.route( sampleRequest, function(response, status) {
			if ( status == google.maps.DirectionsStatus.OK ) {
				routeObject[ "display" ].setDirections( response );
			}
		} );

		if ( routeObject[ "polyline" ] )
			_this.select( i );

	}; // showRoute()

	return GoogleMap;

} ) ); // define module
