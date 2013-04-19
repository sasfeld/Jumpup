<?php
namespace JumpUpUser\Controller;

use Zend\View\Helper\ViewModel;

use JumpUpUser\Util\ServicesUtil;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * 
* The successController instance handles the results from the AuthController.
*
* @package    JumpUpUser\Controller
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      19.04.2013
 */
class SuccessController extends AbstractActionController {
    private $authService;
    
    public function getAuthService() 
    {
        if(!isset($this->authService)) {
            $this->authService = ServicesUtil::getAuthService($this->getServiceLocator());
        }
        return $this->authService;
    }
    /**
     * (non-PHPdoc)
     * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
     */
    public function indexAction() {       
       if(!$this->getAuthService()->hasIdentity()) {
           // we need to redirect to the login form because of missing authentication
           return $this->redirect()->toRoute('login');
       } 
       return new ViewModel();
    }
}