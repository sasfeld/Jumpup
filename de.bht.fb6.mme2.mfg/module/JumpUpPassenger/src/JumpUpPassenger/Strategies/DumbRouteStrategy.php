<?php
namespace JumpUpPassenger\Strategies;

use JumpUpDriver\Models\Trip;
use JumpUpPassenger\Filter\AlreadyBookedFilter;
use JumpUpPassenger\Filter\MaxSeatsFilter;
use JumpUpPassenger\Filter\FindTripsContainer;
use JumpUpUser\Models\User;
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
class DumbRouteStrategy implements  IFindTripsStrategy {
	protected $filter;
	
	public function __construct() {
		// set filters
		$this->filter = new AlreadyBookedFilter(new MaxSeatsFilter());
	}
	
    /**
     * (non-PHPdoc)
     * @see JumpUpPassenger\Strategies.IFindTripsStrategy::findNearTrips()
     */
    public function findNearTrips($location, $destination, $dateFrom, $dateTo, $priceFrom, $priceTo, array $inTrips, User $passenger) {
		$container = new FindTripsContainer($inTrips, $passenger);
		
		// delegate to filter
		$resultingTrips = $this->filter->filter($container);    	
    	return $resultingTrips; 
    }
    
   
}