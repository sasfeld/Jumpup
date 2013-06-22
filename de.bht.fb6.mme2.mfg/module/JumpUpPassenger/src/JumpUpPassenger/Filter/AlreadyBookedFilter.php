<?php

namespace JumpUpPassenger\Filter;

use JumpUpPassenger\Filter\ATripFilter;
use JumpUpPassenger\Filter\FindTripsContainer;
use JumpUpPassenger\Util\IBookingState;

/**
 *
 * This ATripFilter filters the trips for trips that the user (= passenger) hasn't already booked.
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
class AlreadyBookedFilter extends ATripFilter {
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
			$hasBooked = false; // has the passenger booked the trip?
			foreach ($trip->getBookings() as $booking) {
				// given passenger appears in a booking
				if($booking->getPassenger() === $tripsContainer->getPassenger()) {
					$hasBooked = true;
					break;
				} 
			}
			if (!$hasBooked) {
				array_push($filteredTrips, $trip);
			}
		}
		return $filteredTrips;
// 		return null;
	}
}

?>