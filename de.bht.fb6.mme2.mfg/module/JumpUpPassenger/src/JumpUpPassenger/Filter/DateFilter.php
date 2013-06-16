<?php

namespace JumpUpPassenger\Filter;

use JumpUpPassenger\Filter\ATripFilter;

/**
 *
 * This ATripFilter filters trips within the passenger's desired date range.
 *
 * @package JumpUpPassenger\Filter
 * @subpackage
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de) and Martin Schultz
 * @license GNU license
 * @version 1.0
 * @since 16.06.2013
 **/
class DateFilter extends ATripFilter {
	
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
			// proof time
			$currentDate = $this->__toDate($trip->getStartDate(), "-");
			$dateFrom = $this->__toDate($tripsContainer->getDateFrom(), "-");
			$dateTo = $this->__toDate($tripsContainer->getDateTo(), "-");
				
			if( $currentDate >= $dateFrom && $currentDate <= $dateTo ) {
				array_push($filteredTrips, $trip);
			}			
		}
		return $filteredTrips;
	}
	
	/**
	 * Convert the string representation of Zend's DateField to a timestamp.
	 * @param unknown $string a String representation in the form "YYYY-MM-DD"
	 * @param unknown $separator the separator. default is = "-" 
	 * @return int the timestamp (milliseconds since 01.01.1970)
	 */
	private function __toDate($string, $separator = "-") {
		$dateArray = explode($separator,$string);
		return mktime(0,0,0,$dateArray[1],$dateArray[2],$dateArray[0]);
	}
}

?>