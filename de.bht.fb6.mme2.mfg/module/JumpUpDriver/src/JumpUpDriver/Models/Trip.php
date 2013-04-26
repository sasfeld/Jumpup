<?php
namespace JumpUpDriver\Models;


use JumpUpUser\Models\User;

use JumpUpDriver\Util\Exception_Util;

use JumpUpDriver\Util\StringUtil;

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
    * @ORM\Column(type="string")
    */
   private $endPoint;
   /**
    * @ORM\Column(type="hash")
    */
   private $waypoints;
   /**
    * @ORM\Column(type="date")
    */
   private $startDate;
   /**
    * @ORM\Column(type="int")
    */
   private $price;
   /**
   * @OneToOne(targetEntity="JumpUpUser\Models\User")
   * */
   private $driver;
   
   
   public function  setStartPoint($startPoint) {
     if(!StringUtil::isString($startPoint)) {
       throw Exception_Util::throwInvalidArgument('$startPoint', 'String', $startPoint);
     }
     $this->startPoint = $startPoint;
   }
   
   public function  setEndPoint($endPoint) {
     if(!StringUtil::isString($endPoint)) {
       throw Exception_Util::throwInvalidArgument('$endPoint', 'String', $endPoint);
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
   
   public function  setStartDate($startDate) {
     $this->startDate = $startDate;
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