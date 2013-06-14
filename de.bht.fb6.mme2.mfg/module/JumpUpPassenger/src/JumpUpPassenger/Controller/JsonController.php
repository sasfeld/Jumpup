<?php
namespace JumpUpPassenger\Controller;



use JumpUpPassenger\Json\TripWrapper;

use JumpUpPassenger\Strategies\DumbRouteStrategy;

use Zend\View\Model\JsonModel;

use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * 
* This controller handles all asynchronous requests in this module.
*
* @package    JumpUpPassenger\Controller
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      05.05.2013
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
    
    /**
    * Fetch all trips from the DB / entity manager.
    * @return an array of Trip.
    */
    protected function _getAllTrips($userId) {      
      $tripsRepo = $this->em->getRepository('JumpUpDriver\Models\Trip');
      // here, we look if the userId was sent by the client.      
      if(null !== $userId) { // yes, so we can prefilter the trips
      	 $queryBuilder = $tripsRepo->createQueryBuilder('u');
      	 $queryBuilder->where("u.id != {$userId}");
      	 $trips = $queryBuilder->getQuery()->getResult();
      }
      else { // no, so he will get all trips
      	$trips = $tripsRepo->findAll();
      }
      return $trips;
    }
    
    protected function _getFindTripStrategy() {
      if(!isset($this->findTripStrategy)) {
        $strategy = new DumbRouteStrategy();
        $this->findTripStrategy = $strategy;
      }
      return $this->findTripStrategy;
    }
	
	/**
     *
     * This constructor is designed for dependency injection.
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }     
    
    /**
     * This action returns a json representation of matching trips.
     * The matching is done by an IFindTripsStrategy.
     * @return JsonModel 
     */
    public function tripAction() {
      $request = $this->getRequest();
      
      if($request->isPost()) {
         $startCoord = "";
         if(null !== $request->getPost(self::PARAM_START_COORD)) {
           $startCoord = $request->getPost(self::PARAM_START_COORD);
         }
         $endCoord = "";
         if(null !== $request->getPost(self::PARAM_END_COORD)) {
           $endCoord = $request->getPost(self::PARAM_END_COORD);
         }
         $dateFrom = "";
         if(null !== $request->getPost(self::PARAM_DATE_FROM)) {
           $dateFrom = $request->getPost(self::PARAM_DATE_FROM);
         }
         $dateTo = "";
         if(null !== $request->getPost(self::PARAM_DATE_TO)) {
           $dateTo = $request->getPost(self::PARAM_DATE_TO);
         }
         $priceFrom = "";
         if(null !== $request->getPost(self::PARAM_PRICE_FROM)) {
           $priceFrom = $request->getPost(self::PARAM_PRICE_FROM);
         }
         $priceTo = "";
         if(null !== $request->getPost(self::PARAM_PRICE_TO)) {
           $priceTo = $request->getPost(self::PARAM_PRICE_TO);
         }
         $userId = null;
         if(null !== $request->getPost(self::PARAM_USER_ID)) {
         	$userId = (int) $request->getPost(self::PARAM_USER_ID);
         }
         
         $trips = $this->_getAllTrips($userId);
         $findStrategy = $this->_getFindTripStrategy();

         $matchedTrips = $findStrategy->findNearTrips($startCoord, $endCoord, $dateFrom, $dateTo, $priceFrom, $priceTo, $trips);
         if(null !== $matchedTrips) {
           $tripWrapper = new TripWrapper();
           $tripWrapper->setTrips($matchedTrips);
           $jsonObj = \Zend\Json\Json::encode($tripWrapper, true);
         }       
         return new JsonModel($jsonObj);
      }
      
      // return bad request message
      $this->getResponse()->setStatusCode(400); // bad request
      return new JsonModel(array ("badRequest" => true));
    } 
    
    /**
     * This action returns a json representation of matching vehicles.
     * The client needs to send the ID of the owner/user as POST.
     */
    public function vehicleAction() {
        $request = $this->getRequest();
      
        
        // set header to json
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine("Content-Type", "application/json");
        //header("Content-Type: application/json");
        
         if($request->isPost()) {
           if(null !== $request->getPost(self::PARAM_VEHICLE_ID)) {
                $ownerId = $request->getPost(self::PARAM_VEHICLE_ID);
                $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
                $vehicleWrapper = new VehicleJsonWrapper();
                $vehicles = $vehicleRepo->findBy(array('id' => $ownerId));
                if(null !== $vehicles) { 
                    $vehicleWrapper->setVehicles($vehicles);                  
                    $jsonObj = \Zend\Json\Json::encode($vehicleWrapper, true); 
                               
                                     
                }              
                return new JsonModel($jsonObj); 
           }     
          
       }
       
        // return bad request message
       $this->getResponse()->setStatusCode(400); // bad request
       return new JsonModel(array ("badRequest" => true));
         
    }
   
	/**
     * Return list of resources
     *
     * @return mixed
     */
    public function getList()
    {
        return new JsonModel(array(
            array('name' => 'test'),
            array('name' => 'second')
        ));
    }
   
/**
     * Return single resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function get($id)
    {
        //TODO: Implement Method
    }
 
    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        //TODO: Implement Method
    }
 
    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        //TODO: Implement Method
    }
 
    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        //TODO: Implement Method
    }
}