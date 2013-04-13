<?php
namespace JumpUpUser\Util\Messages;


use JumpUpUser\Models\User;

use Zend\I18n\Translator\Translator;

use JumpUpUser\Util\Messages\IControllerMessages;
/**
 * 
* This util (service) class offers messages which the controller produces to inform the user.
*
*
* @package    JumpUpUser\Util\Messages
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
class ConcreteControllerMessages implements IControllerMessages {
    private $translator;
    
    /**
     * This should be done by the ServiceManager / a factory.
     * @param Translator $translator
     */
    public function __construct(Translator $translator) {
        if(null === $translator) {
            throw new IllegalArgumentException("The argument Translator mustn't be null");
        }
        $this->translator = $translator;
    }
    
    /*
     * 
     */
    public function generateConfirmationMailBody(User $user, $confirmationLink) {
        $message = ""; // initialize / pseudo declaration
        $message .= $this->translator->translate("Hello")." {$user->getPrename()} {$user->getLastname()}!"
                 . "\n\n"
                 . $this->translator->translate("We are glad to have you now aboard the greatest car pooling community.")
                 . "\n"
                 . $this->translator->translate("Before you can use your account, we have to check if your eMail is correct.")
                 . "\n"
                 . $this->translator->translate("Please click on the confirmation link below")
                 . ":\n\n"
                 . $confirmationLink
                 . "\n\n"
                 . $this->translator->translate("Have a great stay on jumpup.me!")
                 . "\n\n"
                 . $this->translator->translate("Greetings")
                 . ",\n"
                 . $this->translator->translate("Your jumup.me team");
        return $message;
        
    }
}