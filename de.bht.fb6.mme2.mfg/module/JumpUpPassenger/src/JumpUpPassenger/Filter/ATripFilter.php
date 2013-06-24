<?php

namespace JumpUpPassenger\Filter;

use Application\Util\ExceptionUtil;
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
abstract class ATripFilter {
	protected $decorationFilter;
	/**
	 * store properties for values the filters want to export to the client (like the passenger's distance to the trip or similiar things).
	 * @var associative array, can be accesed via the constant keys defined in the filter implementations.
	 */
	protected $properties;
	
	/**
	 * Construct a new Filter. You can add another filter if you want to use multiple filters.
	 * @param ATripFilter $decorationFilter the additional filter. You can leave it null.
	 */
	function __construct(ATripFilter $decorationFilter = null) {
		$this->decorationFilter = $decorationFilter;
		$this->properties = array();
	}
	
	/**
	 * Get the property stored by a filter implementation. Should only be called after the filter() method was called.
	 * @param String $key. The key for the specific properties of the filter implementations can be found in the filter implementations' class constants.
	 * @return \JumpUpPassenger\Filter\associative
	 */
	public function getProperty($key) {
		if(!is_string($key)) {
			throw ExceptionUtil::throwInvalidArgument('$key', 'string', $key);
		}
		$property = $this->properties[$key];
		if(null === $property) { // search recursive
			$property = $this->decorationFilter->getProperty($key);
		}
		return $property;
	}
	
	/**
	 * Set the property to be exported to the client.
	 * @param String $key
	 * @param whatever $value
	 */
	protected function setProperty($key, $value) {
		if(!is_string($key)) {
			throw ExceptionUtil::throwInvalidArgument('$key', 'string', $key);
		}
		$property[$key] = $value;
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