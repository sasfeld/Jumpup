<?php
namespace JumpUpPassenger\Json;

class TripWrapper {
    const FIELD_NUMBER_TRIPS = "numberTrips";
    const FIELD_TRIPS = "trips";
    protected $trips;
    protected $distanceLocationToTrip;
    protected $distanceDestinationToTrip;
    
 

	public function __construct() {
        $this->trips = array();
    }
    
    public function getTrips() {
        return $this->trips;
    }
    
    public function setTrips(array $trips) {
        $this->trips  = $trips;
    }
    
  
    
    public function toJson() {
     $jsonArr = array();
     $jsonArr[self::FIELD_NUMBER_TRIPS] = sizeof($this->trips);
     $jsonArr[self::FIELD_TRIPS] = array();

     foreach ($this->trips as $trip) {
       array_push($jsonArr[self::FIELD_TRIPS], $trip->toJson());
      }
     return $jsonArr;
    }
    
}