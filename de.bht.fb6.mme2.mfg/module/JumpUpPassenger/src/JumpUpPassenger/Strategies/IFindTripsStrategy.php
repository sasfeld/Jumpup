<?php
namespace JumpUpPassenger\Strategies;

use JumpUpDriver\Models\Trip;
use JumpUpUser\Models\User;
/**
 * 
* The IFindTripsStrategy is an API for all strategies, which find trips that match the user's location and destination.
* 
* Each strategy returns an array of Trip for the matching trips.
*
* @package    JumpUpPassenger\Strategies
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      07.05.2013
 */
interface IFindTripsStrategy {
    /**
     * Find the nearest trips for the given parameters.
     * @param String $location the location, pair of latitude / longitude in the form (latitude, longitude)
     * @param String $destination the destination, pair of latitude / longitude in the form (latitude, longitude)
     * @param dateFrom the left side of the date range
     * @param dateTo the right side of the date range
     * @param priceFrom the left side of the price range
     * @param priceTo the right side of the price range
     * @param array $inTrips the array of Trip which will be used to look up for trips.
     * @param passenger User the passenger who is looking for near trips.
     * @param distance the maximum distance the passenger wishes between his location and the driver's trip.
     * @return array of Trip the matched trips.
     */
    function findNearTrips($location, $destination, $dateFrom, $dateTo, $priceFrom, $priceTo, array $inTrips, User $passenger, $distance);  

  
    
}