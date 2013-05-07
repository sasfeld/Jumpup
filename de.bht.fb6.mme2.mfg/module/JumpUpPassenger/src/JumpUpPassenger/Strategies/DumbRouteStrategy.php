<?php
namespace JumpUpPassenger\Strategies;

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
    /**
     * (non-PHPdoc)
     * @see JumpUpPassenger\Strategies.IFindTripsStrategy::findNearTrips()
     */
    public function findNearTrips($location, $destination, $dateFrom, $dateTo, $priceFrom, $priceTo, array $inTrips) {
        return $inTrips; // do it yourself, dumbass! :P
    }
}