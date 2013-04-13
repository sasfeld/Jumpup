<?php
namespace JumpUpUser\Controller;

    
use JumpUpUser\Util\ControllerMessages;
use JumpUpUser\Util\ServicesUtil;
use JumpUpUser\Models\UserTable;
use Zend\Debug\Debug;
use JumpUpUser\Models\User;
use JumpUpUser\Filters\RegistrationFormFilter;
use JumpUpUser\Forms\RegistrationForm;
use Zend\Mvc\Controller\AbstractActionController;
use JumpUpUser\Forms;


class RegisterController extends AbstractActionController 
{
    /**
     * This function defines the default action.    
     * 
     * Here, the registration form is instanciated with the RegistrationFormFilter.
     * The action reacts on the post method (so we only need one action for rendering and validation)
     * @return an array of the attributes ('attr_name' => 'attr_value') to be exported for the view.
     */    
    public function showformAction()
    {                
        $user = new User();
        $translator = $this->getServiceLocator()->get('translator');
        $form = new RegistrationForm('register', $translator);  
        $form->bind($user); // bind the property class user
        $filter = new RegistrationFormFilter($translator);
        $form->setInputFilter($filter);
        $form->setAttribute('action', 'register'); // the default action itself

        $request = $this->getRequest();
        
        if($request->isPost()) { // form filled?
            $form->setData($request->getPost()); // set data to be validated
            if($form->isValid()) {
                // encrypt password and bind it manually
                $encryptedPw = $filter->encryptPassword($user->getPassword());
                $user->setPassword($encryptedPw);     
                // persist user           
                $userTable = ServicesUtil::getUserTable($this->getServiceLocator());
                $userTable->saveUser($user);
                // export sucess message to the view
                return array(
                	'form' => null,
                    'message' => ControllerMessages::SUCCESS_REGISTER,
                );
            }
        }

        // the view can access the form via $this->form
        return array('form' => $form);
    } 
    
    
}

?>