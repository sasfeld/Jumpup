<?php

namespace JumpUpPassenger\Filter;

use JumpUpPassenger\Filter\ATripFilter;

/**
 *
 * This ATripFilter filters trips within the passenger's desired price range.
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
class PriceFilter extends ATripFilter {
	
	/**
	 * Construct a new PriceFilter.
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
			// @TODO later, the price will be depending on the passenger's location....
			$pricePerPassenger = $trip->getPrice() / $trip->getMaxSeats();
			
			if ($pricePerPassenger < $tripsContainer->getPriceTo() && $pricePerPassenger > $tripsContainer->getPriceFrom()) {
				array_push($filteredTrips, $trip);
			}
		}
		return $filteredTrips;
	}
}

?>