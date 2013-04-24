<?php
namespace JumpUpUser\Controller;

    
use Zend\Stdlib\Hydrator\ClassMethods;

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
    private $em;
    private $translator;
    
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
        $form = new RegistrationForm('register', $this->getTranslatorService(), $this->em);
        $form->setHydrator(new ClassMethods());  // data binding (hydrator strategy) -> setters and getters shall be called
        $form->bind($user); // bind the property class user   
        $form->setAttribute('action', 'register'); // the default action itself

        $request = $this->getRequest();
        
        if($request->isPost()) { // form filled?
            $form->setData($request->getPost()); // set data to be validated
            if($form->isValid()) {
                // set confirmation key (user needs to confirm it on the eMail)                
                $user->setConfirmation_key(time()); // we use the UNIX timestamp
                // encrypt password and bind it manually
                $encryptedPw = $form->encryptPassword($user->getPassword());
                $user->setPassword($encryptedPw);     
                // persist user                       
                $this->em->persist($user);
                $this->em->flush(); 
                //$userTable->saveUser($user);
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
            $queryConfirmKey = (int) $queryConfirmKey;
            $queryConfirmUser = (string) $queryConfirmUser;           
            try {                
                //$user = $userTable->getUser($queryConfirmUser);
                // fetch user from entity manager
                $repoUser = $this->em->getRepository("JumpUpUser\Models\User");
                $user = $repoUser->findOneBy(array('username' => $queryConfirmUser));
                if($user->getConfirmation_key() === $queryConfirmKey) { // compare input key with the randomly generated in the database
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
       $user->setConfirmation_key(0); // 0 indicates, that the user is confirmed   
       $this->em->persist($user);
       $this->em->flush();   
    }
    
    /**
     * 
     * Send the confirmation mail which contains the confirmation link.
     * @param User $user
     */
    private function sendConfirmationMail(User $user) {    
        
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
            . "/confirm?key={$user->getConfirmation_key()}&u={$user->getUsername()}";
        
        $mail = new Message();
        $mail->setFrom('info@jumup.me', 'JumpUp');
        $mail->addTo($user->getEmail());
        $mail->setSubject($this->getTranslatorService()->translate(IControllerMessages::CONFIRM_MAIL_SUBJECT));
        $mail->setBody($this->controllerMessages->generateConfirmationMailBody($user, $confirmationLink));
        
        
        $transport = new Sendmail();
        $transport->send($mail);
    }
    
    
}

?>