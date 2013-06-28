<?php

namespace JumpUpPassenger\Filter;

use JumpUpPassenger\Filter\ATripFilter;
use JumpUpPassenger\Filter\FindTripsContainer;
use JumpUpPassenger\Util\IBookingState;

/**
 *
 * This ATripFilter filters the trips for trips from the requesting passenger.
 * @package JumpUpPassenger\Filter
 * @subpackage
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 18.06.2013
 **/
class PassengerHimselfFilter extends ATripFilter {
	public function __construct(ATripFilter $decorationFilter = null) {
		parent::__construct ( $decorationFilter );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \JumpUpPassenger\Filter\ATripFilter::filter()
	 */
	public function filter(FindTripsContainer $tripsContainer) {
		// apply decorated filter (see super class)
		$applyTrips = parent::filter($tripsContainer);
		if(null === $applyTrips) {
			$applyTrips = $tripsContainer->getTrips();
		}
		$filteredTrips = array();		
	
		foreach ($applyTrips as $trip) {
			if($tripsContainer->getPassenger() !== $trip->getDriver()) {				
				array_push($filteredTrips, $trip);
			}
			// else: trip is filtered ;)
		}
		return $filteredTrips;
// 		return null;
	}
}

?>