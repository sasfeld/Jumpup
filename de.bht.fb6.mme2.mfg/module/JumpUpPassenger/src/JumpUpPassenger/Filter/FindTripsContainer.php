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
	/**
	 * Trips from the entity manager.
	 * @var array of Trip entities
	 */
	protected $trips;
	/**
	 * The user who is requesting the trips.
	 * @var User the passenger
	 */
	protected $passenger;
	/**
	 * User's desired minimum price.
	 * @var int
	 */
	protected $priceFrom;
	/**
	 * User's desired maximum price.
	 * @var int
	 */
	protected $priceTo;
	/**
	 * User's desired minimum date.
	 * @var String
	 */
	protected $dateFrom;
	/**
	 * User's desired maximum date.
	 * @var String
	 */
	protected $dateTo;
	/**
	 * Coordinates (latLng) of passenger's start location.
	 * @var String
	 */
	protected $startCoord;
	/**
	 * Coordinates (latLng) of passenger's end location.
	 * @var String
	 */
	protected $endCoord;
	/**
	 * Maximum desired distance to the driver's route of passenger. 
	 * @var int
	 */
	protected $maxDistance;	
	

	/**
	 * Construct a new container.
	 * @param Trip $trip the trip to be keeped.
	 * @param User $user the user to be keeped.
	 */
	public function __construct(array $trips, User $user, $priceFrom, $priceTo, $dateFrom, $dateTo, $startCoord, $endCoord, $maxDistance) {
		$this->trips = $trips;
		$this->passenger = $user;
		$this->dateFrom = $dateFrom;
		$this->dateTo = $dateTo;
		$this->priceFrom = $priceFrom;
		$this->priceTo = $priceTo;
		$this->startCoord = $startCoord;
		$this->endCoord = $endCoord;
		$this->maxDistance = $maxDistance;
	}
	
	/**
	 * @return the $startCoord
	 */
	public function getStartCoord() {
		return $this->startCoord;
	}

	/**
	 * @return the $endCoord
	 */
	public function getEndCoord() {
		return $this->endCoord;
	}

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
	
	/**
	 * @return the $maxDistance
	 */
	public function getMaxDistance() {
		return $this->maxDistance;
	}
	
}

?>