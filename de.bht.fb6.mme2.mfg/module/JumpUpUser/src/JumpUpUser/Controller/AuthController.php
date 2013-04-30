<?php
namespace JumpUpUser\Controller;

use Zend\Session\Storage\SessionStorage;

use Zend\I18n\Translator\Translator;

use JumpUpUser\Forms\LoginForm;

use JumpUpUser\Util\Routes\IRouteStore;

use Zend\Filter\Encrypt;

use JumpUpUser\Filters\RegistrationFormFilter;

use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpUser\Models\LoginDumb;
use JumpUpUser\Util\ServicesUtil;
use Zend\Mvc\Controller\AbstractActionController;
use JumpUpUser\Util\Exception_Util;
/**
 * 
* The AuthController instance handles the login action and authentication storage.
*
*
* @package    JumpUpUser\Controller
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      19.04.2013
 */
class AuthController extends AbstractActionController {
    protected $form;
    protected $storage;
    protected $authservice;
    protected $translator;
    
    
    /**
     * 
     * Fetch the translator instance
     */
    public function getTranslatorService() {
        if(!isset($this->translator)) {
           $this->translator = ServicesUtil::getTranslatorService($this->getServiceLocator());
        }
        return $this->translator;
    }
    
    /**
     * Fetch the AuthenticationService instance  
     */
    public function getAuthService() 
    {
        if(!isset($this->authservice)) {
            $this->authservice = ServicesUtil::getAuthService($this->getServiceLocator());
        }
        return $this->authservice;
    }
    
    /**
     * Fetch the AuthenticationStorage instance
     */
    public function getSessionStorage() 
    {
        if(!isset($this->storage)) {
            $this->storage = ServicesUtil::getSessionStorageService($this->getServiceLocator());
        }
        return $this->storage;
    }
    
    /**
     * Build a form using the annotation builder.
     * @see LoginDumb
     */
    public function getForm() 
    {
        if(!isset($this->form)) {
            $loginForm = new LoginForm();
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($loginForm);
            $this->form->setAttribute('action', $this->url()->fromRoute(IRouteStore::LOGIN).'/authenticate');
        }
        return $this->form;
    }
    
    /**
     * The login action.
     */    
    public function loginAction() 
    {
        // login already performed?
        if($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute(IRouteStore::LOGIN_SUCCESS);
        }
        else { // export login form
            $form = $this->getForm();
            return array(
                'form' => $form,
                'messages' => $this->flashMessenger()->getMessages(),
            );
        }
        
    }   
     

     
    
    /**
     * Authenticate action which redirects to login action.
     */
    public function authenticateAction() 
    {
        $form = $this->getForm();
        $redirect = IRouteStore::LOGIN;
        
        $request = $this->getRequest();
        if($request->isPost()) { // authenticate user      
        
            $form->setData($request->getPost());
            
            if($form->isValid()) {                                
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost(LoginForm::FIELD_USERNAME))
                                       ->setCredential($request->getPost(LoginForm::FIELD_PASSWORD));
                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message) {
                    // save session-based message
                    $this->flashMessenger()->addMessage($this->getTranslatorService()->translate($message));
                }
                if($result->isValid()) { // successful authentication                    
                    $redirect = IRouteStore::LOGIN_SUCCESS;
                    // save username on the client
                    if(1 == $request->getPost(LoginForm::FIELD_REMEMBER_ME)) {
                        $this->getSessionStorage()->setRememberMe(1);                       
                        // set storage again
                         // write in cookie
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }                   
                    $this->getAuthService()->getStorage()->write($request->getPost(LoginForm::FIELD_USERNAME));                    
                }
            }
        }
        // redirect to login action        
       $this->redirect()->toRoute($redirect);
    }
    
    /**
     * Logout action which clears session data and redirects to login again.
     */
    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
         
        $this->flashmessenger()->addMessage("You've been successfully logged out");
        return $this->redirect()->toRoute('login');
    }
    
}