<?php
namespace JumpUpUser\Controller;

use Zend\Filter\Encrypt;

use JumpUpUser\Filters\RegistrationFormFilter;

use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpUser\Models\LoginDumb;
use JumpUpUser\Util\ServicesUtil;
use Zend\Mvc\Controller\AbstractActionController;
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
            $loginDumb = new LoginDumb();
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($loginDumb);
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
            return $this->redirect()->toRoute('success');
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
     * Encrypt a password. This should be done after the validation.
     * @param String $password
     * @return String the encrypted password
     * @throws IllegalArgumentException if the password is not a string
     */
    public function encryptPassword($password) {       
        if(is_string($password)) {
           
            $filter = new Encrypt();
            $filter->setOptions( array (
                        'adapter' => 'BlockCipher', 
                        //'key' => self::ENCRYPTION_KEY,
                ));           
            $filter->setKey("imastring");
            $encryptedPw = $filter->filter($password);
            return $encryptedPw;
        }
     }
     

     
    
    /**
     * Authenticate action which redirects to login action.
     */
    public function authenticateAction() 
    {
        $form = $this->getForm();
        $redirect = 'login';
        
        $request = $this->getRequest();
        if($request->isPost()) { // authenticate user      
        
            $form->setData($request->getPost());
            
            if($form->isValid()) {                                
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost(LoginDumb::FIELD_USERNAME))
                                       ->setCredential($request->getPost(LoginDumb::FIELD_PASSWORD));
                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message) {
                    // save session-based message
                    $this->flashMessenger()->addMessage($message);
                }
                if($result->isValid()) { // successful authentication
                    $redirect = 'success';
                    // save username on the client
                    if(1 == $request->getPost(LoginDumb::FIELD_REMEMBER_ME)) {
                        $this->getSessionStorage()->setRememberMe(1);
                        // set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    // write in cookie
                    $this->getAuthService()->getStorage()->write($request->getPost(LoginDumb::FIELD_USERNAME));                    
                }
            }
        }
        // redirect to login action
        echo $request->getPost(LoginDumb::FIELD_PASSWORD);
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