<?php
namespace JumpUpDriver\Controller;

use JumpUpUser\Controller\ANeedsAuthenticationController;

use JumpUpUser\Models\User;

use JumpUpUser\Export\IAuthenticationRequired;

use JumpUpUser\Util\Auth\CheckAuthentication;

use JumpUpDriver\Util\ServicesUtil;

use JumpUpDriver\Util\Messages\IControllerMessages;

use JumpUpDriver\Models\Trip;

use Zend\Stdlib\Hydrator\ClassMethods;

use JumpUpDriver\Forms\TripForm;

use JumpUpDriver\Util\Routes\IRouteStore;

use Zend\Form\Annotation\AnnotationBuilder;

use Zend\Mvc\Controller\AbstractActionController;
use JumpUpDriver\Forms\BasicTripForm;
use JumpUpDriver\Util\IEntitiesStore;
use JumpUpPassenger\Util\IBookingState;

/**
 * 
* AddTripController handles the creation of a trip.
*
* @package    
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      07.05.2013
 */
class AddTripController extends ANeedsAuthenticationController {
    
    protected $form_step1;


    private function getFormStep1() {
        if(!isset($this->form_step1)) {
            $step1Form = new TripForm();
            $builder = new AnnotationBuilder();
            $this->form_step1 = $builder->createForm($step1Form);
            $this->form_step1->setAttribute('action', $this->url()->fromRoute(IRouteStore::ADD_TRIP));
        }
        return $this->form_step1;
    }

    /**
     * error action
     */
    public function errorAction() {
        return array(
          'messages' => $this->flashMessenger()->getMessages(),
        );
    }

    /**
     * the success action is performed after a trip was added
     */
    public function successAction() {
        return array(
          'messages' => $this->flashMessenger()->getMessages(),
        );
    }

    /**
     * step1: add trip aciton
     */
    public function step1Action() {
        if($this->_checkAuthentication()) { // authentication required
            $redirect = ""; // redirection to other controllers
            $messages = array(); // messages array to be printed on the depending view.
            
            $user = $this->getCurrentUser();
            if(null === $user) { // user doesn't appear do be logged in
                $this->flashMessenger()->addErrorMessage(IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED);
                $redirect = IRouteStore::ADD_TRIP_ERROR;
            }
            $trip = new Trip();
            $form = $this->getFormStep1();
            $form->setHydrator(new ClassMethods());
            $form->bind($trip);             
           

            $translator = $this->_getTranslator();
            $request = $this->getRequest();             
            if($request->isPost()) {
                $form->setData($request->getPost());
                if($form->isValid())  {
                    // success & user is logged in
                    $this->_bindHiddenValues($trip, $user);
                    $success = $this->_persistTrip($trip);
                    // show success messages and execute redirect
                    if($success) {
                        $this->flashMessenger()->clearCurrentMessages();
                        $this->flashMessenger()->addMessage(IControllerMessages::SUCCESS_ADD_TRIP);
                        $redirect = IRouteStore::ADD_TRIP_SUCCESS;
                    }
                    else { // vehicle doesn't fit the user's input
                        array_push($messages, IControllerMessages::ADD_TRIP_ERROR_MAX_SEATS);
                     }
                  
                }
            }
            else { // none post-request
              if(0 === sizeof($user->getVehicles())) {
                $this->flashMessenger()->clearMessages();
                $this->flashMessenger()->addInfoMessage(\JumpUpDriver\Util\Messages\IControllerMessages::INFO_NO_VEHICLES);
                $redirect = IRouteStore::ADD_VEHICLE;
              }
            }
             
            if("" !== $redirect) { // redirect 
                $this->redirect()->toRoute($redirect);
            }
            else {   // show form
                // show form
                // fetch already filled hidden input fields
                $request = $this->request;
                $startCoord = "";
                $endCoord = "";
                $duration = "";
                $distance = "";
                $overviewPath = "";
                $viaWaypoints = "";
                
                if($request->isPost()) {
                    $startCoord = $request->getPost(TripForm::FIELD_START_COORDINATE);
                    $endCoord = $request->getPost(TripForm::FIELD_END_COORDINATE);
                    $duration = $request->getPost(TripForm::FIELD_DURATION);
                    $distance = $request->getPost(TripForm::FIELD_DISTANCE);
                    $overviewPath = $request->getPost(TripForm::FIELD_OVERVIEW_PATH);
                    $viaWaypoints = $request->getPost(TripForm::FIELD_VIA_WAYPOINTS);
                }
                
                // Create hard-coded inputFields
                $inputFields = array(
                		'<input type="hidden" name="'.TripForm::FIELD_START_COORDINATE.'" value="'.$startCoord.'" />',
                		'<input type="hidden" name="'.TripForm::FIELD_END_COORDINATE.'" value="'.$endCoord.'"  />',
                		'<input type="hidden" name="'.TripForm::FIELD_DURATION.'" value="'.$duration.'"  />',
                		'<input type="hidden" name="'.TripForm::FIELD_DISTANCE.'" value="'.$distance.'"  />',
                		'<input type="hidden" name="'.TripForm::FIELD_OVERVIEW_PATH.'" value="'.$overviewPath.'"  />',
                		'<input type="hidden" name="'.TripForm::FIELD_VIA_WAYPOINTS.'" value="'.$viaWaypoints.'"  />',
                );
                // user's vehicles
                $inputFields = $this->_appendUsersVehicles($inputFields, $user);
                array_push($inputFields, '<input type="submit" name="'.TripForm::SUBMIT.'" value="'.$translator->translate(IControllerMessages::ADD_TRIP_SUBMIT).'" /><br />');
                
                // Export the form and the input fields to the view
                return array(
                        "messages" => $messages, 
                        "form" => $form,
                       "fields" => $inputFields);
            }
        }
    }
    
