<?php

namespace JumpUpPassenger\Filter;
use JumpUpDriver\Models\Trip;
use JumpUpUser\Models\User;
/**
 *
 *
 *
 * This class just keeps the information for each ATripFilter so it's easy to extend the fields which shall be included in the filter function.
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
class FindTripsContainer {
	protected $trips;
	protected $passenger;
	protected $priceFrom;
	protected $priceTo;
	protected $dateFrom;
	protected $dateTo;
	
	/**
	 * @return the $priceFrom
	 */
	public function getPriceFrom() {
		return $this->priceFrom;
	}

	/**
	 * @return the $priceTo
	 */
	public function getPriceTo() {
		return $this->priceTo;
	}

	/**
	 * @return the $dateFrom
	 */
	public function getDateFrom() {
		return $this->dateFrom;
	}

	/**
	 * @return the $dateTo
	 */
	public function getDateTo() {
		return $this->dateTo;
	}

	/**
	 * Construct a new container. 
	 * @param Trip $trip the trip to be keeped.
	 * @param User $user the user to be keeped.
	 */
	public function __construct(array $trips, User $user, $priceFrom, $priceTo, $dateFrom, $dateTo) {
		$this->trips = $trips;
		$this->passenger = $user;
		$this->dateFrom = $dateFrom;
		$this->dateTo = $dateTo;
		$this->priceFrom = $priceFrom;
		$this->priceTo = $priceTo;
	}
	
	/**
	 * @return array the $trips
	 */
	public function getTrips() {
		return $this->trips;
	}

	/**
	 * @return the $passenger
	 */
	public function getPassenger() {
		return $this->passenger;
	}
	
}

?>