<?php
namespace JumpUpDriver\Models;


use Application\Util\ArrayUtil;

use JumpUpPassenger\Models\Booking;

use Application\Util\ExceptionUtil;

use JumpUpUser\Models\User;

use Application\Util\StringUtil;




use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne as OneToOne;
use Doctrine\ORM\Mapping\OneToMany as OneToMany;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use JumpUpPassenger\Util\IBookingState;

/**
 * @ORM\Entity
 * @Table(name="trip")
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
   * @ManyToOne(targetEntity="JumpUpUser\Models\User", inversedBy="trips")
   * */
  private $driver;
  /**
   * @ManyToOne(targetEntity="JumpUpDriver\Models\Vehicle", inversedBy="intrips")
   */
  private $vehicle;
  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $overviewPath;
  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $viaWaypoints;
  /**
   * @ORM\Column(type="integer")
   */
  private $maxSeats;
  /**
   * @OneToMany(targetEntity="JumpUpPassenger\Models\Booking",mappedBy="trip")
   */
  private $bookings;
  /**
   * This is not an entity field. It's only for the client so we can offer a system-generated price.
   * @var int
   */
  private $priceRecommendationForPassenger;
  /**
   * This is not an entity field. It's only for the client so we can offer the passenger's location distance to the trip.
   * @var int
   */
  private $distanceFromPassengersLocation;
  /**
   * This is not an entity field. It's only for the client so we can offer the passenger's destination distance to the trip.
   * @var int
   */
  private $distanceFromPassengersDestination;
   
  



