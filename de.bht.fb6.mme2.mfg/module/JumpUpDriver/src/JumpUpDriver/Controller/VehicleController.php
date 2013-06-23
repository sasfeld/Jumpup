<?php

namespace JumpUpDriver\Controller;

use JumpUpUser\Controller\ANeedsAuthenticationController;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpDriver\Util\Messages\IControllerMessages;
use JumpUpDriver\Models\Vehicle;
use JumpUpDriver\Util\ServicesUtil;
use JumpUpUser\Util\Auth\CheckAuthentication;
use JumpUpUser\Export\IAuthenticationRequired;
use JumpUpDriver\Util\Routes\IRouteStore;
use JumpUpDriver\Forms\VehicleForm;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Util\FilesUtil;

/**
 *
 *
 *
 * This controller handles the addition / modification / removement of user-defined vehicles.
 *
 * @package JumpUpDriver\Controller
 * @subpackage
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 03.05.2013
 */
class VehicleController extends ANeedsAuthenticationController {
	/**
	 * The key determining the value for the parameter of the vehicle.
	 *
	 * @var String
	 */
	const PARAM_VEHICLE_ID = "vehId";
	protected $form;
	private function _getForm() {
		if (! isset ( $this->form )) {
			$formToBuild = new VehicleForm ();
			$builder = new AnnotationBuilder ();
			$this->form = $builder->createForm ( $formToBuild );
			$this->form->setHydrator ( new ClassMethods () );
		}
		return $this->form;
	}
	
	/**
	 * Edit vehicle action
	 */
	public function editAction() {
		if ($this->_checkAuthentication ()) { // auth required
			$form = $this->_getForm ();
			$request = $this->getRequest ();
			if ($this->getRequest ()->isGet ()) {
				$vehicleId = $this->getRequest ()->getQuery ( self::PARAM_VEHICLE_ID );
				if (isset ( $vehicleId )) {
					$user = $this->getCurrentUser ();
					$vehicleRepo = $this->em->getRepository ( 'JumpUpDriver\Models\Vehicle' );
					$vehicle = $vehicleRepo->findOneBy ( array (
							'id' => $vehicleId,
							'owner' => $user->getId () 
					) );
					if (null !== $vehicle) { // show form for editing
					                         // Create hard-coded inputFields
						$inputFields = array (
								'<input type="hidden" name="' . self::PARAM_VEHICLE_ID . '" value="' . $vehicle->getId () . '"/>' 
						);
						$form->bind ( $vehicle );
						$form->setAttribute ( 'action', $this->url ()->fromRoute ( IRouteStore::EDIT_VEHICLE ) );
						return array (
								"form" => $form,
								"fields" => $inputFields 
						); // export edit form
					}
				}
			} else { // post
				$data = $request->getPost ();
				// get upload files if available (they are optional)
				if (null !== $request->getFiles ()) {
					$data = array_merge_recursive ( $request->getPost ()->toArray (), $request->getFiles ()->toArray () );
				}
				$form->setData ( $data );
				$vehicle = new Vehicle ();
				$form->bind ( $vehicle );
				if ($form->isValid ()) {
					$user = $this->getCurrentUser ();
					if (null === $user) { // user doesn't appear do be logged in
						$this->flashMessenger ()->addErrorMessage ( IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED );
						$this->redirect ()->toRoute ( IRouteStore::ADD_TRIP_ERROR );
					} else { // success & user is logged in
					         // move pic and get the full path to it
						if (null !== $data [VehicleForm::FIELD_VEHICLE_PIC]) {
							$pathVehiclePic = FilesUtil::moveUploadedFile ( $data [VehicleForm::FIELD_VEHICLE_PIC], $user, FilesUtil::TYPE_VEHICLE_PIC, $vehicle );
							$vehicle->setVehiclepic ( $pathVehiclePic );
						}
						$vehicleId = $this->getRequest ()->getPost ( self::PARAM_VEHICLE_ID );
						$vehicle->setOwner ( $user );
						$vehicle->setId ( $vehicleId );
						$this->em->merge ( $vehicle ); // update in DB
						$this->em->flush (); // persist in DB
						$this->flashMessenger ()->addMessage ( IControllerMessages::SUCCESS_EDIT_VEHICLE );
						$this->redirect ()->toRoute ( IRouteStore::LIST_VEHICLES );
					}
				}
			}
		}
		
		return array (
				"form" => $form 
		); // export edit form
	}
	
