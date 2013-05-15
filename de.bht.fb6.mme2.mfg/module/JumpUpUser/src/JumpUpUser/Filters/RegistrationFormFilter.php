<?php
namespace JumpUpUser\Filters;
use JumpUpUser\Validators\MailExists;

use JumpUpUser\Util\Exception_Util;

use Doctrine\ORM\EntityManager;

use JumpUpUser\Util\Messages\IControllerMessages;

use Zend\Validator\AbstractValidator;

use JumpUpUser\Validators\UserExists;

use JumpUpUser\Models\UserTable;

use Zend\Filter\Decrypt;

use Zend\Filter\Encrypt;

use Application\src\Application\Util\String_Util;

use Zend\Validator\Identical;

use Zend\Validator\EmailAddress;

use Zend\Validator\NotEmpty;

use Zend\I18n\Translator\Translator;

use Zend\Validator\StringLength;
use Zend\Validator\Regex;
use JumpUpUser\Forms\RegistrationForm;
use Zend\InputFilter\InputFilter;

/**
 * 
* This class defines an input filter / validation for our RegistrationForm.
*
* It implements the Zend InputFilter.
*
* @package    
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
class RegistrationFormFilter extends InputFilter {
    /**
     * Define the minimum number of chars here
     * @var int
     */
    const USERNAME_MIN_CHARS = 6;
    /**
     * Define the maximum number of chars here
     * @var int
     */
    const USERNAME_MAX_CHARS = 22;
    /**
     * Define the minimum number of chars in the password here.
     * @var int
     */
    const PASSWORD_MIN_CHARS = 8;
    /**
     * Define the maximum number of chars in the password field here.
     * @var int
     */
    const PASSWORD_MAX_CHARS = 30;
    /**
     * The encryption key used by the encryption strategy.
     * @var String
     */
    const ENCRYPTION_KEY = "imastring";

    /**
     * 
     * Construct the RegistrationFormFilter.
     * @param Zend\I18n\Translator\Translator $translator which translates the messages
     * @param EntityManager the (doctrine) entityManager
     */
    public function __construct(Translator $translator, EntityManager $entityManager) {
        if(null === $translator)  {
            Exception_Util::throwInvalidArgument('$translator', 'Translator', 'null');
        }        
        elseif(null === $entityManager) {
            Exception_Util::throwInvalidArgument('$entityManager', 'EntityManager', 'null');
        }
        /*
         * usage of validators, see
         * http://framework.zend.com/manual/2.1/en/modules/zend.validator.set.html#stringlength
         */
        // these messages are shown when the validation fails
        $emptyUsernameMsg = $translator->translate(IControllerMessages::REGISTER_EMPTY_USERNAME);
        $expectedUsernameMessage = $translator->translate(IControllerMessages::REGISTER_EXPECTED_USERNAME);
        $minimum = self::USERNAME_MIN_CHARS;
        $maximum = self::USERNAME_MAX_CHARS;
        $fillInMsg = IControllerMessages::REGISTER_SIZE_USERNAME;
        $fillInMsg = str_replace(IControllerMessages::MIN, $minimum, $fillInMsg);
        $fillInMsg = str_replace(IControllerMessages::MAX, $maximum, $fillInMsg);
        $expectedUsernameLengthMsg = $translator->translate($fillInMsg);
        $emptyPasswordMsg = $translator->translate(IControllerMessages::REGISTER_EMPTY_PASSWORD);
        $emptyRepeatPasswordMsg = $translator->translate(IControllerMessages::REGISTER_EMPTY_REPEAT_PASSWORD);
        $expectedPasswordMessage = $translator->translate(IControllerMessages::REGISTER_EXPECTED_PASSWORD);
        $minimum = self::PASSWORD_MIN_CHARS;
        $maximum = self::PASSWORD_MAX_CHARS;
        $fillInMsg = IControllerMessages::REGISTER_SIZE_PASSWORD;
        $fillInMsg = str_replace(IControllerMessages::MIN, $minimum, $fillInMsg);
        $fillInMsg = str_replace(IControllerMessages::MAX, $maximum, $fillInMsg);
        $expectedPasswordLengthMsg = $translator->translate($fillInMsg);
        $emptyeMailMsg = $translator->translate(IControllerMessages::REGISTER_EMPTY_EMAIL);
        $invalidMxMsg = $translator->translate(IControllerMessages::REGISTER_INVALID_MX);
        $invalideMailMsg = $translator->translate(IControllerMessages::REGISTER_INVALID_MAIL);
        $passwordsNotSameMsg = $translator->translate(IControllerMessages::REGISTER_PASSWORDS_NOT_EQUAL);
        $userAlreadyExistsMsg = $translator->translate(IControllerMessages::REGISTER_USER_ALREADY_EXISTS);
        $emptyPrenameMsg = $translator->translate(IControllerMessages::REGISTER_EMPTY_PRENAME);
        $emptyLastnameMsg = $translator->translate(IControllerMessages::REGISTER_EMPTY_LASTNAME);
        $expectedPrenameMessage = $translator->translate(IControllerMessages::REGISTER_EXPECTED_PRENAME);
        $expectedLastnameMessage = $translator->translate(IControllerMessages::REGISTER_EMPTY_LASTNAME);
        $eMailAlreadyExistsMsg = $translator->translate(IControllerMessages::REGISTER_EMAIl_ALREADY_EXISTS);
        
        // wow, a lot of code. The framework will call all this stuff and instanciate the validators and so on

        // username
        $this->add(array(
            'name' => RegistrationForm::FIELD_USERNAME,
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyUsernameMsg )
                    ),
                ),
                array( // check if user already exists
                    'name' => 'JumpUpUser\Validators\UserExists', 
                    'options' => array (
                        'entityManager' => $entityManager,
                        'messages' => array (
                            UserExists::USER_ALDREADY_EXISTS => $userAlreadyExistsMsg,                            
                        ),
                    ),
                ),
                array(
                	 'name' => 'string_length',
                	 'options' => array (
                        'min' => self::USERNAME_MIN_CHARS,
                        'max' => self::USERNAME_MAX_CHARS,
                        'messages' => array (
                            StringLength::TOO_LONG => $expectedUsernameLengthMsg,
                            StringLength::TOO_SHORT => $expectedUsernameLengthMsg
                        )
                     )                     
                   ),
                array(
                	 'name' => 'Regex',
                	 'options' => array (
                       'pattern' => '/^[A-Za-z]+[A-Za-z0-9]+/',  
                       'messages' => array (
                            Regex::NOT_MATCH => $expectedUsernameMessage,
                        ),                    
                     )                     
                   )
                 )
              )
          );
          
          // prename         
          $this->add(array(
            'name' => RegistrationForm::FIELD_PRENAME,
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyPrenameMsg )
                    ),
                ),
                array(
                	 'name' => 'Regex',
                	 'options' => array (
                       'pattern' => '/^[A-Za-z]+/',  
                       'messages' => array (
                            Regex::NOT_MATCH => $expectedPrenameMessage,
                        ),                    
                     )                     
                   ),
               )
             )
           );
          
          // lastname
           $this->add(array(
            'name' => RegistrationForm::FIELD_LASTNAME,
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags'),
                ), 
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyLastnameMsg )
                    ),
                ),
                array(
                	 'name' => 'Regex',
                	 'options' => array (
                       'pattern' => '/^[A-Za-z]+/',  
                       'messages' => array (
                            Regex::NOT_MATCH => $expectedLastnameMessage,
                        ),                    
                     )                     
                   ),
               )
             )
           );
          
          // password
          $this->add(array(
            'name' => RegistrationForm::FIELD_PASSWORD,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
               ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyPasswordMsg )
                    ),
                ),
                array(
                	 'name' => 'string_length',
                	 'options' => array (
                        'min' => self::PASSWORD_MIN_CHARS,
                        'max' => self::PASSWORD_MAX_CHARS,
                        'messages' => array (
                            StringLength::TOO_LONG => $expectedPasswordLengthMsg,
                            StringLength::TOO_SHORT => $expectedPasswordLengthMsg
                        )
                     )                     
                   ),
                array(
                	 'name' => 'Regex',
                	 'options' => array (
                       // safe pw pattern: must at least contain one of those: (){}^?°§"%&/\´`+*~#';.:_-+
                       'pattern' => '/^.*[?~^°$%_.;:\\\&§!-+\/\'\"´`(){}]+.*$/',  
                       'messages' => array (
                            Regex::NOT_MATCH => $expectedPasswordMessage,
                        ),                    
                     )                     
                   )
                 )
              )
          );    

          // repeat password 
          $this->add(array(
            'name' => RegistrationForm::FIELD_REPEAT_PASSWORD,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyRepeatPasswordMsg )
                    ),
                ),
                array(
                    // check if both passwords are identical
                	 'name' => 'Identical',
                	 'options' => array (
                        'token' => RegistrationForm::FIELD_PASSWORD,
                        'messages' => array (
                            Identical::NOT_SAME => $passwordsNotSameMsg)
                     )                     
                   ),             
                 )
              )
          ); 
          // eMail
          $this->add(array(
            'name' => RegistrationForm::FIELD_EMAIL,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyeMailMsg )
                    ),
                ),
                array( // check if user already exists
                    'name' => 'JumpUpUser\Validators\MailExists',
                    'options' => array (
                        'entityManager' => $entityManager,
                        'messages' => array (
                            MailExists::MAIL_ALDREADY_EXISTS => $eMailAlreadyExistsMsg,
                        ),
                    ),
                ),
                array(
                	 'name' => 'EMailAddress',
                	 'options' => array (     
                        'deep'         => true, // warning: checks mail for all dns records -> may be a performance issue
                        'useMxCheck'   => true,                   
                        'messages' => array (
                            // @TODO add personal messages here
                            EmailAddress::INVALID_MX_RECORD => $invalidMxMsg,
                            EmailAddress::INVALID_LOCAL_PART => $invalideMailMsg,
                            EmailAddress::INVALID => $invalideMailMsg,
                            EmailAddress::INVALID_HOSTNAME => $invalideMailMsg,
                            EmailAddress::INVALID_SEGMENT => $invalideMailMsg,
                            EmailAddress::INVALID_FORMAT => $invalideMailMsg,
                            EmailAddress::QUOTED_STRING => $invalideMailMsg,
                            EmailAddress::DOT_ATOM => $invalideMailMsg,
                            EmailAddress::LENGTH_EXCEEDED => $invalideMailMsg,                           
                        )
                     )                     
                   ),            
                 )
              )
          );    
    }
    

    
}