public function __construct() {
    // initialize bookings
    $this->bookings = array();
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
   
  public function  setWaypoints(array $waypoints) {
    $this->waypoints = $waypoints;
  }
   
  public function  setStartDate($startDate) {
    $this->startDate = $startDate;
  }
   
  public function  setDriver(\JumpUpUser\Models\User $user) {
    $this->driver = $user;
  }
   
  public function  setPrice($price) {
    $this->price   = $price;
  }
   
  public function setVehicle(\JumpUpDriver\Models\Vehicle $vehicle) {
    $this->vehicle = $vehicle;
  }
  
  /**
   * @return the $distanceFromPassengersLocation
   */
  public function getDistanceFromPassengersLocation() {
  	return $this->distanceFromPassengersLocation;
  }
  
  /**
   * @return the $distanceFromPassengersDestination
   */
  public function getDistanceFromPassengersDestination() {
  	return $this->distanceFromPassengersDestination;
  }
  
  /**
   * @param number $distanceFromPassengersLocation
   */
  public function setDistanceFromPassengersLocation($distanceFromPassengersLocation) {
  	$this->distanceFromPassengersLocation = $distanceFromPassengersLocation;
  }
  
  /**
   * @param number $distanceFromPassengersDestination
   */
  public function setDistanceFromPassengersDestination($distanceFromPassengersDestination) {
  	$this->distanceFromPassengersDestination = $distanceFromPassengersDestination;
  }
   
  /**
   * Set the coordinate of the trip's start location.
   * Should be in the form (Longitude,Latitude).
   * @param String $startCoord
   */
  public function setStartCoordinate($startCoord) {
    $this->startCoordinate = $startCoord;
  }
  /**
   * Set the coordinate of the trip's end location.
   * Should be in the form (Longitude,Latitude).
   * @param String $endCoord
   */
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
   * Set the via waypoints which is string of semicolon separated coordinates by google map.
   * @param String $val
   */
  public function setViaWaypoints($val) {
    $strVal = (string) $val;
    if(!is_string($val)) {
      throw ExceptionUtil::throwInvalidArgument('$val', 'string', $val);
    }
    $this->viaWaypoints = $strVal;
  }
   
  public function setMaxSeats($maxSeats) {
    $intVal = (int) $maxSeats;
    if(!is_int($intVal)) {
      throw ExceptionUtil::throwInvalidArgument('$maxSeats', 'int', $maxSeats);
    }
    $this->maxSeats = $maxSeats;
  }
   
  /**
   * Add a new booking.
   * @param Booking $booking the Booking to be added.
   * @throws Exception if you try to add a booking, but the number of maximum seats is already touched.
   */
  public function addBooking(Booking $booking) {
    /*
     * doesn' work because bookings doesn't appear to be an array...
     */
//      if(sizeof($this->bookings) < $this->maxSeats) { // seat available
//         array_push($this->bookings, $booking);
//      }
//      else {
//        throw new Exception("Trip::addBooking(): maximum number of seats would be passed.");
//      }
     /*
      * ...............
      */
  }
  
  /**
   * Remove a booking within the Trip.
   * @param int $bookingId the ID to the booking which shall be removed.
   */
  public function removeBooking($bookingId) {
    $counter = 0;
    foreach ($this->bookings as $booking) {
      if($booking->getId() === $bookingId) {
        unset($this->bookings[$counter]); // delete booking element
        ArrayUtil::swapToFreePos($this->bookings, $counter); // swap last element to free position, so the indices in the array work fine
        return; 
      }
      $counter++;
    }    
  }
  
 
   
  /**
   * Get the overview path which is string of semicolon separated coordinates.
   * @return String
   */
  public function getOverviewPath() {
    return $this->overviewPath;
  }
   
  /**
   * Get the overview path which is string of semicolon separated coordinates.
   * @return String
   */
  public function getViaWaypoints() {
    return $this->viaWaypoints;
  }
   
  /**
   * Get the distance (in meters)
   * @return int
   */
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
  
  public function getStartPoint() {
  	return $this->startPoint;
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
   
  /**
   * Return the ID for this trip entity.
   */
  public function getId() {
    return $this->id;
  }
  
  /**
   * @return int the maximum number of seats or the maximum number of allowed bookings.
   */
  public function getMaxSeats() {
    return (int) $this->maxSeats;
  }
  /**
   * 
   * @return int the number of non-DENIED bookings
   */
  public function getNumberOfBookings() { 
  	$appliedBookings = 0;
  	foreach ($this->bookings as $booking) {
  		if(IBookingState::DENY !== $booking->getState()) {
  			$appliedBookings++;
  		}	
  	}
    return (int) $appliedBookings;
  }
  
  /**
   * 
   * @return array all the bookings (such bookings with states DENY... are included) of this trip.
   */
  public function getBookings() {
  	return $this->bookings;
  }
   
  /**
   * @return int the $priceRecommendationForPassenger
   */
  public function getPriceRecommendationForPassenger() {
  	return $this->priceRecommendationForPassenger;
  }
  
  /**
   * Set the priceRecommendation to be presented to the passenger before he would like to book a trip.
   * @param double $priceRecommendationForPassenger
   */
  public function setPriceRecommendationForPassenger($priceRecommendationForPassenger) {
    if(!is_int($priceRecommendationForPassenger) && !is_double($priceRecommendationForPassenger)) {
        throw ExceptionUtil::throwInvalidArgument('$priceRecommendaation', 'int', $priceRecommendationForPassenger);
    }
  	$this->priceRecommendationForPassenger = $priceRecommendationForPassenger;
  }
   
  public function __toString() {
    return StringUtil::generateToString(get_class($this),
        array ('startPoint' => $this->startPoint,
            'endPoint' => $this->endPoint,
            'startDate' => $this->startDate,
            'price'  => $this->price,
            'driver'  => $this->driver->getPrename() . " " . $this->driver->getLastname(),
            'startCoord' => $this->getStartCoord(),
            'endCoord' => $this->getEndCoord(),
            'overviewPath' => $this->getOverviewPath(),
            'viaWaypoints' => $this->getViaWaypoints(),

        ));
  }
   
  public function toJson() {
    return array ('id' => $this->id,
        'startPoint' => $this->startPoint,
        'endPoint' => $this->endPoint,
        'startDate' => $this->getStartDate(),
        'price'  => $this->price,
        'driver'  => $this->driver->toJson(),
        'startCoord' => $this->getStartCoord(),
        'endCoord' => $this->getEndCoord(),
        'overviewPath' => $this->getOverviewPath(),
        'viaWaypoints' => $this->getViaWaypoints(),
        'maxSeats'     => $this->maxSeats, 
        'numberBookings' => $this->getNumberOfBookings(),
        'priceRecommendation' => $this->getPriceRecommendationForPassenger(), 
    	'vehicle' => $this->getVehicle()->toJson(),
    	'distanceFromPassengersLocation' => $this->getDistanceFromPassengersLocation(),
    	'distanceFromPassengersDestination' => $this->getDistanceFromPassengersDestination(),
    );
  }
   
   
   

}