<?php
namespace JumpUpDriver\Json;

class VehicleJsonWrapper {
    const FIELD_NUMBER_VEHICLES = "numberVehicles";
    const FIELD_VEHICLES = "vehicles";
    protected $vehicles;
    
    public function __construct() {
        $this->vehicles = array();
    }
    
    public function getVehicles() {
        return $this->vehicles;
    }
    
    public function setVehicles(array $vehicles) {
        $this->vehicles = $vehicles;
    }
    
    public function toJson() {
     $jsonArr = array();
     $jsonArr[self::FIELD_NUMBER_VEHICLES] = sizeof($this->vehicles);
     $jsonArr[self::FIELD_VEHICLES] = array();

     foreach ($this->vehicles as $vehicle) {
       array_push($jsonArr[self::FIELD_VEHICLES], $vehicle->toJson());
      }
     return $jsonArr;
    }
    
}