<?php

namespace JumpUpPassenger\Controller;

use JumpUpUser\Controller\ANeedsAuthenticationController;
use JumpUpUser\Models\User;
use JumpUpPassenger\Exceptions\OverbookException;
use JumpUpPassenger\Util\IEntitiesStore;
use JumpUpPassenger\Util\Routes\IRouteStore;
use JumpUpPassenger\Util\Messages\IControllerMessages;
use JumpUpDriver\Models\Trip;
use JumpUpPassenger\Models\Booking;
use Application\Util\ExceptionUtil;
use Application\Forms\BasicBookingForm;
use JumpUpPassenger\Util\IBookingState;

/**
 *
 *
 * This controller handles actions that concern the booking entity.
 *
 * @package JumpUpPassenger\Controller
 * @subpackage
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 31.05.2013
 */
class BookingController extends ANeedsAuthenticationController {
	/**
	 * POST-parameter for the tripId.
	 * 
	 * @var String the parameter name
	 */
	const POST_TRIP_ID = "tripId";
	/**
	 * POST-parameter for the recommendedPrice.
	 * 
	 * @var String the parameter name
	 */
	const POST_TRIP_RECOMM_PRICE = "recommendedPrice";
	/**
	 * POST-parameter for the startPoint (of the passenger).
	 * 
	 * @var String the parameter name
	 */
	const POST_START_POINT = "startPoint";
	/**
	 * POST-parameter for the endPoint (of the passenger).
	 * 
	 * @var String the parameter name
	 */
	const POST_END_POINT = "endPoint";
	/**
	 * POST-parameter for the startCoord (of the passenger).
	 * 
	 * @var String the parameter name
	 */
	const POST_START_COORD = "startCoord";
	/**
	 * POST-parameter for the endCoord (of the passenger).
	 * 
	 * @var String the parameter name
	 */
	const POST_END_COORD = "endCoord";
	/**
	 * The error action is called when another action raises an error.
	 * Input parameters: none
	 * FlashMessenger messages: will be exported to the error view.
	 */
	public function errorAction() {
		$errorMessages = $this->flashMessenger ()->getCurrentErrorMessages ();
		$messages = $this->flashMessenger ()->getCurrentMessages ();
		return array (
				"errorMessages" => $errorMessages,
				"messages" => $messages 
		);
	}
	
	/**
	 * The viewBooking actions shows each booking for the logged in user (where he's a passenger).
	 * exports: an array of booking instances for this user, accessible via "bookings" key.
	 */
	public function viewBookingsAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();
		
