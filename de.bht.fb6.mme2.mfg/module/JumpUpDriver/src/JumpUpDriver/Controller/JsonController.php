<?php
namespace JumpUpDriver\Controller;

use JumpUpDriver\Json\VehicleJsonWrapper;

use JumpUpDriver\Json\VehicleWrapper;

use Zend\View\Model\JsonModel;

use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * 
* This controller handles all asynchronous requests in this module.
*
* @package    JumpUpDriver\Controller
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      05.05.2013
 */
class JsonController extends AbstractRestfulController {
     /**
     * Key to the parameter name containing the vehId.
     * @var String
     */
    const PARAM_VEHICLE_ID = "vehicleId";
    protected $em;
	
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