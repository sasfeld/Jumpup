<?php
namespace JumpUpDriver\Controller;

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



/**
 * 
* This controller handles the addition / modification / removement of user-defined vehicles.
*
* @package    JumpUpDriver\Controller
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      03.05.2013
 */
class VehicleController extends ANeedsAuthenticationController{
    protected $form;    
    
     private function  _getForm() {
        if(!isset($this->form)) {
            $formToBuild = new VehicleForm();
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($formToBuild);
            $this->form->setAttribute('action', $this->url()->fromRoute(IRouteStore::ADD_VEHICLE));
            $this->form->setHydrator(new ClassMethods());
        }
        return $this->form;
    }
   	
    /**
     * List all vehicles action.
     */
    public function listAction() {
        $user = $this->getCurrentUser();
        $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
        $vehicles = $vehicleRepo->findBy(array('owner' => $user->getId()));
        
        // just export the vehicles array to be shown
        return array("vehicles" => $vehicles);
    }
    
    public function addAction() {
        if($this->_checkAuthentication()) {
            $vehicle = new Vehicle();
            $form = $this->_getForm();
            $form->bind($vehicle);
            
            $request = $this->getRequest();
            
            if($request->isPost()) {
                $form->setData($request->getPost());
                if($form->isValid())  {
                    $user = $this->getCurrentUser();
                    if(null === $user) { // user doesn't appear do be logged in
                        $this->flashMessenger()->addErrorMessage(IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED);
                        $this->redirect()->toRoute(IRouteStore::ADD_TRIP_ERROR);
                    }
                    else { // success & user is logged in
                        $vehicle->setOwner($user);
                        $this->em->persist($vehicle);
                        $this->em->flush(); // persist in DB
                        $this->redirect()->toRoute(IRouteStore::LIST_VEHICLES);
                    }
                }
            }
            return array("form" => $form);
            
        }
        
    }    
}