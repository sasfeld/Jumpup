<?php
namespace JumpUpDriver\Controller;

use JumpUpDriver\Forms\TripForm;

use JumpUpDriver\Util\Routes\IRouteStore;

use Zend\Form\Annotation\AnnotationBuilder;

use Zend\Mvc\Controller\AbstractActionController;



class AddTripController extends AbstractActionController {
  protected $form_step1;
  
  public function  getFormStep1() {
    if(!isset($this->form_step1)) {
      $step1Form = new TripForm();
      $builder = new AnnotationBuilder();
      $this->form_step1 = $builder->createForm($step1Form);
      $this->form_step1->setAttribute('action', $this->url()->fromRoute(IRouteStore::ADD_TRIP).'/step1');
    }
    return $this->form_step1;
  }
  
  public function step1Action() {
       $form = $this->getFormStep1();
       
       
       
       // Export the form to the view
       return array("form" => $form);
  }
  
  
	
}