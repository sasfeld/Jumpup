<?php
namespace JumpUpDriver\Controller;

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
            $trip = new Trip();
            $form = $this->getFormStep1();
            $form->setHydrator(new ClassMethods());
            $form->bind($trip);
             
            // Create hard-coded inputFields
            $inputFields = array(
           '<input type="hidden" name="'.TripForm::FIELD_START_COORDINATE.'" />',           
           '<input type="hidden" name="'.TripForm::FIELD_END_COORDINATE.'" />',
            );
             
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
                        $startCoord = $request->getPost(TripForm::FIELD_START_COORDINATE);
                        $endCoord = $request->getPost(TripForm::FIELD_END_COORDINATE);
                        echo $startCoord . " " . $endCoord;
                        $trip->setDriver($user);
                        $this->em->persist($trip);
                        $this->em->flush();
                        $this->flashMessenger()->clearCurrentMessages();
                        $this->flashMessenger()->addMessage(IControllerMessages::SUCCESS_ADD_TRIP);
                        // $this->redirect()->toRoute(IRouteStore::ADD_TRIP_SUCCESS);
                    }
                }
            }
             
             
            // Export the form and the input fields to the view
            return array("form" => $form,
                   "fields" => $inputFields);
        }
    }

   



}