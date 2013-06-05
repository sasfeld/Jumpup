<?php
namespace JumpUpPassenger\Controller;

use \JumpUpUser\Util\Auth\CheckAuthentication;
use \JumpUpUser\Export\IAuthenticationRequired;
use \Zend\Mvc\Controller\AbstractActionController;

abstract class ANeedsAuthenticationController extends AbstractActionController implements IAuthenticationRequired {
	protected $authservice;
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