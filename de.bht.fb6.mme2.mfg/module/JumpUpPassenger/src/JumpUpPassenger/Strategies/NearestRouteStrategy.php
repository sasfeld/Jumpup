<?php
namespace JumpUpPassenger\Strategies;

use JumpUpPassenger\Filter\FindTripsContainer;
use JumpUpPassenger\Filter\PriceFilter;
use JumpUpPassenger\Filter\DateFilter;
use JumpUpUser\Models\User;
use JumpUpPassenger\Filter\AlreadyBookedFilter;
use JumpUpPassenger\Filter\MaxSeatsFilter;
use JumpUpPassenger\Filter\PassengersLocationFilter;
use JumpUpPassenger\Filter\PassengerHimselfFilter;
/**
 *
 * Just for testing. This strategy just returns all trips from the DB
 *
 * @package    JumpUpPassenger\Strategies
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      07.05.2013
 */
class NearestRouteStrategy implements  IFindTripsStrategy {
	protected $filter;
	
	public function __construct() {
		// set filters
		$this->filter = new PassengersLocationFilter(new DateFilter(new PriceFilter(new AlreadyBookedFilter(new MaxSeatsFilter(new PassengerHimselfFilter())))));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JumpUpPassenger\Strategies.IFindTripsStrategy::findNearTrips()
	 */
	public function findNearTrips($location, $destination, $dateFrom, $dateTo, $priceFrom, $priceTo, array $inTrips, User $passenger, $distance) {
		$container = new FindTripsContainer($inTrips, $passenger, $priceFrom, $priceTo, $dateFrom, $dateTo, $location, $destination, $distance);
		
		// delegate to filter
		$resultingTrips = $this->filter->filter($container);
		return $resultingTrips;				
	}
	
}