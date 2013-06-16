<?php

namespace JumpUpPassenger\Filter;

use JumpUpPassenger\Filter\ATripFilter;

/**
 *
 * This ATripFilter filters trips within the passenger's location (specified by latLng values).
 *
 * @package JumpUpPassenger\Filter
 * @subpackage
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 16.06.2013
 **/
class PassengersLocationFilter extends ATripFilter {
	/**
	 * Radius of the earth's equator.
	 * @var unknown
	 */
	const EQUATOR_RADIUS = 6378.137;
	
	/**
	 * key to access the latLng array for the latitute (BREITENGRAD)
	 * @var int
	 */
	const LAT = 0;
	/**
	 * key to access the latLng array for the longitude (LÄNGENGRAD)
	 * @var int
	 */
	const LNG = 1;
	
	/**
	 * Construct a new PassengersLocationFilter..
	 * @param ATripFilter $decorationFilter
	 */
	public function __construct(ATripFilter $decorationFilter = null) {
		parent::__construct ( $decorationFilter );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \JumpUpPassenger\Filter\ATripFilter::filter()
	 */
	public function filter(FindTripsContainer $tripsContainer) {
		$applyTrips = parent::filter($tripsContainer);
		if(null === $applyTrips) {
			$applyTrips = $tripsContainer->getTrips();
		}
		$filteredTrips = array();		
	
		foreach ($applyTrips as $trip) {
			$passengerLocation = $this->_toLatLng($tripsContainer->getStartCoord());
			$passengersDestination = $this->_toLatLng($tripsContainer->getEndCoord());
			
			// we use rad here, because PHP only supports rad					
			$passLocLatRad = deg2rad($passengerLocation[self::LAT]);
			$passLocLngRad = deg2rad($passengerLocation[self::LNG]);
			$passDesLatRad = deg2rad($passengersDestination[self::LAT]);
			$passDesLngRad = deg2rad($passengersDestination[self::LNG]);
			
			
			$driversLocation = $this->_toLatLng($trip->getStartCoord());
			$driversDestination = $this->_toLatLng($trip->getEndCoord());
			$drivLocLatRad = deg2rad($driversLocation[self::LAT]);
			$drivLocLngRad = deg2rad($driversLocation[self::LNG]);
			$drivDesLatRad = deg2rad($driversDestination[self::LAT]);
			$drivDesLngRad = deg2rad($driversDestination[self::LNG]);
			
			// ARCCOS[ SIN(Breite1)*SIN(Breite2) + COS(Breite1)*COS(Breite2)*COS(Länge2-Länge1) ]
			$distanceLocation = self::EQUATOR_RADIUS * acos( sin($passLocLatRad) * sin($drivLocLatRad) + cos($passLocLatRad) * cos($drivLocLatRad)*cos($drivLocLngRad - $passLocLngRad));
			$distanceDestination = self::EQUATOR_RADIUS * acos( sin($passDesLatRad) * sin($drivDesLatRad) + cos($passDesLatRad) * cos($drivDesLatRad)*cos($drivDesLngRad - $passDesLngRad));
			
			/*
			 * passenger's location must be near to the driver's location OR any of his route waypoints.
			 * passenger's destination must also be near to the driver's destination OR any of his route waypoints.
			 */
			if ($distanceLocation < $tripsContainer->getMaxDistance() && $distanceDestination < $tripsContainer->getMaxDistance()) {
				array_push($filteredTrips, $trip);
			}
		}
		return $filteredTrips;
	}
	
	/**
	 * Get an latLng array for a given input string.
	 * @param String $inputCoord as delegated by googleMaps in the frontend (looks like: "(LAT,LNG)"
	 * @return array with two elements (LAT and LNG). access via the constant keys above
	 */
	protected function _toLatLng($inputCoord) {
		$cleanString = str_replace("(", "", $inputCoord);
		$cleanString = str_replace(")", "", $cleanString);
		
		$returnArray = explode(",", $cleanString);	
		return $returnArray;	
	}
}

?>