<?php
namespace Application\View\Helper;

use JumpUpUser\Forms\RegistrationForm;
use JumpUpDriver\Util\Messages\IControllerMessages;
use JumpUpPassenger\Util\IBookingState;
use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpDriver\Util\Messages\IViewMessages;
use JumpUpDriver\Util\Routes\IRouteStore;
use Application\Forms\RecommendationForm;
use JumpUpPassenger\Models\Booking;
use JumpUpDriver\Util\Messages\StateUtil;
use Application\Forms\BasicBookingForm;
use JumpUpDriver\Models\Trip;
use JumpUpDriver\Util\View\ICssStyles;

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
	public static function renderBookingForDriver(Booking $booking, $_this) {
		$applyForm = self::getApplyForm ( $booking );
		$applyForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::BOOK_APPLY );
		$denyForm = self::getDenyForm ( $booking );
		$denyForm->setAttribute ( 'action', \JumpUpDriver\Util\Routes\IRouteStore::BOOK_DENY );
		echo '<li> <h4 class="' . ICssStyles::BOOKINGHEADLINE . '">#' . $booking->getId () . ' | ' . $booking->getPassenger ()->getPrename () . ' ' . $booking->getPassenger ()->getLastname () . ' | ' . $booking->getStartPoint () . ' => ' . $booking->getEndPoint () . '</h4>';
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
		echo '<h3 class="' . ICssStyles::BOOKINGHEADLINE . '">#' . $booking->getId () . ' | ' . $booking->getDriver ()->getPrename () . ' ' . $booking->getDriver ()->getLastname () . ' | ' . $booking->getStartPoint () . ' => ' . $booking->getEndPoint () . '</h3>';
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
		// render status
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
		echo "<h3>" . $_this->translate ( $trip->getStartPoint () . " => " . $trip->getEndPoint () ) . " (" . $_this->translate ( "Date" ) . " - " . $_this->translate ( IViewMessages::BOOKING_DATE ) . ")" . "</h3>";
		echo '<div class="' . ICssStyles::TRIP . '">'; // begin of accordion element content
		
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
			echo "<p>".\Application\Util\Messages\IViewMessages::DRIVER_NO_BOOKINGS."</p>";
		}
		
		echo "</div>"; // end of accordion element content
	}
	
}