    /**
     * This action is responsible for deleting a trip.
     * - inputParam: tripId
     * 
     * exports: redirects to the booking overview page.
     */
    public function deleteTripAction() {
        // check if user is logged in and fetch user's entity
        $loggedInUser = $this->_checkAndRedirect ();
        
        $request = $this->getRequest();
        $tripId = ( int ) $request->getPost ( BasicTripForm::FIELD_TRIP_ID );
        $redirect = IRouteStore::ADD_TRIP_ERROR;
        if(null !== $tripId) {
            $trip = $this->_getTrip($tripId);
            if(null !== $trip) {
                if($this->_hasTripAppliedBookings($trip)) {
                    $this->flashMessenger()->clearMessages();
                    $this->flashMessenger()->addMessage(IControllerMessages::ERROR_DELETE_TRIP_ACTIVE_BOOKINGS);
                    $redirect = IRouteStore::BOOK_DRIVER_OVERVIEW;
                }
                else {
                    $this->_deleteTrip($trip);
                    $this->flashMessenger()->addMessage(IControllerMessages::SUCCESS_DELETE_TRIP);
                    $redirect = IRouteStore::BOOK_DRIVER_OVERVIEW;
                }
            }
        }
        $this->redirect()->toRoute($redirect);
    }
    
    /** Check whether a trip has accepted bookings
     * 
     * @param Trip $trip
     * @return boolean true if the given trip as accepted bookings.
     */
    private function _hasTripAppliedBookings(Trip $trip) {        
        foreach ($trip->getBookings() as $booking) {
            if(IBookingState::ACCEPT === $booking->getState()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Delet a given trip and all the belonging bookings.
     * @param Trip $trip
     */
    private function _deleteTrip(Trip $trip) {
        $em = $this->em;
        foreach ($trip->getBookings() as $booking) {
        	$em->remove($booking);
        }
        $em->remove($trip);
        $em->flush();
    }
    

    /**
     * Get the trip instance from the entity manager for a given tripid.
     * @param int $tripId
     * @return Ambigous <object, NULL>
     */
    private function _getTrip($tripId) {
        $tripRepo = $this->em->getRepository(IEntitiesStore::TRIP);
        $trip = $tripRepo->findOneBy(array("id" => $tripId));
        return $trip;
    }
    /**
     * Persist (INSERT INTO) a new trip
     * @param Trip $tripe
     * @return boolean false if the user tries to type in more seats than the vehicle offers.
     */
    private function _persistTrip(Trip $trip) {
        $vehicleId = $this->getRequest()->getPost(TripForm::FIELD_VEHICLE);
        
        $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
        $vehicle = $vehicleRepo->findOneBy(array('id' => $vehicleId));
        if(null !== $vehicle) {
            if($trip->getMaxSeats() > $vehicle->getNumberseats() || $trip->getMaxSeats() < 0) {
                return false;
            }
            $trip->setVehicle($vehicle);
            $this->em->persist($trip);
            $this->em->flush();
            return true;
        }
        return false; // else       
    }

    
    /**
     * Bind hard-coded hidden input fields to our record class Trip.    
     * @param Trip $trip
     * @param User $user
     */
    private function _bindHiddenValues(Trip $trip, User $user) {
        $request = $this->getRequest();
        // fetch post parameters
        $startCoord = $request->getPost(TripForm::FIELD_START_COORDINATE);
        $endCoord = $request->getPost(TripForm::FIELD_END_COORDINATE);
        $duration = $request->getPost(TripForm::FIELD_DURATION);        
        $distance = $request->getPost(TripForm::FIELD_DISTANCE);
        $overviewPath = $request->getPost(TripForm::FIELD_OVERVIEW_PATH);
        $viaWaypoints = $request->getPost(TripForm::FIELD_VIA_WAYPOINTS);

        // bind data
        $trip->setDriver($user);
        $trip->setStartCoordinate($startCoord);
        $trip->setEndCoordinate($endCoord);
        $trip->setDuration($duration);
        $trip->setDistance($distance);
        $trip->setOverviewPath($overviewPath);
        $trip->setViaWaypoints($viaWaypoints);
    }
     
    /**
     * Hard-code selection form of the user's vehicles.
     * @param array $inputFields current hard-coded input fields.
     * @param User $user the user whose vehicles will be rendered in the selection
     */
    private function _appendUsersVehicles(array $inputFields, User $user) {
        array_push($inputFields, '<select name="'.TripForm::FIELD_VEHICLE.'">');
        array_push($inputFields, '<option>--- '.$this->_getTranslator()->translate(IControllerMessages::ADD_TRIP_VEHICLE_CHOOSE). '---</option>');
        $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
        $vehicles = $vehicleRepo->findBy(array('owner' => $user->getId()));
        if(null !== $vehicles) {
            foreach ($vehicles as $vehicle) {
                array_push($inputFields, '<option value="'.$vehicle->getId().'">'.$vehicle->getBrand(). ' '. $vehicle->getType().'</option>');
            }
        }
        array_push($inputFields, '</select>');
        return $inputFields;
    }



}