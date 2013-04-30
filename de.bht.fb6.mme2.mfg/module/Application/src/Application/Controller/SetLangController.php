<?php
namespace Application\Controller;

use JumpUpUser\Util\UserUtil;

use JumpUpUser\Util\Auth\CheckAuthentication;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Util\ServicesUtil;

/**
* 
* This controller handles a request to change the current language
*
*
* @package    
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      30.04.2013
 */
class SetLangController extends AbstractActionController {
    private $translator;
    private $em;
    private $authservice;
    private $_userUtil;
    

    
    private function _getTranslator() {
        if(null == $this->translator) {
            $this->translator = ServicesUtil::getTranslatorService($this->getServiceLocator()); 
        }        
        return $this->translator;
    }
    
	/**
     * Fetch the AuthenticationService instance  
     */
    private function _getAuthService() 
    {
        if(!isset($this->authservice)) {
            $this->authservice = ServicesUtil::getAuthService($this->getServiceLocator());
        }
        return $this->authservice;
    }
    
    
    
    public function indexAction() {
        
    }
    
    /**
     * Set the german language file.
     */
    public function deAction() {
        $translator = $this->_getTranslator();               
        $lang = 'de_DE';        
        $this->_persistSelection($lang);
    }
    
    /**
     * Set the english language file.
     */
    public function enAction() {
        $translator = $this->_getTranslator();       
        $lang = 'en_US';          
        $this->_persistSelection($lang);
    }
    
    /**
     * Persist the language selection depending on whether the user is logged in or not.
     * @param unknown_type $localStr
     */
    private function _persistSelection($localStr) {
        if(CheckAuthentication::isAuthorized($this->_getAuthService(), $this->url())) { // update user's prefered language
            $loggendInUser = $this->_getUserUtil()->getCurrentUser();
            $loggendInUser->setLocale($localStr);
            $this->_getUserUtil()->updateUser($loggendInUser); // perform DB update
            $this->flashMessenger()->addMessage("You're prefered language was successfully changed.");
            $this->redirect()->toRoute(\JumpUpUser\Util\Routes\IRouteStore::LOGIN);
        }
        else { // @TODO
           // $translator->setLocale($localStr);
           // echo $translator->translate("DummyTest");
        }         
    }
    
 	/**
     * Fetch the currently logged in user.   
     */
    protected function _getUserUtil() {
        if(null === $this->_userUtil) {
            $this->_userUtil = \JumpUpUser\Util\ServicesUtil::getUserUtil($this->getServiceLocator());
        }       
        return $this->_userUtil;
    }
    
    
    
    
}