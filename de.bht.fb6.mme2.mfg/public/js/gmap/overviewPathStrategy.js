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

define( [ "lib/vec2" ], ( function(vec2) {
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

	/**
	 * Line from a to b. Return distance of p from line.
	 */
	function distance(a, b, p) {
		var vecA = vec2.fromLatLngToVec2( a );
		var vecB = vec2.fromLatLngToVec2( b );
		var vecP = vec2.fromLatLngToVec2( p );

		// project point on line, get parameter of that projection point
		var t = vec2.projectPointOnLine( vecP, vecA, vecB );

		// outside the line segment?
		if ( t < 0.0 || t > 1.0 ) {
			// infinite
			return Number.MAX_VALUE;
		}

		// coordinates of the projected point
		var pp = vec2.add( vecA, vec2.mult( vec2.sub( vecB, vecA ), t ) );

		// distance of the point from the line
		var d = vec2.length( vec2.sub( pp, vecP ) );

		return d;
	}

	var ByDistanceStrategy = function() {
	};

	/*
	 * Let ByDistanceStrategy extend OverviewPathStrategy
	 */
	ByDistanceStrategy.prototype = Object.create( OverviewPathStrategy.prototype );

	/*
	 * Take when distance is to high.
	 */
	ByDistanceStrategy.prototype.execute = function(overviewPath) {
		var stringConcat = "";
		var maxDistance = 0.01;
		var currentA = overviewPath[ 0 ];
		var currentB = overviewPath[ overviewPath.length - 1 ];
		var debugLength = 0;

		// add start
		stringConcat += currentA.lat() + "," + currentA.lng() + ";";

		for ( var index = 1; index < overviewPath.length - 1; ++index ) {
			if ( distance( currentA, currentB, overviewPath[ index ] ) > maxDistance ) {
				++debugLength;
				currentA = overviewPath[ index ];
				stringConcat += currentA.lat() + "," + currentA.lng() + ";";
			}
		}

		// add end
		stringConcat += currentB.lat() + "," + currentB.lng() + ";";

		console.log( "     before: " + overviewPath.length + " after: "
				+ debugLength );

		return stringConcat;

	};

	// return EveryTenthStrategy;
	return ByDistanceStrategy;
} ) );