		$bookings = $this->_getAllBookings ( $loggedInUser );
		if (null !== $bookings) {
			$messages = $this->flashMessenger ()->getMessages ();
			return array (
					"bookings" => $bookings,
					"messages" => $messages 
			);
		} else { // there must be some internal error if $bookings is really null.
			$this->flashMessenger ()->clearMessages ();
			$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERVIEW );
			$this->redirect ()->toRoute ( IRouteStore::BOOK_ERROR );
		}
	}
	
	/**
	 * The applyAction is called when the user (driver) wants to apply the booking request by another user.
	 * Input parameters: - bookingId (the id of the depending booking)
	 * exports: nothing
	 * the user will be redirect to the viewBookingsAction for an overview
	 */
	public function applyAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();
		
		$request = $this->getRequest ();
		$bookingId = ( int ) $request->getPost ( BasicBookingForm::FIELD_BOOKING_ID );
		$redirect = IRouteStore::BOOK_ERROR;
		if (null !== $bookingId) {
			$booking = $this->_getBooking ( $loggedInUser, $bookingId );
			if (null !== $booking) {
				$booking->setState ( IBookingState::ACCEPT );
				$this->em->merge ( $booking );
				$this->em->flush ();
				// redirect to overview page
				$redirect = IRouteStore::BOOK_PASS_OVERVIEW;
			} else { // there must be some internal error if $bookings is really null.
				$this->flashMessenger ()->clearMessages ();
				$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERVIEW );
			}
		} else {
			$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_REQUEST );
		}
		// fallthrough: error
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * The denyAction is called when the user (driver) wants to deny the booking request by another user.
	 * Input parameters: - bookingId (the id of the depending booking)
	 * exports: nothing
	 * the user will be redirect to the viewBookingsAction for an overview
	 */
	public function denyAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();
		
		$request = $this->getRequest ();
		$bookingId = ( int ) $request->getPost ( BasicBookingForm::FIELD_BOOKING_ID );
		$redirect = IRouteStore::BOOK_ERROR;
		if (null !== $bookingId) {
			$booking = $this->_getBooking ( $loggedInUser, $bookingId );
			if (null !== $booking) {
				$booking->setState ( IBookingState::DENY );
				$this->em->merge ( $booking );
				$this->em->flush ();
				// redirect to overview page
				$redirect = IRouteStore::BOOK_PASS_OVERVIEW;
			} else { // there must be some internal error if $bookings is really null.
				$this->flashMessenger ()->clearMessages ();
				$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERVIEW );
			}
		} else {
			$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_REQUEST );
		}
		// fallthrough: error
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * The book trip action reacts to a submit given when a user books a trip.
	 * The input parameters are (POST):
	 * - the tripid int the ID of the trip entity
	 * - recommendedPrice int the recommendation (by the passenger)
	 */
	public function bookTripAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();
		
		$request = $this->getRequest ();
		$redirect = IRouteStore::BOOK_ERROR;
		if ($request->isPost ()) {
			// @TODO use ZEND form here for validation
			$tripId = ( int ) $request->getPost ( self::POST_TRIP_ID );
			$recomPrice = ( int ) $request->getPost ( self::POST_TRIP_RECOMM_PRICE );
			$startPoint = ( string ) $request->getPost ( self::POST_START_POINT );
			$endPoint = ( string ) $request->getPost ( self::POST_END_POINT );
			$startCoord = $request->getPost ( self::POST_START_COORD );
			$endCoord = $request->getPost ( self::POST_END_COORD );
			if (null !== $tripId && null !== $recomPrice && null !== $startCoord && null !== $startPoint && null !== $endPoint && null !== $endCoord) {
				$trip = $this->_getTrip ( $tripId );
				// user tries to book his own ride
				if ($loggedInUser === $trip->getDriver ()) {
					$this->flashMessenger ()->clearMessages ();
					$this->flashMessenger ()->addErrorMessage ( IControllerMessages::BOOKING_ERROR_OWN_TRIP );
					$redirect = IRouteStore::BOOK_ERROR;
				} else {
					if (null !== $trip) {
						$booking = new Booking ( $trip, $loggedInUser, $recomPrice );
						$booking->setStartPoint ( $startPoint );
						$booking->setStartCoordinate ( $startCoord );
						$booking->setEndCoordinate ( $endCoord );
						$booking->setEndPoint ( $endPoint );
						try {
							$this->_bookTrip ( $booking, $trip );
							// @TODO inform driver via eMail, he needs to check his booking overview page
							$this->flashMessenger ()->clearMessages ();
							$this->flashMessenger ()->addMessage ( IControllerMessages::BOOKING_SUCCESS );
							$redirect = IRouteStore::BOOK_PASS_OVERVIEW;
						} catch ( OverbookException $e ) {
							$this->flashMessenger ()->clearMessages ();
							$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERBOOKING );
						}
					} else {
						$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_NOTRIP );
					}
				}
			} else {
				$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_REQUEST );
			}
		}
		// fallthrough: error
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * The doRecommendation actions updates the passenger's price recommendation on an active booking.
	 * input parameters: - bookingId (the id of the depending booking)
	 * - price (the recommended price of the passenger)
	 * exports: nothing =)
	 * because it redirects
	 */
	public function doRecommendationAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();
		
		$request = $this->request;
		$redirect = IRouteStore::BOOK_ERROR;
		if ($request->isPost ()) {
			$bookingId = ( int ) $request->getPost ( \Application\Forms\RecommendationForm::FIELD_BOOKING_ID );
			$passengersRecomPrice = ( int ) $request->getPost ( \Application\Forms\RecommendationForm::FIELD_RECOMM_PRICE );
			if (null !== $bookingId && null !== $passengersRecomPrice) {
				$booking = $this->_getBooking ( $loggedInUser, $bookingId );
				if (null !== $booking) {
					$booking->setPassengersRecomPrice ( $passengersRecomPrice );
					// set correct state
					$booking->setState ( IBookingState::OFFER_FROM_PASSENGER );
					// update entity
					$this->em->merge ( $booking );
					$this->em->flush ();
					$redirect = IRouteStore::BOOK_PASS_OVERVIEW;
				} else {
					$this->flashMessenger ()->clearMessages ();
					$this->flashMessenger ()->addErrorMessage ( \JumpUpPassenger\Util\Messages\IControllerMessages::ERROR_NO_BOOKING );
				}
			} else {
				$this->flashMessenger ()->addErrorMessage ( \JumpUpPassenger\Util\Messages\IControllerMessages::ERROR_BOOKING_REQUEST );
			}
		}
		
		// fallthrough: error
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * Perform a booking.
	 * Create a booking record for the given booking.
	 * 
	 * @param Booking $booking
	 *        	the new Booking record
	 * @param Trip $trip
	 *        	the trip to be booked
	 * @throws OverbookException when trying to overbook
	 */
	private function _bookTrip(Booking $booking, Trip $trip) {
		$numberBookings = $trip->getNumberOfBookings ();
		if ($numberBookings < $trip->getMaxSeats ()) { // still free seats
			$trip->addBooking ( $booking );
			// persist both
			$this->em->persist ( $booking ); // create new
			$this->em->merge ( $trip ); // update existing
			$this->em->flush ();
		} else {
			// raise overbook exception
			throw new OverbookException ( $trip, $booking );
		}
	}
	
	/**
	 * Get all bookings for the given passenger.
	 * 
	 * @param User $user        	
	 * @return array of Booking instances
	 */
	private function _getAllBookings(User $user) {
		$bookings = $user->getPassengerBookings ();
		return $bookings;
	}
	/**
	 * Get the trip entity for a given tripid.
	 * 
	 * @param int $tripId
	 *        	- must be an integer
	 * @return s the entity with the given id or null if it doesn't exist
	 * @throws InvalidArgumentExcetion if the param is not an int.
	 */
	private function _getTrip($tripId) {
		if (! is_int ( $tripId )) {
			throw ExceptionUtil::throwInvalidArgument ( '$tripId', 'int', $tripId );
		}
		
		$tripRepo = $this->em->getRepository ( IEntitiesStore::TRIP );
		$trip = $tripRepo->findOneBy ( array (
				"id" => $tripId 
		) );
		return $trip;
	}
	
	/**
	 * Get the booking entity for a given User (passenger) AND bookingId.
	 *
	 * @param User $loggedInUser        	
	 * @param unknown_type $bookingId        	
	 * @return Booking instance or null if there was no matching entity.
	 */
	private function _getBooking(User $loggedInUser, $bookingId) {
		$bookingRepo = $this->em->getRepository ( \JumpUpPassenger\Util\IEntitiesStore::BOOKING );
		$booking = $bookingRepo->findOneBy ( array (
				"id" => $bookingId,
				"passenger" => $loggedInUser->getId () 
		) );
		return $booking;
	}
}