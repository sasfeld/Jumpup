<?php
namespace JumpUpPassenger\Models;

use JumpUpDriver\Models\Trip;

use Application\Util\ExceptionUtil;
use JumpUpUser\Models\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne as OneToOne;
use Doctrine\ORM\Mapping\OneToMany as OneToMany;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;

/**
 * @ORM\Entity
 */
class Booking {
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
   * @ManyToOne(targetEntity="JumpUpUser\Models\User")
   */
  private $driver;
  /**
   * @ManyToOne(targetEntity="JumpUpUser\Models\User")
   */
  private $passenger;
  /**
   * @ManyToOne(targetEntity="JumpUpDriver\Models\Trip", inversedBy="bookings")
   */
  private $trip;
  
  
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
  
  
  /**
   * Set the coordinate of the trip's start location.
   * Should be in the form (Longitude,Latitude).
   * @param String $startCoord
   */
  public function setStartCoordinate($startCoord) {
    if(!is_string($startCoord)) {
      throw ExceptionUtil::throwInvalidArgument('$startCoord', 'String', $startCoord);
    }
    $this->startCoordinate = $startCoord;
  }
  /**
   * Set the coordinate of the trip's end location.
   * Should be in the form (Longitude,Latitude).
   * @param String $endCoord
   */
  public function setEndCoordinate($endCoord) {
    if(!is_string($endCoord)) {
      throw ExceptionUtil::throwInvalidArgument('$endCoord', 'String', $endCoord);
    }
    $this->endCoordinate = $endCoord;
  }
  
  /**
   * Set the driver (a User) for this booking.
   * A booking can only have one driver. 
   * @param User $driver the User who offers the trip.
   */
  public function setDriver(User $driver) {
    $this->driver = $driver;
  }
  
  /**
   * Set the passenger (a User) for this booking.
   * A booking can only have one passenger.
   * @param User $passenger the User who books the trip.
   */
  public function setPassenger(User $passenger) {
    $this->passenger = $passenger;
  }
  
  public function setTrip(Trip $trip) {
    $this->trip = $trip;
  }
  
  public function getTrip() {
    return $this->trip;
  }
  
  /**
   * Return the ID for this booking entity.
   */
  public function getId() {
    return $this->id;
  }
  
  public function getPassenger() {
    return $this->passenger;
  }
  
  public function getDriver() {
    return $this->driver;
  }
  
  public function getStartCoord() {
    return $this->startCoordinate;
  }
   
  public function getEndCoord() {
    return $this->endCoordinate;
  }
  
  public function getStartPoint() {
    return $this->startPoint;
  }
  
  public function getEndPoint() {
    return $this->endPoint;
  }
  
}