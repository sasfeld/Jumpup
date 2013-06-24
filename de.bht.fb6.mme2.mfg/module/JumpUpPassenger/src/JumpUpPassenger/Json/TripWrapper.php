<?php
namespace JumpUpPassenger\Json;

class TripWrapper {
    const FIELD_NUMBER_TRIPS = "numberTrips";
    const FIELD_TRIPS = "trips";
    const FIELD_MESSAGES = "messages";
    protected $trips;
    protected $messages;
    
 

	/**
	 * @return the $messages
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * @param field_type $messages
	 */
	public function setMessages($messages) {
		$this->messages = $messages;
	}

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
     $jsonArr[self::FIELD_MESSAGES] = $this->getMessages();
     $jsonArr[self::FIELD_TRIPS] = array();

     foreach ($this->trips as $trip) {
       array_push($jsonArr[self::FIELD_TRIPS], $trip->toJson());
      }
     return $jsonArr;
    }
    
}