<?php
namespace JumpUpUser\Controller;

    
use Zend\Mail\Transport\Sendmail;

use JumpUpUser\Util\ServicesUtil;

use Zend\Mail\Message;

use JumpUpUser\Util\Messages\IControllerMessages;
use JumpUpUser\Models\UserTable;
use Zend\Debug\Debug;
use JumpUpUser\Models\User;
use JumpUpUser\Filters\RegistrationFormFilter;
use JumpUpUser\Forms\RegistrationForm;
use Zend\Mvc\Controller\AbstractActionController;
use JumpUpUser\Forms;


class RegisterController extends AbstractActionController 
{
    private $controllerMessages;
    
 
    /**
     * This function defines the default action.    
     * 
     * Here, the registration form is instanciated with the RegistrationFormFilter.
     * The action reacts on the post method (so we only need one action for rendering and validation)
     * @return an array of the attributes ('attr_name' => 'attr_value') to be exported for the view.
     */    
    public function showformAction()
    {                
         // we grab the only existing ControllerMessages instance here from the service manager
        $this->controllerMessages = ServicesUtil::getControllerMessages($this->getServiceLocator());
        
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
                // set confirmation to false (user needs to confirm in an eMail)
                $user->setConfirmationState(false);
                // encrypt password and bind it manually
                $encryptedPw = $filter->encryptPassword($user->getPassword());
                $user->setPassword($encryptedPw);     
                // persist user           
                $userTable = ServicesUtil::getUserTable($this->getServiceLocator());
                $userTable->saveUser($user);
                // send eMail with confirmation link
                $this->sendConfirmationMail($user);                
                // export sucess message to the view
                return array(
                	'form' => null,
                    'message' => IControllerMessages::SUCCESS_REGISTER,
                );
            }
        }

        // the view can access the form via $this->form
        return array('form' => $form);
    } 
    
    /**
     * 
     * Send the confirmation mail which contains the confirmation link.
     * @param User $user
     */
    private function sendConfirmationMail(User $user) {
        $confirmationLink = "test";
        
        $mail = new Message();
        $mail->setFrom('info@jumup.me', 'JumpUp');
        $mail->addTo($user->getEmail());
        $mail->setSubject(IControllerMessages::CONFIRM_MAIL_SUBJECT);
        $mail->setBody($this->controllerMessages->generateConfirmationMailBody($user, $confirmationLink));
        
        
        $transport = new Sendmail();
        $transport->send($mail);
    }
    
    
}

?>