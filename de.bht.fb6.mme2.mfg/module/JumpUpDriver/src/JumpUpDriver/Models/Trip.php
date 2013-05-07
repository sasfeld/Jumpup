<?php
namespace JumpUpDriver\Models;


use Application\Util\ExceptionUtil;

use JumpUpUser\Models\User;




use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne as OneToOne;
use Doctrine\ORM\Mapping\OneToMany as OneToMany;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;

/**
 * @ORM\Entity
 */
class Trip {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
   private $id;
   /**
    * @ORM\Column(type="string")
    */
   private $startPoint;
   /**
    * @ORM\Column(type="string", nullable=true)
    */
   private $startCoordinate;
   /**
    * @ORM\Column(type="string")
    */
   private $endPoint;
   /**
    * @ORM\Column(type="string", nullable=true)
    */
   private $endCoordinate;
   /**
     * @OneToMany(targetEntity="Waypoint", mappedBy="parentTrip")
   */
   private $waypoints;
   /**
    * @ORM\Column(type="string")
    */
   private $startDate;
   /**
    * @ORM\Column(type="integer")
    */
   private $price;
   /**
    * @ORM\Column(type="integer")
    */
   private $duration; // seconds
   /**
    * @ORM\Column(type="integer")
    */
   private $distance; // meter
   /**
   * @ManyToOne(targetEntity="JumpUpUser\Models\User")
   * */
   private $driver;
   /**
    * @ManyToOne(targetEntity="JumpUpDriver\Models\Vehicle") 
    */
   private $vehicle;
   /**
    * @ORM\Column(type="text", nullable=true)
    */
   private $overviewPath;
   
   
   public function  setStartPoint($startPoint) {
     if(!is_string($startPoint)) {
       throw ExceptionUtil::throwInvalidArgument('$startPoint', 'String', $startPoint);
     }
     $this->startPoint = $startPoint;
   }
   
   public function  setEndPoint($endPoint) {
     if(!is_string($endPoint)) {
       throw ExceptionUtil::throwInvalidArgument('$endPoint', 'String', $endPoint);
     }
     $this->endPoint = $endPoint;
   }
   
   public function  setWaypoints(array $waypoints) {
     $this->waypoints = $waypoints;
   }
   
   public function  setStartDate($startDate) {
     $this->startDate = $startDate;
   }
   
   public function  setDriver(User $user) {
     $this->driver = $user;
   }
   
   public function  setPrice($price) {
     $this->price   = $price;
   }
   
   public function setVehicle(Vehicle $vehicle) {
     $this->vehicle = $vehicle;
   }
   
    public function setStartCoordinate($startCoord) {        
     $this->startCoordinate = $startCoord;
   }
   
    public function setEndCoordinate($endCoord) {        
     $this->endCoordinate = $endCoord;
   }
   
   public function setDuration($val) {
       $intVal = (int) $val;
       if(!is_int($intVal)) {
           throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
       }
       $this->duration = $intVal;
   }
   
   public function setDistance($val) {
       $intVal = (int) $val;
       if(!is_int($intVal)) {
           throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
       }
       $this->distance = $intVal;
   }
   
   /**
    * Set the overview path which is string of semicolon separated coordinates by google map. 
    * @param String $val
    */
   public function setOverviewPath($val) {
       $strVal = (string) $val;
       if(!is_string($val)) {
           throw ExceptionUtil::throwInvalidArgument('$val', 'string', $val);
       }
       $this->overviewPath = $strVal;
   }
   
   /**
    * Get the overview path which is string of semicolon separated coordinates.
    * @return String
    */
   public function getOverviewPath() {
       return $this->overviewPath;
   }
   public function getDistance() {
       return $this->distance;
   }
   
   public function getDuration() {
       return $this->duration;
   }
   
   public function getStartCoord() {
       return $this->startCoordinate;
   }
   
   public function getEndCoord() {
       return $this->endCoordinate;
   }
   
   public function getVehicle() {
     return $this->vehicle;
   }
   
   public function getEndPoint() {
     return $this->endPoint;
   }
   
   
   public function getWaypoints() {
     return $this->waypoints;
   }
   
   
   public function getStartDate() {
     return $this->startDate;
   }
   
   
   public function getPrice() {
     return $this->price;
   }
   
   
   public function getDriver() {
     return $this->driver;
   }
   
   
   public function __toString() {
     return StringUtil::generateToString(get_class($this),
          array ('startPoint' => $this->startPoint,
                 'endPoint' => $this->endPoint,
                 'waypoints' => $this->waypoints,
                 'startDate' => $this->startDate,
                 'price'  => $this->price,
                 'driver'  => $this->driver,
              ));
   }
   
   
   
  
}