	/**
	 * Remove vehicle action.
	 */
	public function removeAction() {
		$redirect = IRouteStore::LIST_VEHICLES;
		if ($this->_checkAuthentication ()) { // auth required
			if ($this->getRequest ()->isGet ()) {
				$vehicleId = $this->getRequest ()->getQuery ( self::PARAM_VEHICLE_ID );
				if (isset ( $vehicleId )) {
					$user = $this->getCurrentUser ();
					$vehicleRepo = $this->em->getRepository ( 'JumpUpDriver\Models\Vehicle' );
					$vehicle = $vehicleRepo->findOneBy ( array (
							'id' => $vehicleId,
							'owner' => $user->getId () 
					) );
					// check, whether the vehicle is contained in a trip
					if (! $this->_isContainedInTrip ( $vehicle )) {
						// remove pic if available
						$picPath = $vehicle->getVehiclepic ();
						if (null !== $picPath) {
							$success = unlink ( $picPath );
						}
						$this->em->remove ( $vehicle ); // remove from db
						$this->em->flush ();
						$this->flashMessenger ()->clearMessages ();
						$this->flashMessenger ()->addMessage ( IControllerMessages::SUCCESS_DELETE_VEHICLE );
					}
					else { // vehicle is contained in a trip -> show error message
						$this->flashMessenger()->addMessage( IControllerMessages::DELETE_VEHICLE_IS_IN_TRIP);
					}
				} else {
					$this->flashMessenger ()->clearMessages ();
					$this->flashMessenger ()->addMessage ( IControllerMessages::DELETE_VEHICLE_NO_ID );
				}
			}
		}
		
		$this->redirect ()->toRoute ( $redirect );
	}
	
	/**
	 * List all vehicles action.
	 */
	public function listAction() {
		if ($this->_checkAuthentication ()) { // auth required
			$user = $this->getCurrentUser ();
			$vehicleRepo = $this->em->getRepository ( 'JumpUpDriver\Models\Vehicle' );
			$vehicles = $vehicleRepo->findBy ( array (
					'owner' => $user->getId () 
			) );
			
			// just export the vehicles array to be shown
			return array (
					"vehicles" => $vehicles,
					"messages" => $this->flashMessenger ()->getMessages (),
					"identifierParam" => self::PARAM_VEHICLE_ID,
					"removeUrl" => $this->url ()->fromRoute ( IRouteStore::REMOVE_VEHICLE ),
					"editUrl" => $this->url ()->fromRoute ( IRouteStore::EDIT_VEHICLE ),
					"addUrl" => $this->url ()->fromRoute ( IRouteStore::ADD_VEHICLE ) 
			);
		}
	}
	
	/**
	 * Add vehicle action.
	 * A form is shown.
	 */
	public function addAction() {
		if ($this->_checkAuthentication ()) { // auth required
		                                      // check if the request was redirected by the AddTripController
			$infoMessages = "";
			if ($this->flashMessenger ()->hasInfoMessages ()) {
				$infoMessages = $this->flashMessenger ()->getInfoMessages ();
				$this->flashMessenger ()->clearMessages ();
				$this->flashMessenger ()->addInfoMessage ( IControllerMessages::INFO_NO_VEHICLES );
			}
			
			$vehicle = new Vehicle ();
			$form = $this->_getForm ();
			$form->setAttribute ( 'action', $this->url ()->fromRoute ( IRouteStore::ADD_VEHICLE ) );
			$form->bind ( $vehicle );
			
			$request = $this->getRequest ();
			
			if ($request->isPost ()) {
				$data = $request->getPost ();
				// get upload files if available (they are optional)
				if (null !== $request->getFiles ()) {
					$data = array_merge_recursive ( $request->getPost ()->toArray (), $request->getFiles ()->toArray () );
				}
				$form->setData ( $data );
				if ($form->isValid ()) {
					$user = $this->getCurrentUser ();
					if (null === $user) { // user doesn't appear do be logged in
						$this->flashMessenger ()->addErrorMessage ( IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED );
						$this->redirect ()->toRoute ( IRouteStore::ADD_TRIP_ERROR );
					} else { // success & user is logged in
						$vehicle->setOwner ( $user );
						// move pic and get the full path to it
						if (null !== $data [VehicleForm::FIELD_VEHICLE_PIC]) {
							$pathVehiclePic = FilesUtil::moveUploadedFile ( $data [VehicleForm::FIELD_VEHICLE_PIC], $user, FilesUtil::TYPE_VEHICLE_PIC, $vehicle );
							$vehicle->setVehiclepic ( $pathVehiclePic );
						}
						$this->em->persist ( $vehicle );
						$this->em->flush (); // persist in DB
						$this->flashMessenger ()->addMessage ( IControllerMessages::SUCCESS_ADD_VEHICLE );
						$redirectRoute = IRouteStore::LIST_VEHICLES;
						if ($this->flashMessenger ()->hasInfoMessages ()) {
							$redirectRoute = IRouteStore::ADD_TRIP;
							$this->flashMessenger ()->clearMessages ();
						}
						$this->redirect ()->toRoute ( $redirectRoute );
					}
				}
			}
			return array (
					"form" => $form,
					"infoMessages" => $infoMessages 
			);
		}
	}
	
	/**
	 * Check whether the given vehicle is contained in a trip.
	 * @param Vehicle $vehicle
	 * @return boolean true if the vehicle is contained in any trip.
	 */
	protected function _isContainedInTrip (Vehicle $vehicle) {
		if(null !== $vehicle->getIntrips() && (sizeof($vehicle->getIntrips() > 0))) {
			return true;
		} 
		return false;
	}
}