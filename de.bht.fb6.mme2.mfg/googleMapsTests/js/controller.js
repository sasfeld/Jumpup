define( [ "jquery", "googlemap" ], ( function($, GoogleMap) {

	/**
	 * @param gmap
	 *          The GoogleMap instance
	 */
	var Controller = function() {

		var options = {
			"map_canvas" : $( "#map_canvas" )[ 0 ],
			"textbox" : $( "#textbox" )[ 0 ],
			"directions" : $( "#directions" )[ 0 ]
		};

		this.gmap = new GoogleMap( options );
	}; // Controller constructor

	Controller.prototype.insertRoute = function() {

		var start;
		var end;

		var startInput = $( "#startInput" );
		var endInput = $( "#endInput" );
		var sendBt = $( "#sendBt" );

		this.gmap.setAutocomplete( startInput, function(place) {
			start = place.geometry.location;
		} );

		this.gmap.setAutocomplete( endInput, function(place) {
			end = place.geometry.location;
		} );

		var _this = this;
		function _showRoute(e) {
			var code = -1;
			if ( e )
				code = ( e.keyCode ? e.keyCode : e.which );

			// - no key ------ mouseclick - enter
			if ( code == -1 || code == 1 || code == 13 ) {
				_this.gmap.showRoute( start, end );
			}
		}

		startInput.keyup( _showRoute );
		endInput.keyup( _showRoute );
		if ( sendBt )
			sendBt.click( _showRoute );

	}; // insertRoute()

	return Controller;

} ) ); // define module
