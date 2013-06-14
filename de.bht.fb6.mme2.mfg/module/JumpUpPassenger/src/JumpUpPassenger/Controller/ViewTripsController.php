<?php

namespace JumpUpPassenger\Controller;

use JumpUpPassenger\Strategies\NearestRouteStrategy;
use JumpUpUser\Controller\ANeedsAuthenticationController;
use JumpUpPassenger\Util\IEntitiesStore;
use JumpUpPassenger\Util\Messages\ILabels;
use \JumpUpDriver\Models\Trip;
use JumpUpPassenger\Util\Messages\IControllerMessages;
use JumpUpPassenger\Strategies\DumbRouteStrategy;
use Zend\Stdlib\Hydrator\ClassMethods;
use JumpUpPassenger\Util\Routes\IRouteStore;
use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpPassenger\Forms\LookUpTripsForm;
use Application\Util\FormTransmitterUtil;
use Zend\Form\Form;

/**
 *
 *
 * The ViewTripsController handles the rendering of routes stored in the DB.
 *
 * @package JumpUpPassenger\Controller
 * @subpackage
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 07.05.2013
 */
class ViewTripsController extends ANeedsAuthenticationController {
	protected $form;
	protected $findTripStrategy;
	protected function _getForm() {
		if (! isset ( $this->form )) {
			$lookUpForm = new LookUpTripsForm ();
			$builder = new AnnotationBuilder ();
			$this->form = $builder->createForm ( $lookUpForm );
			$this->form->setHydrator ( new ClassMethods () );
			$this->form->setAttribute ( 'action', $this->url ()->fromRoute ( IRouteStore::SHOW_TRIPS ) );
		}
		return $this->form;
	}
	protected function _getFindTripStrategy() {
		if (! isset ( $this->findTripStrategy )) {
			$strategy = new NearestRouteStrategy ();
			$this->findTripStrategy = $strategy;
		}
		return $this->findTripStrategy;
	}
	/**
	 *
	 * @deprecated because we use AJAX in the frontend.
	 *             Show results of a find trips strategy.
	 * @return an array of trips. Those trips shall be rendered by the view / javascript / google map.
	 */
	public function showTripsAction() {
		$user = $this->_checkAndRedirect ();
		// @TODO the view needs to send the coordinates for the user's input
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form = $this->_getForm ();
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				$findTripStrategy = $this->_getFindTripStrategy ();
				$trips = $this->_getAllTrips ();
				$locationName = $request->getPost ( LookUpTripsForm::FIELD_START_POINT );
				$destinationName = $request->getPost ( LookUpTripsForm::FIELD_END_POINT );
				$location = $request->getPost ( LookUpTripsForm::FIELD_START_COORD );
				$destination = $request->getPost ( LookUpTripsForm::FIELD_END_COORD );
				$dateFrom = $request->getPost ( LookUpTripsForm::FIELD_START_DATE );
				$dateTo = $request->getPost ( LookUpTripsForm::FIELD_END_DATE );
				$priceFrom = $request->getPost ( LookUpTripsForm::FIELD_PRICE_FROM );
				$priceTo = $request->getPost ( LookUpTripsForm::FIELD_PRICE_TO );
				$matchedTrips = $findTripStrategy->findNearTrips ( $location, $destination, $dateFrom, $dateTo, $priceFrom, $priceTo, $trips );
				
				return array (
						'matchedTrips' => $matchedTrips 
				);
			}
		}
		$this->flashMessenger ()->clearMessages ();
		$this->flashMessenger ()->addMessage ( IControllerMessages::ERROR_LOOKUP_FORM );
		$this->redirect ()->toRoute ( IRouteStore::LOOKUP_TRIPS );
	}
	/**
	 * Handle lookup of trips in the database.
	 * 
	 * @return a form.
	 */
	public function lookUpAction() {
		$user = $this->_checkAndRedirect ();
		$request = $this->getRequest ();
		
		// hard-coded hidden input fields to be bind by javascript
		$inputFields = array (
				'<input type="hidden" name="' . LookUpTripsForm::FIELD_USER_ID . '" value="' . $user->getId () . '"/>',
				'<input type="hidden" name="' . LookUpTripsForm::FIELD_START_COORD . '" />',
				'<input type="hidden" name="' . LookUpTripsForm::FIELD_END_COORD . '" />',
				'<input type="button" name="' . LookUpTripsForm::BUTTON . '" value="' . ILabels::LOOKUP_BUTTON . '"/>' 
		);
		
		// GET method -> only return the input form
		$messages = $this->flashMessenger ()->getMessages ();
		$form = $this->_getForm ();
		$this->_fillFormFieldsBy ($form, $messages );
		return array (
				'form' => $form,
				'messages' => $messages,
				'inputFields' => $inputFields 
		);
	}
	private function _fillFormFieldsBy(Form $form, array $messages) {
		foreach ( $messages as $message ) {
			if (FormTransmitterUtil::isValidMessage ( $message )) {				
				$decodedArray = FormTransmitterUtil::decodeMessage ( $message );
				if(null !== $decodedArray) {
					$elName = $decodedArray[FormTransmitterUtil::ELEMENT_NAME];
					$elValue = $decodedArray[FormTransmitterUtil::ELEMENT_VALUE];
					$formEl = $form->get($elName);
					if(null !== $formEl) {
						$formEl->setValue($elValue);
					}
				}
			}
		}
	}
	
	/**
	 * Fetch all trips from the DB / entity manager.
	 * 
	 * @return an array of Trip.
	 */
	protected function _getAllTrips() {
		$tripsRepo = $this->em->getRepository ( IEntitiesStore::TRIP );
		$trips = $tripsRepo->findAll ();
		return $trips;
	}
}