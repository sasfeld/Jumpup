<?php
namespace JumpUpUser\Controller;

    
use JumpUpUser\Util\Routes\IRouteStore;

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

use Zend\Db\TableGateway\Exception\RuntimeException;


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
        $userTable = ServicesUtil::getUserTable($this->getServiceLocator());
         // we grab the only existing ControllerMessages instance here from the service manager
        $this->controllerMessages = ServicesUtil::getControllerMessages($this->getServiceLocator());
        
        $user = new User();
        $translator = $this->getServiceLocator()->get('translator');
        $form = new RegistrationForm('register', $translator);  
        $form->bind($user); // bind the property class user
        $filter = new RegistrationFormFilter($translator, $userTable);
        $form->setInputFilter($filter);
        $form->setAttribute('action', 'register'); // the default action itself

        $request = $this->getRequest();
        
        if($request->isPost()) { // form filled?
            $form->setData($request->getPost()); // set data to be validated
            if($form->isValid()) {
                // set confirmation key (user needs to confirm it on the eMail)                
                $user->setConfirmationKey(time()); // we use the UNIX timestamp
                // encrypt password and bind it manually
                $encryptedPw = $filter->encryptPassword($user->getPassword());
                $user->setPassword($encryptedPw);     
                // persist user                         
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
     * This function defines the confirm action. 
     * The user recieves an eMail with the confirmation link. We except the get parameter to be the confirmation key.
     * Enter description here ...
     */
    public function confirmAction() {    
        $message = ""; // pseudo declaration of the string message to be exported to the frontend    
        $queryConfirmKey = $this->getRequest()->getQuery()->key;
        $queryConfirmUser = $this->getRequest()->getQuery()->u;
        if(null !== $queryConfirmKey && null !== $queryConfirmUser) {
            $queryConfirmKey = (string) $queryConfirmKey;
            $queryConfirmUser = (string) $queryConfirmUser;
            // get the DAO object
            $userTable = ServicesUtil::getUserTable($this->getServiceLocator());
            try {
                $user = $userTable->getUser($queryConfirmUser);
                if($user->getConfirmationKey() === $queryConfirmKey) { // compare input key with the randomly generated in the database
                    $this->confirmUser($user); // success
                    $message = IControllerMessages::SUCCESS_CONFIRM;
                    // redirect to login action  and add message in session realm
                    $this->flashMessenger()->addMessage($message); 
                    $this->redirect()->toRoute(IRouteStore::LOGIN);
                }
                else { // keys in db and by user input aren't equal
                    $message = IControllerMessages::UNSUCCESS_CONFIRM;
                }
            }
            catch (RuntimeException $e) { // user doesn't exist in database
                $message = IControllerMessages::UNSUCCESS_CONFIRM;
            }
        }
        else { // illegal request
          $message = IControllerMessages::UNSUCCESS_CONFIRM;  
        }
        
        return array('message' => $message);
    }
    
    /**
     * Confirm a user. Set the confirmation flag in the user instance and persist the user.
     * @param User $user
     */
    private function confirmUser(User $user) {
        $userTable = ServicesUtil::getUserTable($this->getServiceLocator());
        $user->setConfirmationKey(0); // 0 indicates, that the user is confirmed
        $userTable->saveUser($user); // update the user entity        
    }
    
    /**
     * 
     * Send the confirmation mail which contains the confirmation link.
     * @param User $user
     */
    private function sendConfirmationMail(User $user) {
        $translator = $this->getServiceLocator()->get('translator');
        
        /*
         * The confirmation link needs to refernce to the confirmAction().
         * That means for our default configuration, that it's relative to the
         * Register/ controller.
         * 
         * The confirm action needs to HTTP get parameters:
         * -key (the confirmation key to confirm the user)
         * -u (the username)
         */
        $confirmationLink = $this->getRequest()->getUriString() 
            . "/confirm?key={$user->getConfirmationKey()}&u={$user->getUsername()}";
        
        $mail = new Message();
        $mail->setFrom('info@jumup.me', 'JumpUp');
        $mail->addTo($user->getEmail());
        $mail->setSubject($translator->translate(IControllerMessages::CONFIRM_MAIL_SUBJECT));
        $mail->setBody($this->controllerMessages->generateConfirmationMailBody($user, $confirmationLink));
        
        
        $transport = new Sendmail();
        $transport->send($mail);
    }
    
    
}

?>