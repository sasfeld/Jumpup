<?php

namespace JumpUpPassenger\Controller;

use JumpUpPassenger\Json\TripWrapper;
use JumpUpPassenger\Strategies\DumbRouteStrategy;
use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
use JumpUpPassenger\Forms\LookUpTripsForm;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\Hydrator\ClassMethods;
use JumpUpPassenger\Util\Routes\IRouteStore;
use Zend\Form\Form;
use Application\Util\ServicesUtil;
use JumpUpPassenger\Util\Messages\IControllerMessages;
use JumpUpUser\Util\IEntitiesStore;
use JumpUpPassenger\Strategies\NearestRouteStrategy;
use JumpUpPassenger\Util\GmapCoordUtil;

/**
 *
 *
 * This controller handles all asynchronous requests in this module.
 *
 * @package JumpUpPassenger\Controller
 * @subpackage
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 05.05.2013
 */
class JsonController extends AbstractRestfulController {
	const PARAM_START_COORD = "startCoord";
	const PARAM_END_COORD = "endCoord";
	const PARAM_DATE_FROM = "dateFrom";
	const PARAM_DATE_TO = "dateTo";
	const PARAM_PRICE_FROM = "priceFrom";
	const PARAM_PRICE_TO = "priceTo";
	const PARAM_USER_ID = "userId";
	protected $em;
	protected $lookupform;
	protected $translator;
	
	
	protected function _getTranslator() {
		if(null === $this->translator) {
			$this->translator = ServicesUtil::getTranslatorService($this->getServiceLocator());
		}
		return $this->translator;
	}
	/**
	 * Fetch all trips from the DB / entity manager.
	 * 
	 * @return an array of Trip.
	 */
	protected function _getAllTrips($userId) {
		$tripsRepo = $this->em->getRepository ( 'JumpUpDriver\Models\Trip' );	
		$trips = $tripsRepo->findAll ();	
		return $trips;
	}
	protected function _getFindTripStrategy() {
		if (! isset ( $this->findTripStrategy )) {
			$strategy = new NearestRouteStrategy();
			$this->findTripStrategy = $strategy;
		}
		return $this->findTripStrategy;
	}
	
	/**
	 *
	 *
	 * This constructor is designed for dependency injection.
	 * 
	 * @param \Doctrine\ORM\EntityManager $em        	
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em) {
		$this->em = $em;
	}
	private function _getLookUpForm() {
		if (! isset ( $this->lookupform )) {
			$lookUpForm = new LookUpTripsForm ();
			$builder = new AnnotationBuilder ();
			$this->lookupform = $builder->createForm ( $lookUpForm );
			$this->lookupform->setHydrator ( new ClassMethods () );
		}
		return $this->lookupform;
	}
	/**
	 * This action returns a json representation of matching trips.
	 * The matching is done by an IFindTripsStrategy.
	 * 
	 * @return JsonModel
	 */
	public function tripAction() {
		$request = $this->getRequest ();
		
		if ($request->isPost ()) {
			$startCoord = "";
			
			$form = $this->_getLookUpForm ();
			$form->setData ( $request->getPost () );
			
			$jsonObj = null;
			if ($form->isValid ()) {
				if (null !== $request->getPost ( LookUpTripsForm::FIELD_START_COORD )) {
					$startCoord = $request->getPost ( LookUpTripsForm::FIELD_START_COORD );
				}
				$endCoord = "";
				if (null !== $request->getPost ( LookUpTripsForm::FIELD_END_COORD )) {
					$endCoord = $request->getPost ( LookUpTripsForm::FIELD_END_COORD );
				}
				$dateFrom = "";
				if (null !== $request->getPost ( LookUpTripsForm::FIELD_START_DATE )) {
					$dateFrom = $request->getPost ( LookUpTripsForm::FIELD_START_DATE );
				}
				$dateTo = "";
				if (null !== $request->getPost ( LookUpTripsForm::FIELD_END_DATE )) {
					$dateTo = $request->getPost ( LookUpTripsForm::FIELD_END_DATE );
				}
				$priceFrom = "";
				if (null !== $request->getPost ( LookUpTripsForm::FIELD_PRICE_FROM )) {
					$priceFrom = $request->getPost ( LookUpTripsForm::FIELD_PRICE_FROM );
				}
				$priceTo = "";
				if (null !== $request->getPost ( LookUpTripsForm::FIELD_PRICE_TO )) {
					$priceTo = $request->getPost ( LookUpTripsForm::FIELD_PRICE_TO);
				}
				$userId = null;
				if (null !== $request->getPost ( self::PARAM_USER_ID )) {
					$userId = ( int ) $request->getPost ( self::PARAM_USER_ID );
				}				
				$maxDistance = (int) $request->getPost( LookUpTripsForm::FIELD_MAX_DISTANCE);
				
				
				
				$trips = $this->_getAllTrips ( $userId );
				$passenger = $this->_getUser( $userId);
				$findStrategy = $this->_getFindTripStrategy ();
				
				$matchedTrips = $findStrategy->findNearTrips ( $startCoord, $endCoord, $dateFrom, $dateTo, $priceFrom, $priceTo, $trips, $passenger, $maxDistance );
				if (null !== $matchedTrips && sizeof($matchedTrips) != 0) {
				    $this->_calculatePriceForPassenger($startCoord, $endCoord, $matchedTrips);
					$tripWrapper = new TripWrapper ();
					$tripWrapper->setTrips ( $matchedTrips );
					$jsonObj = \Zend\Json\Json::encode ( $tripWrapper, true );
				}
				else { // no matching trips
					$jsonObj = array ( "noTrips" => true,			
							"user" => $passenger->getPrename(),			
							"userMessage" => $this->_getTranslator()->translate(IControllerMessages::LOOKUP_NO_TRIPS),
							"dateFrom" => $dateFrom,
							"dateTo" => $dateTo,
					);
				}
			}
			else {
				$validationMessages = $this->_getValidationMessages($form);
				$jsonObj = array ( "validationFail" => true,
								   "validationMessages" => $validationMessages,
								   "userMessage" => $this->_getTranslator()->translate(IControllerMessages::ERROR_LOOKUP_VALIDATION), 
				 );
			}
			return new JsonModel ( $jsonObj );
		}
		
		// return bad request message
		$this->getResponse ()->setStatusCode ( 400 ); // bad request
		return new JsonModel ( array (
				"badRequest" => true 
		) );
	}
	
