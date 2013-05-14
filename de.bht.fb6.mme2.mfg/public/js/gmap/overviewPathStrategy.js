/*
 * Project: JumpUp
 * Realm: Frontend
 * 
 * This is the overviewPathStrategy module. It will encapsulate several algorithm to determine which coordinates in the overviewPath are passed to the backend.
 *
 * gmap\oveviewPathStrategy.js
 * Copyright (c) 2013 Martin Schultz & Sascha Feldmann
 * license    GNU license
 * version    1.0
 * since      7.05.2013
 */

define( [], ( function() {
	/*
	 * Abstract super class.
	 */
	var OverviewPathStrategy = function() {
	};
	/*
	 * Take an array (overviewPath) and return a string in the form
	 * latitude,longitude;latitude,longitude ...
	 */
	OverviewPathStrategy.prototype.execute = function(overviewPath) {
		throw new Error( 'Strategy#execute needs to be overridden.' );
	};

	var EveryTenthStrategy = function() {
	};

	/*
	 * Let EveryTenthStrategy extend OverviewPathStrategy
	 */
	EveryTenthStrategy.prototype = Object.create( OverviewPathStrategy.prototype );

	/*
	 * Take every tenth overviewPath.
	 */
	EveryTenthStrategy.prototype.execute = function(overviewPath) {
		var stringConcat = "";
		for ( var index = 0; index < overviewPath.length; index += 10 ) {
			// kb = latitude / breite; lb = longitude / länge
			stringConcat += overviewPath[ index ].lat() + ","
					+ overviewPath[ index ].lng() + ";";
		}
		return stringConcat;

	};

	return EveryTenthStrategy;
} ) );