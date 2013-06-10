<?php
namespace  JumpUpDriver\Util\Messages;

use Application\Util\ExceptionUtil;
use JumpUpPassenger\Util\IBookingState;



/**
 *
 * This static class offers static methods to handle the IBookingStates' rendering.
 *
 * @package    JumpUpDriver\Util\Messages
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      10.06.2013
 */
class StateUtil {
  
  /**
   * Get the label for an IBookingState.
   * @see IBookingState
   * @param int $state
   * @thros an InvalidArgumentException if the state doesn't match any in IBookingState.
   */
  public static function getStateLabel($state) {
    switch ($state) {
      case IBookingState::ACCEPT:
        return "accepted";
      case IBookingState::DENY:
        return "denied";
      case IBookingState::OFFER_FROM_DRIVER:
        return "offer from driver";
      case IBookingState::OFFER_FROM_PASSENGER:
        return "offer from passenger";
      default:
        throw ExceptionUtil::throwInvalidArgument('$state', 'IBookingState', $state);
    }
  }
  
}