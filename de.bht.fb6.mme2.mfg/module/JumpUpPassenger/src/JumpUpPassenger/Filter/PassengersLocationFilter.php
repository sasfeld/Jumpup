<?php

namespace JumpUpPassenger\Filter;

use JumpUpPassenger\Filter\ATripFilter;
use JumpUpPassenger\Util\GmapCoordUtil;

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
			$passengerLocation = GmapCoordUtil::toLatLng($tripsContainer->getStartCoord());
			$passengersDestination = GmapCoordUtil::toLatLng($tripsContainer->getEndCoord());			
			$driversLocation = GmapCoordUtil::toLatLng($trip->getStartCoord());
			$driversDestination = GmapCoordUtil::toLatLng($trip->getEndCoord());

			$distanceLocation = GmapCoordUtil::calculateDistance($passengerLocation, $driversLocation);
			$distanceDestination = GmapCoordUtil::calculateDistance($passengersDestination, $driversDestination);
			
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
	 * Take the overviewPath as given in the database and convert it to an 2d-array. Elements are arrays with two elements (LAT and LNG). Use the constants above to access those.
	 * @param String $overviewPath from the database
	 * @return 2d-array whose elements are arrays with two elements LAT and LNG
	 */	
	protected function _overviewPathToLatLng($overviewPath) {
		$latLngStrings = explode(";" , $overviewPath);
		$returnArray = array();
		foreach ($latLngStrings as $latLngString) {
			if("" !== $latLngString) {
				array_push($returnArray, GmapCoordUtil::toLatLng($latLngString));
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
			$distance = GmapCoordUtil::calculateDistance($passengerPoint, $singleWaypoint);
			if($distance < $maxDistance) {
				return true;
			}
		}
		return false;
	}
	

}

?>