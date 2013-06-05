<?php
namespace JumpUpPassenger\Controller;

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
 * @since      31.05.2013
 */
class BookingController extends ANeedsAuthenticationController{
  /**
   * POST-parameter for the tripId.
   * @var String the parameter name
   */
  const POST_TRIP_ID = "tripId";
  /**
   * POST-parameter for the recommendedPrice.
   * @var String the parameter name
   */
  const POST_TRIP_RECOMM_PRICE = "recommendedPrice";
  
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
   * The book trip action reacts to a submit given when a user books a trip.
   * The input parameters are (POST):
   * - the tripid int the ID of the trip entity
   * - recommendedPrice int the recommendation (by the passenger)
   */
  public function bookTripAction() {
    // check if user is logged in and fetch user's entity
    $loggedInUser = $this->_checkAndRedirect();
    
    $request = $this->getRequest();
    if($request->isPost()) {
      $tripId = (int) $request->getPost(self::POST_TRIP_ID);
      $recomPrice = (int) $request->getPost(self::POST_TRIP_RECOMM_PRICE);
      if(null !== $tripId && null !== $recomPrice) {
        $trip = $this->_getTrip($tripId);
        if(null !== $trip) {         
          $booking = new Booking($trip, $loggedInUser, $recomPrice);
          try {
            $this->_bookTrip($booking, $trip);
            // @TODO inform driver via eMail, he needs to check his booking overview page
            return array("message" => IControllerMessages::BOOKING_SUCCESS);
          }
          catch (OverbookException $e) {
            $this->flashMessenger()->clearMessages();
            $this->flashMessenger()->addErrorMessage(IControllerMessages::ERROR_BOOKING_OVERBOOKING);
            
          }         
        }
        else {
          $this->flashMessenger()->addErrorMessage(IControllerMessages::ERROR_BOOKING_NOTRIP);
        }
      }
      else {
        $this->flashMessenger()->addErrorMessage(IControllerMessages::ERROR_BOOKING_REQUEST);
      }
    }
    // fallthrough: error
    $this->redirect()->toRoute(IRouteStore::BOOK_ERROR);
  }
  
  /**
   * Perform a booking. Create a booking record for the given booking.
   * @param Booking $booking the new Booking record
   * @param Trip $trip the trip to be booked
   * @throws OverbookException when trying to overbook
   */
  private  function _bookTrip(Booking $booking, Trip $trip) {
    $numberBookings = $trip->getNumberOfBookings();
    if($numberBookings < $trip->getMaxSeats()) { // still free seats
      $trip->addBooking($booking);
      // persist both
      $this->em->persist($booking); // create new
      $this->em->merge($trip); // update existing
      $this->em->flush();
    }
    else {
      // raise overbook exception
       throw new OverbookException($trip, $booking);
    }
  }
  
  /**
   * Get the trip entity for a given tripid.
   * @param int $tripId - must be an integer
   * @returns the entity with the given id or null if it doesn't exist
   * @throws InvalidArgumentExcetion if the param is not an int.
   */
  private function _getTrip($tripId) {
    if(!is_int($tripId)) {
      throw ExceptionUtil::throwInvalidArgument('$tripId', 'int', $tripId);
    }
    
    $tripRepo = $this->em->getRepository(IEntitiesStore::TRIP);
    $trip = $tripRepo->findOneBy(array("id" => $tripId));
    return $trip;
  }
  
}