<?php
namespace JumpUpDriver\Json;

class VehicleJsonWrapper {
    const FIELD_NUMBER_VEHICLES = "numberVehicles";
    const FIELD_VEHICLES = "vehicles";
    const FIELD_MESSAGES = "messages";
    protected $vehicles;
    protected $messages;
    
   

	public function __construct() {
        $this->vehicles = array();
    }
    
    public function getVehicles() {
        return $this->vehicles;
    }
    
    public function setVehicles(array $vehicles) {
        $this->vehicles = $vehicles;
    }
    
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
    
    public function toJson() {
     $jsonArr = array();
     $jsonArr[self::FIELD_NUMBER_VEHICLES] = sizeof($this->vehicles);
     $jsonArr[self::FIELD_VEHICLES] = array();
     $jsonArr[self::FIELD_MESSAGES] = $this->getMessages();

     foreach ($this->vehicles as $vehicle) {
       array_push($jsonArr[self::FIELD_VEHICLES], $vehicle->toJson());
      }
     return $jsonArr;
    }
    
}