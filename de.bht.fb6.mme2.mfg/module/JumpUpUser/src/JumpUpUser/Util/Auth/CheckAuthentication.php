<?php
namespace JumpUpUser\Util\Auth;

use Zend\Mvc\Controller\AbstractController;

use Zend\Authentication\AuthenticationService;

class CheckAuthentication {    
     /**
     * Check if a the user is within an anonymous page or if he has an idendity by the AuthenticationService yet.
     * @param AuthenticationService $as
     * @param String $requestUrl
     * @return boolean true if the user is authorized
     */
     static public function isAuthorized(AuthenticationService $as, $requestUrl) {
         //@TODO: maybe write config...
         // ask AuthenticationService
         return $as->hasIdentity();
     }
     
     /**
      * Redirect to the login page.      
      * @param AbstractController $ctrl
      */
     static public function redirectToLogin(AbstractController $ctrl) {
         $ctrl->redirect()->toRoute(\JumpUpUser\Util\Routes\IRouteStore::LOGIN);
     }
}