<?php
namespace JumpUpUser\Controller;

    
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
        $translator = $this->getServiceLocator()->get('translator');
        $form = new RegistrationForm('register', $translator);  
        $form->setInputFilter(new RegistrationFormFilter($translator));
        $form->setAttribute('action', 'register'); // the default action itself

        $request = $this->getRequest();
        
        if($request->isPost()) { // form filled?
            $form->setData($request->getPost()); // set data to be validated
            if($form->isValid()) {
                echo "the form is valid";
            }
        }

        // the view can access the form via $this->form
        return array('form' => $form);
    } 
}

?>