<?php
namespace JumpUpUser\Controller;

use \JumpUpUser\Util\Auth\CheckAuthentication;
use \JumpUpUser\Export\IAuthenticationRequired;
use \Zend\Mvc\Controller\AbstractActionController;
use JumpUpUser\Util\Messages\IControllerMessages;
use JumpUpUser\Util\Routes\IRouteStore;
use JumpUpUser\Models\User;
use JumpUpUser\Util\ServicesUtil;

/**
 *
 * This controller offers functionalities for all kinds of controller that work with User entities.
 *
 * @package    JumpUpPassenger\Controller
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      07.06.2013
 */
abstract class ANeedsAuthenticationController extends AbstractActionController implements IAuthenticationRequired {
	private $authservice;
	protected $em;
	private $userService;
	
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
     * Fetch the AuthenticationService instance
     */
    private function _getAuthService()
    {
        if(!isset($this->authservice)) {
            $this->authservice = \Application\Util\ServicesUtil::getAuthService($this->getServiceLocator());
        }
        return $this->authservice;
    }
	/**
     * (non-PHPdoc)
     * @see JumpUpUser\Export.IAuthenticationRequired::_checkAuthentication()
     */
    public function _checkAuthentication() {
        $authenticated = CheckAuthentication::isAuthorized($this->_getAuthService(), $this->url());
        if(!$authenticated) {
            CheckAuthentication::redirectToLogin($this); // redirect to login page here
            return false;
        }
        else {
            return true;
        }
    }
    
     /**
     * Fetch the current logged in user. 
     */
    protected function getCurrentUser() {
        $userUtil = \JumpUpUser\Util\ServicesUtil::getUserUtil($this->getServiceLocator());
        return $userUtil->getCurrentUser();
    }
    
    /**
     * Check authentication and if a user can be matched.
     * @return User or redirect to the error/login page if there's no matching user for some reason
     */
    protected function _checkAndRedirect() {
      $auth = $this->_checkAuthentication();
      if(true === $auth) { // authentication required, redirects to login page
        $user = $this->getCurrentUser();
        if(null === $user) { // user doesn't appear do be logged in
          $this->flashMessenger()->addErrorMessage(IControllerMessages::FATAL_ERROR_NOT_AUTHENTIFICATED);
          $this->redirect()->toRoute(IRouteStore::LOGIN); // break;
          return;
        }
        else {
        	return $user;
        }
      }
      else {
      	exit;
      	return false;
      }
    }
    
    /**
     * Save the changed user entity on the database.
     * @param User $user
     */
    protected function _saveChangedUser(User $user) {
    	$this->em->merge($user);
    	$this->em->flush();
    }
    
    /**
     * Get the UserUtil service instance by the ServiceManager.
     * @see JumpUpUser\Util\UserUtil
     */
    protected function _getUserService() {
    	if(null === $this->userService) {
    		$sm = $this->getServiceLocator();
    		$this->userService = ServicesUtil::getUserUtil($sm);
    	}
    	return $this->userService;
    }
    
}