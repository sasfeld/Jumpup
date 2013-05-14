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
		this.allRouteObjects = new Array();
		this.geocoder;
		this.infoWindow;

		this.mapsLoaded();
	}; // GoogleMap constructor

	GoogleMap.prototype.mapsLoaded = function() {
		this.directionsService = new google.maps.DirectionsService();
		this.geocoder = new google.maps.Geocoder();
		this.infowindow = new google.maps.InfoWindow();
		var torun = new google.maps.LatLng( 53.01357, 18.597665 );
		this.map = new google.maps.Map( this.map_canvas, {
			mapTypeId : google.maps.MapTypeId.ROADMAP,
			center : torun,
			zoom : 5
		} );
	}; // mapsLoaded()

	GoogleMap.prototype.removeRoutes = function() {

		while ( this.allRouteObjects.length > 0 ) {
			var routeObject = this.allRouteObjects.pop();
			var display = routeObject[ "display" ];
			var polyline = routeObject[ "polyline" ];

			if ( display ) {
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
		for(var j = 0; j < this.allRouteObjects.length; ++j) {
			this.deselect( j );
		}
		
		var routeObject = this.allRouteObjects[ i ];
		console.log(routeObject);
		
		if ( this.textbox && routeObject )
			routeObject[ "display" ].setPanel( this.textbox );
		
		if( routeObject[ "polyline" ] )
			routeObject[ "polyline" ].setOptions({
				strokeOpacity : 0.4,
			});

	}; // select()
	
	GoogleMap.prototype.deselect = function(i) {
		
		var routeObject = this.allRouteObjects[ i ];
		
		if( routeObject[ "polyline" ] )
			routeObject[ "polyline" ].setOptions({
				strokeOpacity : 0,
			});

	}; // deselect()

	GoogleMap.prototype.showRoute = function(startLatLng, endLatLng, callbackFnc) {

		var _this = this;
		var routeObject = new Object();
		var i = this.allRouteObjects.length;

		routeObject[ "display" ] = new google.maps.DirectionsRenderer( {
			map : _this.map,
			preserveViewport : true,
			draggable : true,
		} );

		routeObject[ "polyline" ] = new google.maps.Polyline( {
			strokeOpacity: 0,
			strokeColor: "red",
			strokeWeight: 10,
			map: _this.map,
			zIndex: 100000,
		});

		_this.allRouteObjects[ i ] = routeObject;

		// direction changed
		google.maps.event
				.addListener( routeObject[ "display" ], "directions_changed", function() {
					// callback ( function param )
					var directions = routeObject[ "display" ].getDirections();
					callbackFnc( directions );

					routeObject[ "polyline" ]
							.setPath( directions.routes[ 0 ].overview_path );

				} );

		// mouseover route
		google.maps.event
				.addListener( routeObject[ "polyline" ], "mouseover", function() {
					console.log( "over" );
					_this.select( i );
				} );

		// map.setCenter( new google.maps.LatLng( start ) );

		var sampleRequest = {
			origin : startLatLng,
			destination : endLatLng,
			travelMode : google.maps.TravelMode.DRIVING,
			unitSystem : google.maps.UnitSystem.METRIC,
		};
		
		this.directionsService.route( sampleRequest, function(response, status) {
			if ( status == google.maps.DirectionsStatus.OK ) {
				routeObject[ "display" ].setDirections( response );
			}
		} );

		_this.select( i );
		
	}; // showRoute()

	return GoogleMap;

} ) ); // define module
