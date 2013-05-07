<?php
namespace JumpUpPassenger\Controller;
use Zend\Stdlib\Hydrator\ClassMethods;

use JumpUpPassenger\Util\Routes\IRouteStore;

use Zend\Form\Annotation\AnnotationBuilder;

use JumpUpPassenger\Forms\LookUpTripsForm;

/**
 * 
* The ViewTripsController handles the rendering of routes stored in the DB.
*
* @package    JumpUpPassenger\Controller
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      07.05.2013
 */
class ViewTripsController extends ANeedsAuthenticationController{
    protected $form;
    
    protected function _getForm() {
        if(!isset($this->form)) {
            $lookUpForm = new LookUpTripsForm();
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($lookUpForm);
            $this->form->setHydrator(new ClassMethods());
        }
        return $this->form;
    }
    
  /**
     * Show results of a find trips strategy.
     * @return an array of trips. Those trips shall be rendered by the view / javascript / google map.
     */
    public function showTripsAction() {
       $user = $this->_checkAndRedirect();
       
       // @TODO the view needs to send the coordinates for the user's input
       
       
    }
    /**
     * Handle lookup of trips in the database.
     * @return a form.
     */
    public function lookUpAction() {
            $user = $this->_checkAndRedirect();
            $request = $this->getRequest();               
            // GET method -> only return the input form            
            return array('form' => $this->_getForm());
     }
    
    
    /**
     * Check authentication and if a user can be matched.
     * @return User or redirect to the error/login page if there's no matching user for some reason
     */
    protected function _checkAndRedirect() {
        if($this->_checkAuthentication()) { // authentication required, redirects to login page
            $user = $this->getCurrentUser();
            if(null === $user) { // user doesn't appear do be logged in
                $this->flashMessenger()->addErrorMessage(IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED);
                $this->redirect()->toRoute(IRouteStore::LOOKUP_ERROR); // break;
            }
            return $user; 
        }
    }
}