	/**
	 * Calculate price for passenger depending on the distance and set it on the matched trip.
	 * @param String $startCoord
	 * @param String $endCoord
	 * @param array $matchedTrips
	 * @return void - the price will be set on each trip instance immediatly.
	 */
	protected function _calculatePriceForPassenger($startCoord, $endCoord, array $matchedTrips) {	     
	    foreach ($matchedTrips as $matchedTrip) {	        
	        $priceForTrip = GmapCoordUtil::calcPriceForPassenger($matchedTrip, $startCoord, $endCoord);; 
	    	$matchedTrip->setPriceRecommendationForPassenger($priceForTrip);
	    }
	}
	
	protected function _getUser( $userId) {
		$userRepo = $this->em->getRepository(IEntitiesStore::USER);
		$user = $userRepo->findOneBy(array('id' => $userId));
		return $user;
	}
	
	/**
	 * Get the validation messages by the given ZendForm.
	 * @param Form $form 
	 * @return an associative array in the form 'elementName' => 'validationMessages'
	 */
	private function _getValidationMessages(Form $form) {
		$messages = array();
		foreach ($form->getElements() as $formElement) {
			$messages[$formElement->getName()] = $formElement->getMessages();
		}
		return $messages;
	}
	
	/**
	 * This action returns a json representation of matching vehicles.
	 * The client needs to send the ID of the owner/user as POST.
	 */
	public function vehicleAction() {
		$request = $this->getRequest ();
		
		// set header to json
		$headers = $this->getResponse ()->getHeaders ();
		$headers->addHeaderLine ( "Content-Type", "application/json" );
		// header("Content-Type: application/json");
		
		if ($request->isPost ()) {
			if (null !== $request->getPost ( self::PARAM_VEHICLE_ID )) {
				$ownerId = $request->getPost ( self::PARAM_VEHICLE_ID );
				$vehicleRepo = $this->em->getRepository ( 'JumpUpDriver\Models\Vehicle' );
				$vehicleWrapper = new VehicleJsonWrapper ();
				$vehicles = $vehicleRepo->findBy ( array (
						'id' => $ownerId 
				) );
				if (null !== $vehicles) {
					$vehicleWrapper->setVehicles ( $vehicles );
					$jsonObj = \Zend\Json\Json::encode ( $vehicleWrapper, true );
				}
				return new JsonModel ( $jsonObj );
			}
		}
		
		// return bad request message
		$this->getResponse ()->setStatusCode ( 400 ); // bad request
		return new JsonModel ( array (
				"badRequest" => true 
		) );
	}
	
	/**
	 * Return list of resources
	 *
	 * @return mixed
	 */
	public function getList() {
		return new JsonModel ( array (
				array (
						'name' => 'test' 
				),
				array (
						'name' => 'second' 
				) 
		) );
	}
	
	/**
	 * Return single resource
	 *
	 * @param mixed $id        	
	 * @return mixed
	 */
	public function get($id) {
		// TODO: Implement Method
	}
	
	/**
	 * Create a new resource
	 *
	 * @param mixed $data        	
	 * @return mixed
	 */
	public function create($data) {
		// TODO: Implement Method
	}
	
	/**
	 * Update an existing resource
	 *
	 * @param mixed $id        	
	 * @param mixed $data        	
	 * @return mixed
	 */
	public function update($id, $data) {
		// TODO: Implement Method
	}
	
	/**
	 * Delete an existing resource
	 *
	 * @param mixed $id        	
	 * @return mixed
	 */
	public function delete($id) {
		// TODO: Implement Method
	}
}