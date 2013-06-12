<?php
namespace JumpUpPassenger\Exceptions;

/**
 *
 * This exception will be raised if the client does anything that doesn't fit the state cycle of IBookingState.
 *
 * @package    JumpUpPassenger\Exceptions
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      31.05.2013
 */
class InvalidBookingStateException extends \Exception {
  /**
   * The only state that is allowed in this context.
   * @var IBookingState
   */
  protected $allowedState;
  /**
   * The given state of the Booking.
   * @var IBookingState
   */
  protected $givenState;
  
  public function  __construct($allowedState, $givenState) {
    $this->allowedState = $allowedState;
    $this->givenState = $givenState;
    parent::__construct("Invalid state. The given operation is only allowed for the state ".$allowedState." . The given state was".$givenState);
  }
  
  /**
   * Get the only allowed state.
   */
  public function getAllowedState() {
    return $this->allowedState;
  }
  
  /**
   * Get the causing wrong/invalid state.
   */
  public function getGivenState() {
    return $this->givenState;
  }
}