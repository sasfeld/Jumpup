<?php
namespace JumpUpDriver\Controller;

use JumpUpPassenger\Util\IBookingState;

use JumpUpUser\Controller\ANeedsAuthenticationController;

use JumpUpUser\Models\User;

use JumpUpPassenger\Exceptions\OverbookException;

use JumpUpPassenger\Util\IEntitiesStore;


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
  const POST_BOOKING_ID = RecommendationForm::FIELD_BOOKING_ID;
  const POST_DRIVER_PRICE = RecommendationForm::FIELD_RECOMM_PRICE;
  
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
   * The doRecommendation actions updates the driver's price recommendation on an active booking.
   * input parameters: - bookingId (the id of the depending booking)
   *                   - price (the recommended price of the driver)
   * exports: nothing =)
   * because it redirects
   */
  public function doRecommendation() {
    // check if user is logged in and fetch user's entity
    $loggedInUser = $this->_checkAndRedirect();
    if($request->isPost()) {
      $tripId = (int) $request->getPost(self::POST_BOOKING_ID);
      $driversRecomPrice = (int) $request->getPost(self::POST_DRIVER_PRICE);
      if(null !== $tripId && null !== $driversRecomPrice) {
        $booking = $this->_getBooking($loggedInUser, $bookingId);
        if(null !== $booking) {        
          $booking->setDriversRecomPrice($driversRecomPrice);
          // set correct state 
          $booking->setState(IBookingState::OFFER_FROM_DRIVER);
          // update entity
          $this->em->merge($booking);
          $this->em->flush();
        }
        else {
          $this->flashMessenger()->clearMessages();
          $this->flashMessenger()->addErrorMessage(\JumpUpDriver\Util\Messages\IControllerMessages::ERROR_NO_BOOKING);
        }
      }
      else {
        $this->flashMessenger()->addErrorMessage(\JumpUpDriver\Util\Messages\IControllerMessages::ERROR_BOOKING_REQUEST);
      }      
    }
    
    // fallthrough: error
    $this->redirect()->toRoute(\JumpUpDriver\Util\Routes\IRouteStore::BOOK_ERROR);   
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
  
  /**
   * Get the booking entity for a given User AND bookingId.
   * @param User $loggedInUser
   * @param unknown_type $bookingId
   * @return Booking instance or null if there was no matching entity.
   */
  private function _getBooking(User $loggedInUser, $bookingId) {
    $bookingRepo = $this->em->getRepository(\JumpUpPassenger\Util\IEntitiesStore::BOOKING);
    $booking = $bookingRepo->findOneBy(array("id" => $bookingId,
                                          "driver" => $loggedInUser->getId(),
                                          ));
    return $booking;
  }
  
}