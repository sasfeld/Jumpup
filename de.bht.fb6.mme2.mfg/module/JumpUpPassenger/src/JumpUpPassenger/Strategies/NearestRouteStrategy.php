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
class NearestRouteStrategy implements  IFindTripsStrategy {
	/**
	 * (non-PHPdoc)
	 * @see JumpUpPassenger\Strategies.IFindTripsStrategy::findNearTrips()
	 */
	public function findNearTrips($location, $destination, $dateFrom, $dateTo, $priceFrom, $priceTo, array $inTrips) {

		$dateFrom = $this->__toDate($dateFrom);
		$dateTo = $this->__toDate($dateTo);
		
		$outTrips = array();

		foreach ($inTrips as $current) {
			// proof time
			$currentDate = $this->__toDate($current->getStartDate());
			
			if( $currentDate >= $dateFrom && $currentDate <= $dateTo ) {
				$outTrips[] = $current;
			}
		}


		return $outTrips;
	}

	private function __toDate($string) {
		$dateArray = explode("-",$string);
		return mktime(null,null,null,$dateArray[1],$dateArray[2],$dateArray[0]);
	}
}