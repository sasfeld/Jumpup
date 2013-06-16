<?php

namespace JumpUpPassenger\Filter;

/**
 *
 * Decorator pattern:
 * the ATripFilter is the super class and API for concrete filters. The super class can be extended by other filters. 
 *
 * @package JumpUpPassenger\Filter
 * @subpackage
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 16.06.2013
 * */
class ATripFilter {
	protected $decorationFilter;
	
	/**
	 * Construct a new Filter. You can add another filter if you want to use multiple filters.
	 * @param ATripFilter $decorationFilter the additional filter. You can leave it null.
	 */
	function __construct(ATripFilter $decorationFilter = null) {
		$this->decorationFilter = $decorationFilter;
	}
	
	/**
	 * Filter the trips within the tripsContainer. If the current filter is decorated by another filter, return those filtered trips and your own.
	 * @param FindTripsContainer $tripsContainer
	 * @return array containing the filtered Trips.
	 */
	public function filter(FindTripsContainer $tripsContainer) {
		if(null !== $this->decorationFilter) {
			return $this->decorationFilter->filter($tripsContainer);
		}
		return null;
	}
	
}

?>