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
    /**
     * The key determining the value for the parameter of the vehicle.
     * @var String
     */
    const PARAM_VEHICLE_ID = "vehId";
    protected $form;

    private function  _getForm() {
        if(!isset($this->form)) {
            $formToBuild = new VehicleForm();
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($formToBuild);            
            $this->form->setHydrator(new ClassMethods());
        }
        return $this->form;
    }

    /**
     * Edit vehicle action
     */
    public function editAction() {
        if($this->_checkAuthentication()) { // auth required
            $form = $this->_getForm();
            if($this->getRequest()->isGet()) {              
                $vehicleId = $this->getRequest()->getQuery(self::PARAM_VEHICLE_ID);
                if(isset($vehicleId)) {
                    $user = $this->getCurrentUser();
                    $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
                    $vehicle = $vehicleRepo->findOneBy(array('id' => $vehicleId,
                									'owner' => $user->getId()));
                    if(null !== $vehicle) { // show form for editing       
                        // Create hard-coded inputFields
                        $inputFields = array(
                           '<input type="hidden" name="'.self::PARAM_VEHICLE_ID.'" value="'.$vehicle->getId().'"/>',                              
                            );                 
                        $form->bind($vehicle);     
                        $form->setAttribute('action', $this->url()->fromRoute(IRouteStore::EDIT_VEHICLE));    
                        return array("form" => $form,
                                    "fields" => $inputFields); // export edit form               
                    }
                }
            }
            else { // post
                $form->setData($this->getRequest()->getPost());
                $vehicle = new Vehicle();
                $form->bind($vehicle);
                if($form->isValid()) {
                    $user = $this->getCurrentUser();
                    if(null === $user) { // user doesn't appear do be logged in
                        $this->flashMessenger()->addErrorMessage(IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED);
                        $this->redirect()->toRoute(IRouteStore::ADD_TRIP_ERROR);
                    }
                    else { // success & user is logged in
                        $vehicleId = $this->getRequest()->getPost(self::PARAM_VEHICLE_ID);
                        $vehicle->setOwner($user);
                        $vehicle->setId($vehicleId);
                        $this->em->merge($vehicle); // update in DB
                        $this->em->flush(); // persist in DB
                        $this->flashMessenger()->addMessage(IControllerMessages::SUCCESS_EDIT_VEHICLE);
                        $this->redirect()->toRoute(IRouteStore::LIST_VEHICLES);
                    }
                }
            }
        }
        
        return array("form" => $form); // export edit form
    }
    

    /**
     * Remove vehicle action.
     */
    public function removeAction() {
        if($this->_checkAuthentication()) { // auth required
            if($this->getRequest()->isGet()) {              
                $vehicleId = $this->getRequest()->getQuery(self::PARAM_VEHICLE_ID);
                if(isset($vehicleId)) {
                    $user = $this->getCurrentUser();
                    $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
                    $vehicle = $vehicleRepo->findOneBy(array('id' => $vehicleId,
                									'owner' => $user->getId()));
                    $this->em->remove($vehicle); // remove from db
                    $this->em->flush();
                    $this->flashMessenger()->clearMessages();
                    $this->flashMessenger()->addMessage(IControllerMessages::SUCCESS_DELETE_VEHICLE);
                    $this->redirect()->toRoute(IRouteStore::LIST_VEHICLES);
                }     
                else {
                    
                }      
               
            }
        }
        
       $this->flashMessenger()->clearMessages();
       $this->flashMessenger()->addMessage(IControllerMessages::DELETE_VEHICLE_NO_ID);
       $this->redirect()->toRoute(IRouteStore::LIST_VEHICLES);
    }

    /**
     * List all vehicles action.
     */
    public function listAction() {
        if($this->_checkAuthentication()) { // auth required
            $user = $this->getCurrentUser();
            $vehicleRepo = $this->em->getRepository('JumpUpDriver\Models\Vehicle');
            $vehicles = $vehicleRepo->findBy(array('owner' => $user->getId()));

            // just export the vehicles array to be shown
            return array("vehicles" => $vehicles,
                        "messages" => $this->flashMessenger()->getMessages(),                       
                        "identifierParam" => self::PARAM_VEHICLE_ID,
                        "removeUrl" => $this->url()->fromRoute(IRouteStore::REMOVE_VEHICLE),
                        "editUrl" => $this->url()->fromRoute(IRouteStore::EDIT_VEHICLE),
                        "addUrl" => $this->url()->fromRoute(IRouteStore::ADD_VEHICLE));
        }
    }

    /**
     * Add vehicle action. A form is shown.
     */
    public function addAction() {
        if($this->_checkAuthentication()) { // auth required
            $vehicle = new Vehicle();
            $form = $this->_getForm();
            $form->setAttribute('action', $this->url()->fromRoute(IRouteStore::ADD_VEHICLE));
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
                        $this->flashMessenger()->addMessage(IControllerMessages::SUCCESS_ADD_VEHICLE);
                        $this->redirect()->toRoute(IRouteStore::LIST_VEHICLES);
                    }
                }
            }
            return array("form" => $form);

        }

    }
}