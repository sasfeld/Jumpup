<?php
namespace JumpUpPassenger\Exceptions;

use JumpUpPassenger\Models\Booking;

use JumpUpDriver\Models\Trip;

/**
 *
 * This exception will be raised if there isn't any seat available on a Trip but anyone tries to book it.
 *
 * @package    JumpUpPassenger\Controller
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      31.05.2013
 */
class OverbookException extends Exception {
  protected $trip;
  protected $booking;
  
  /**
   * Construct a new OverbookException when a User tries to overbook.
   * @param Trip $trip
   * @param Booking $booking
   */
  public function __construct(Trip $trip, Booking $booking) {
    $this->trip = $trip;
    $this->booking = $booking;
    parent::construct("No more free seats available on the trip(id".$trip->getId().").");
  }
  
  /**
   * Get the causing trip.
   */
  public function getTrip() {
    return $this->trip;
  }
  
  /**
   * Get the causing booking.
   */
  public function getBooking() {
    return $this->booking;
  }
  
}