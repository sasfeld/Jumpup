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
			$driversLocation = $this->_toLatLng($trip->getStartCoord());
			$driversDestination = $this->_toLatLng($trip->getEndCoord());

			$distanceLocation = $this->_calculateDistance($passengerLocation, $driversLocation);
			$distanceDestination = $this->_calculateDistance($passengersDestination, $driversDestination);
			
			/*
			 * passenger's location must be near to the driver's location OR any of his route waypoints.
			 * passenger's destination must also be near to the driver's destination OR any of his route waypoints.
			 */
			if (($distanceLocation < $tripsContainer->getMaxDistance() || $this->_isPointNearRoute($passengerLocation, $trip->getOverviewPath(), $tripsContainer->getMaxDistance())) && ($distanceDestination < $tripsContainer->getMaxDistance()  || $this->_isPointNearRoute($passengersDestination, $trip->getOverviewPath(), $tripsContainer->getMaxDistance()))) {
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
	
	/**
	 * Take the overviewPath as given in the database and convert it to an 2d-array. Elements are arrays with two elements (LAT and LNG). Use the constants above to access those.
	 * @param String $overviewPath from the database
	 * @return 2d-array whose elements are arrays with two elements LAT and LNG
	 */	
	protected function _overviewPathToLatLng($overviewPath) {
		$latLngStrings = explode(";" , $overviewPath);
		$returnArray = array();
		foreach ($latLngStrings as $latLngString) {
			if("" !== $latLngString) {
				array_push($returnArray, $this->_toLatLng($latLngString));
			}
		}
		return $returnArray;
	}
	
	/**
	 * Check whether the given point is near the driver's path.
	 * @param array $passengerPoint
	 * @param String $driverPath as stored in the database.
	 */
	protected function _isPointNearRoute($passengerPoint, $driverPath, $maxDistance) {
		$pathLatLngArr = $this->_overviewPathToLatLng($driverPath);
		foreach ($pathLatLngArr as $singleWaypoint) {
			$distance = $this->_calculateDistance($passengerPoint, $singleWaypoint);
			if($distance < $maxDistance) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Calculate the distance between two points given as coordinates.
	 * @param array $coord1 an array with the two elements LAT and LNG. Set those via the constants above.
	 * @param array $coord2 an array with the two elements LAT and LNG. Set those via the constants above. 
	 * @return int the distance in kilometers between the given points
	 */
	protected function _calculateDistance(array $coord1, array $coord2) {
		$point1LatRad = deg2rad($coord1[self::LAT]);
		$point1LngRad = deg2rad($coord1[self::LNG]);
		$point2LatRad = deg2rad($coord2[self::LAT]);
		$point2LngRad = deg2rad($coord2[self::LNG]);
		
		// ARCCOS[ SIN(Breite1)*SIN(Breite2) + COS(Breite1)*COS(Breite2)*COS(Länge2-Länge1) ]
		$distanceBetween = self::EQUATOR_RADIUS * acos( sin($point1LatRad) * sin($point2LatRad) + cos($point1LatRad) * cos($point2LatRad)*cos($point2LngRad - $point1LngRad));
		return $distanceBetween;
	}
}

?>