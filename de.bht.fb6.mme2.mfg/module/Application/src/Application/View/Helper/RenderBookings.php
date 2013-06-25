<?php
namespace Application\View\Helper;

use JumpUpDriver\Util\Messages\IControllerMessages;
use JumpUpPassenger\Util\IBookingState;
use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpDriver\Util\Messages\IViewMessages;
use Application\Forms\RecommendationForm;
use JumpUpPassenger\Models\Booking;
use JumpUpDriver\Util\Messages\StateUtil;
use Application\Forms\BasicBookingForm;
use JumpUpDriver\Models\Trip;
use JumpUpDriver\Util\View\ICssStyles;
use JumpUpDriver\Forms\BasicTripForm;
use JumpUpUser\Controller\ProfileController;
use JumpUpDriver\Controller\VehicleController;
use JumpUpDriver\Util\Routes\IRouteStore;
use Application\Util\IntlUtil;

class RenderBookings {
	const DRIVER = 1;
	const PASSENGER = 2;
	
	public static function getRecommForm(Booking $booking) {
		$builder = new AnnotationBuilder ();
		$recomForm = $builder->createForm ( new RecommendationForm () );
		$recomForm->get ( RecommendationForm::FIELD_BOOKING_ID )->setValue ( $booking->getId () );
		return $recomForm;
	}
	public static function getDenyForm(Booking $booking) {
		$builder = new AnnotationBuilder ();
		$basicForm = $builder->createForm ( new BasicBookingForm () );
		$basicForm->setAttribute ( 'class', ICssStyles::NO_FLOAT );
		$basicForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::BOOK_DENY );
		$basicForm->get ( BasicBookingForm::FIELD_BOOKING_ID )->setValue ( $booking->getId () );
		$basicForm->get ( BasicBookingForm::SUBMIT )->setValue ( IViewMessages::BOOKING_DENY );
		return $basicForm;
	}
	public static function getApplyForm(Booking $booking) {
		$builder = new AnnotationBuilder ();
		$basicForm = $builder->createForm ( new BasicBookingForm () );		
		$basicForm->get ( BasicBookingForm::FIELD_BOOKING_ID )->setValue ( $booking->getId () );
		$basicForm->get ( BasicBookingForm::SUBMIT )->setValue ( IViewMessages::BOOKING_APPLY );
		return $basicForm;
	}
	public static function getDeleteTripForm(Trip $trip) {
		$builder = new AnnotationBuilder ();
		$basicForm = $builder->createForm ( new \JumpUpDriver\Forms\BasicTripForm()    );		
		$basicForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::DELETE_TRIP );
		$basicForm->get ( BasicTripForm::FIELD_TRIP_ID )->setValue ( $trip->getId () );
		$basicForm->get ( BasicTripForm::SUBMIT )->setValue ( IViewMessages::TRIP_DELETE );
		return $basicForm;
	}
	public static function renderBookingForDriver(Booking $booking, $_this) {
		$applyForm = self::getApplyForm ( $booking );
		$applyForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::BOOK_APPLY );
		$denyForm = self::getDenyForm ( $booking );
		$denyForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::BOOK_DENY );
		echo '<li> <h4 class="' . ICssStyles::BOOKINGHEADLINE . '">#' . $booking->getId () . ' | <a target="blank" href="'.$_this->url(\JumpUpUser\Util\Routes\IRouteStore::SHOW_PROFILE).'?'.ProfileController::PARAM_USER_ID . '=' . $booking->getPassenger()->getId() . '">' .  $booking->getPassenger ()->getPrename () . ' ' . $booking->getPassenger ()->getLastname () . '</a> | ' . $booking->getStartPoint () . ' => ' . $booking->getEndPoint () . '</h4>';
		if ($booking->getState () === IBookingState::OFFER_FROM_PASSENGER) {
			echo $_this->renderForm ( $applyForm );
		}
		if ($booking->getState () === IBookingState::OFFER_FROM_PASSENGER) {
			echo $_this->renderForm ( $denyForm );
		}
		else {
			echo '<span class="nofloat">&nbsp;</span>';
		}
		echo "<div class=\"" . ICssStyles::BOOKING . "\">\n\t\t\t"; // begin of booking div
		// render status
		echo "<p>" . $_this->translate ( IViewMessages::BOOKING_STATE ) . ": " . $_this->translate ( StateUtil::getStateLabel ( $booking->getState () ) ) . "</p>";
		// differ bookings states
		if ($booking->getState () === IBookingState::OFFER_FROM_PASSENGER) {
			echo $_this->translate ( IControllerMessages::BOOKING_STATE_PASSENGER_RECOMM ) . "<br />";
			echo $_this->translate ( "Passenger's price recommendation: " . $booking->getPassengersRecomPrice () );
			// show recommendation form
			$recommForm = self::getRecommForm ( $booking );
			$recommForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::BOOK_DO_RECOMMENDATION );
			echo $_this->renderForm ( $recommForm );
		} elseif ($booking->getState () === IBookingState::ACCEPT) {
		} elseif ($booking->getState () === IBookingState::DENY) {
		} elseif ($booking->getState () === IBookingState::OFFER_FROM_DRIVER) {
		}
		echo '</div>'; // end of booking div
		echo '</li>';
	}
	
	public static function renderBookingForPassenger(Booking $booking, $_this) {
		$applyForm = self::getApplyForm ( $booking );
		$applyForm->setAttribute ( 'action', \JumpUpPassenger\Util\Routes\IRouteStore::BOOK_APPLY );
		$denyForm = self::getDenyForm ( $booking );
		$denyForm->setAttribute ( 'action', \JumpUpPassenger\Util\Routes\IRouteStore::BOOK_DENY );
		echo '<h3 class="' . ICssStyles::BOOKINGHEADLINE . '"><span>#' . $booking->getId () . ' | </span><a target="blank" href="'.$_this->url(\JumpUpUser\Util\Routes\IRouteStore::SHOW_PROFILE).'?'.ProfileController::PARAM_USER_ID . '=' . $booking->getDriver()->getId() . '">'
		 . $booking->getDriver ()->getPrename () . ' ' . $booking->getDriver ()->getLastname () . '</a><span> | ' . $booking->getStartPoint () . ' => ' . $booking->getEndPoint () . '</span></h3>';
		if ($booking->getState () === IBookingState::OFFER_FROM_DRIVER) {
			echo $_this->renderForm ( $applyForm );
		}
		if ($booking->getState () === IBookingState::OFFER_FROM_DRIVER) {
			echo $_this->renderForm ( $denyForm );
		}
		else {
			echo '<span class="nofloat">&nbsp;</span>';
		}
		echo "<div class=\"" . ICssStyles::BOOKING . "\">\n\t\t\t"; // begin of booking div
		// render and link vehicle
		echo "<p>" . $_this->translate ( \JumpUpPassenger\Util\Messages\IViewMessages::VEHICLE) . ': <a target="blank" href="'. $_this->url(IRouteStore::SHOW_VEHICLE) . '?'.VehicleController::PARAM_VEHICLE_ID. '=' . $booking->getTrip()->getVehicle()->getId() . '">' 
				. $booking->getTrip()->getVehicle()->getBrand() . " " . $booking->getTrip()->getVehicle()->getType() . "</a></p>"; 
		// render status
		echo "<p>" . $_this->translate( \JumpUpDriver\Util\Messages\IViewMessages::BOOKING_DATE) . ": " . IntlUtil::strToDeDate($booking->getTrip()->getStartDate())."</p>";
		echo "<p>" . $_this->translate ( IViewMessages::BOOKING_STATE ) . ": " . $_this->translate ( StateUtil::getStateLabel ( $booking->getState () ) ) . "</p>";
		// differ bookings states
		if ($booking->getState () === IBookingState::OFFER_FROM_DRIVER) {
		
			echo $_this->translate ( \JumpUpPassenger\Util\Messages\IControllerMessages::BOOKING_STATE_DRIVERS_RECOMM ) . "<br />";
			echo $_this->translate ( "Driver's price recommendation: " . $booking->getDriversRecomPrice () );
			// show recommendation form
			$recommForm = self::getRecommForm ( $booking );
			$recommForm->setAttribute ( 'action', \JumpUpPassenger\Util\Routes\IRouteStore::BOOK_DO_RECOMMENDATION );
			echo $_this->renderForm ( $recommForm );
		} elseif ($booking->getState () === IBookingState::ACCEPT) {
		} elseif ($booking->getState () === IBookingState::DENY) {
		} elseif ($booking->getState () === IBookingState::OFFER_FROM_DRIVER) {
		}
		echo '</div>'; // end of booking div	
	}
	
	public static function renderTrip(Trip $trip, $_this, $mode) {
		echo "<h3>" . $_this->translate ( $trip->getStartPoint () . " => " . $trip->getEndPoint () ) . " (" . $_this->translate ( IViewMessages::BOOKING_DATE ) . ": ".IntlUtil::strToDeDate($trip->getStartDate()) . ")"
			. "</h3>";
		echo '<div class="' . ICssStyles::TRIP . '">'; // begin of accordion element content
		$deleteForm = self::getDeleteTripForm($trip);	
		echo $_this->renderForm($deleteForm);
		$bookings = $trip->getBookings();
		if(sizeof($bookings) > 0) {
			foreach ( $bookings as $booking ) {
				echo "<ul class=" . ICssStyles::BOOKING_LIST . ">";
				if(self::DRIVER === $mode) {
					self::renderBookingForDriver ( $booking, $_this );
				}
				else {
					self::renderBookingForPassenger ( $booking, $_this );
				}
				echo "</ul>";
			}
		}
		else { // size = 0
			echo "<p>".$_this->translate(\Application\Util\Messages\IViewMessages::DRIVER_NO_BOOKINGS)."</p>";
		}
		
		echo "</div>"; // end of accordion element content
	}
	
}