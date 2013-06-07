<?php
namespace JumpUpDriver\Controller;

use JumpUpUser\Controller\ANeedsAuthenticationController;

use JumpUpUser\Models\User;

use JumpUpPassenger\Exceptions\OverbookException;

use JumpUpPassenger\Util\IEntitiesStore;

use JumpUpPassenger\Util\Routes\IRouteStore;

use JumpUpPassenger\Util\Messages\IControllerMessages;

use JumpUpDriver\Models\Trip;

use JumpUpPassenger\Models\Booking;

use Application\Util\ExceptionUtil;



/**
 *
 * This controller handles actions that concern the booking entity.
 *
 * @package    JumpUpPassenger\Controller
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      07.06.2013
 */
class BookingController extends ANeedsAuthenticationController{
  
  /**
   * The error action is called when another action raises an error.
   * Input parameters: none
   * FlashMessenger messages: will be exported to the error view.
   */
  public function  errorAction() {
     $errorMessages = $this->flashMessenger()->getCurrentErrorMessages();  
     $messages = $this->flashMessenger()->getCurrentMessages();
     return array("errorMessages" => $errorMessages,
         "messages" => $messages); 
  }
  
  /**
   * The viewBooking actions shows each booking for the logged in user (where he's a driver).
   * exports: an array of booking instances for this driver, accessible via "bookings" key.
   */
  public function viewBookingsAction() {
    // check if user is logged in and fetch user's entity
    $loggedInUser = $this->_checkAndRedirect();
    
    $bookings = $this->_getAllBookings($loggedInUser);
    if(null !== $bookings) {
       return array("bookings" => $bookings);
    }
    else { // there must be some internal error if $bookings is really null.
      $this->flashMessenger()->clearMessages();
      $this->flashMessenger()->addErrorMessage(IControllerMessages::ERROR_BOOKING_OVERVIEW);
      $this->redirect()->toRoute(IRouteStore::BOOK_ERROR);
    }
    
    
  }
  
  

  
  /**
   * Get all bookings for the given driver.
   * @param User $user
   * @return array of Booking instances
   */
  private function _getAllBookings(User $driver) {
    $bookings = $driver->getDriverBookings();
    return $bookings;
  }
  
  
}