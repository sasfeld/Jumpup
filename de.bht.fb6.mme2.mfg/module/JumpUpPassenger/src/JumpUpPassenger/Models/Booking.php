<?php
namespace JumpUpPassenger\Models;

use JumpUpPassenger\Util\IBookingState;

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
   * @ORM\Column(type="string", nullable=true)
   */
  private $startPoint;
  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $startCoordinate;
  /**
   * @ORM\Column(type="string", nullable=true)
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
  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $driversRecomPrice;
  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $passengersRecomPrice;
  /**
   * @ORM\Column(type="integer")
   */
  private $state;
  
  /**
   * Create and initialize a new Booking.
   * It will be initialized with values by the given trip.
   * We do that to reduce the amount of database JOINS and queries.
   * @param Trip $relatedTrip
   * @param User $passenger the user who is currently logged in and who performs the booking.
   * @param int  $passengersRecomPrice the passenger's recommended price
   */
  public function __construct(Trip $relatedTrip, User $passenger, $passengersRecomPrice) {
    $driver = $relatedTrip->getDriver();
    if(null === $driver) {
      throw ExceptionUtil::throwInvalidArgument('$relatedTrip->getDriver()', 'User', 'null');
    }
    
    if(!is_int( $passengersRecomPrice)) {
      throw ExceptionUtil::throwInvalidArgument('$passengersRecomPrice', 'int',  $passengersRecomPrice);
    }
    $this->driver = $driver;
    $this->trip = $relatedTrip;
    $this->passenger = $passenger;
    // start state is passenger's recommendation
    $this->state = IBookingState::OFFER_FROM_PASSENGER;
    $this->passengersRecomPrice = $passengersRecomPrice;    
  }
  
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
  
  /**
   * The driver can recommend a price for the booking.
   * The driver's recommended price will be setted here.
   * @param int $val
   */
  public function setDriversRecomPrice($val) {
    // check type
    if(!is_int($val)) {
      throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
    }
    
    // check state
    if($this->state !== IBookingState::OFFER_FROM_PASSENGER) {
      throw new InvalidBooingStateException(IBookingState::OFFER_FROM_PASSENGER, $this->state);
    }   
     
     $this->driversRecomPrice = (int) $val;
     // set next state
     $this->state = IBookingState::OFFER_FROM_DRIVER;
  }
  
  /**
   * The passenger can recommend a price for the booking.
   * The passenger's recommended price will be setted here.
   * @param int $val
   */
  public function setPassengersRecomPrice($val) {
    if(!is_int($val)) {
      throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
    }
    
    // check state
    if($this->state !== IBookingState::OFFER_FROM_DRIVER) {
      throw new InvalidBooingStateException(IBookingState::OFFER_FROM_DRIVER, $this->state);
    }
     
    $this->passengersRecomPrice = (int) $val;
    // set next state
    $this->state = IBookingState::OFFER_FROM_PASSENGER;
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
  
  /**
   * 
   * @return int the recommended price of the driver.
   */
  public function getDriversRecomPrice() {
    return (int) $this->driversRecomPrice;
  }
  
  /**
   * 
   * @return int thre recommended price of the passenger
   */
  public function getPassengersRecomPrice() {
    return (int) $this->passengersRecomPrice;
  }
  
  /**
   * 
   * @return the IBookingState.
   */
  public function getState() {
    return (int) $this->state;
  }
  
}