<?php

namespace JumpUpDriver\Controller;

use JumpUpPassenger\Util\IBookingState;
use JumpUpUser\Controller\ANeedsAuthenticationController;
use JumpUpUser\Models\User;
use JumpUpPassenger\Util\Messages\IControllerMessages;
use JumpUpPassenger\Models\Booking;
use JumpUpDriver\Util\Routes\IRouteStore;
use JumpUpDriver\Forms\BasicBookingForm;
use JumpUpDriver\Forms\RecommendationForm;

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
 * @since 07.06.2013
 */
class BookingController extends ANeedsAuthenticationController {
	const POST_BOOKING_ID = \JumpUpDriver\Forms\RecommendationForm::FIELD_BOOKING_ID;
	const POST_DRIVER_PRICE = \JumpUpDriver\Forms\RecommendationForm::FIELD_RECOMM_PRICE;
	
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
				$booking->setState(IBookingState::ACCEPT);
				$this->em->merge($booking);
				$this->em->flush();
				// redirect to overview page
				$redirect = IRouteStore::BOOK_DRIVER_OVERVIEW;
			} else { // there must be some internal error if $bookings is really null.
				$this->flashMessenger ()->clearMessages ();
				$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERVIEW );				
			}
		}
		else {
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
				$booking->setState(IBookingState::DENY);
				$this->em->merge($booking);
				$this->em->flush();
				// redirect to overview page
				$redirect = IRouteStore::BOOK_DRIVER_OVERVIEW;
			} else { // there must be some internal error if $bookings is really null.
				$this->flashMessenger ()->clearMessages ();
				$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERVIEW );				
			}
		}
		else {
			$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_REQUEST );
		}
		// fallthrough: error
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * The viewBooking actions shows each booking for the logged in user (where he's a driver).
	 * exports: an array of trips instances for this driver, accessible via "trips" key.
	 * 
	 * @TODO add filter handling for the user
	 */
	public function viewBookingsAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();		
	
		$trips = $this->_getAllTrips($loggedInUser);
		if (null !== $trips) {
			return array (
					"trips" => $trips 
			);
		} else { // there must be some internal error if $bookings is really null.
			$this->flashMessenger ()->clearMessages ();
			$this->flashMessenger ()->addErrorMessage ( IControllerMessages::ERROR_BOOKING_OVERVIEW );
			$this->redirect ()->toRoute ( IRouteStore::BOOK_ERROR );
		}
	}
	
	/**
	 * The doRecommendation actions updates the driver's price recommendation on an active booking.
	 * input parameters: - bookingId (the id of the depending booking)
	 * - price (the recommended price of the driver)
	 * exports: nothing =)
	 * because it redirects
	 */
	public function doRecommendationAction() {
		// check if user is logged in and fetch user's entity
		$loggedInUser = $this->_checkAndRedirect ();
		
		$request = $this->request;
		$redirect = IRouteStore::BOOK_ERROR;
		if ($request->isPost ()) {
			$bookingId = ( int ) $request->getPost ( RecommendationForm::FIELD_BOOKING_ID );
			$driversRecomPrice = ( int ) $request->getPost ( RecommendationForm::FIELD_RECOMM_PRICE );
			if (null !== $bookingId && null !== $driversRecomPrice) {
				$booking = $this->_getBooking ( $loggedInUser, $bookingId );
				if (null !== $booking) {
					$booking->setDriversRecomPrice ( $driversRecomPrice );
					// set correct state
					$booking->setState ( IBookingState::OFFER_FROM_DRIVER );
					// update entity
					$this->em->merge ( $booking );
					$this->em->flush ();
					$redirect = IRouteStore::BOOK_DRIVER_OVERVIEW;
				} else {
					$this->flashMessenger ()->clearMessages ();
					$this->flashMessenger ()->addErrorMessage ( \JumpUpDriver\Util\Messages\IControllerMessages::ERROR_NO_BOOKING );
				}
			} else {
				$this->flashMessenger ()->addErrorMessage ( \JumpUpDriver\Util\Messages\IControllerMessages::ERROR_BOOKING_REQUEST );
			}
		}
		
		// fallthrough: error
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * Get all bookings for the given driver.
	 * 
	 * @param User $user        	
	 * @return array of Booking instances
	 */
	private function _getAllBookings(User $driver) {
		$bookings = $driver->getDriverBookings ();
		return $bookings;
	}
	
	/**
	 * Get all trips for the given driver.
	 * 
	 * @param User $user        	
	 * @return array of Trip instances
	 */
	private function _getAllTrips(User $driver) {
		$trips = $driver->getTrips();		
		return $trips;
	}	
	
	
	/**
	 * Get the booking entity for a given User AND bookingId.
	 * 
	 * @param User $loggedInUser        	
	 * @param unknown_type $bookingId        	
	 * @return Booking instance or null if there was no matching entity.
	 */
	private function _getBooking(User $loggedInUser, $bookingId) {
		$bookingRepo = $this->em->getRepository ( \JumpUpPassenger\Util\IEntitiesStore::BOOKING );
		$booking = $bookingRepo->findOneBy ( array (
				"id" => $bookingId,
				"driver" => $loggedInUser->getId () 
		) );
		return $booking;
	}
}