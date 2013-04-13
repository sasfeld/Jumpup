<?php
namespace JumpUpUser\Filters;
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
     * 
     * Construct the RegistrationFormFilter.
     * @param Zend\I18n\Translator\Translator $translator which translates the messages
     */
    public function __construct(Translator $translator) {        
        /*
         * usage of validators, see
         * http://framework.zend.com/manual/2.1/en/modules/zend.validator.set.html#stringlength
         */
        // these messages are shown when the validation fails
        $emptyUsernameMsg = $translator->translate("Please fill in an username.");
        $expectedUsernameMessage = $translator->translate("The username may only contain alphanumeric characters. It must begin with letter.");
        $minimum = self::USERNAME_MIN_CHARS;
        $maximum = self::USERNAME_MAX_CHARS;
        $expectedUsernameLengthMsg = $translator->translate("The username's length must be a mimimum of {$minimum} and a maximum of {$maximum} characters");
        $emptyPasswordMsg = $translator->translate("Please fill in a password.");
        $emptyRepeatPasswordMsg = $translator->translate("Please fill in the repeat password.");
        $expectedPasswordMessage = $translator->translate("The password must contain at least on special character.");
        $minimum = self::PASSWORD_MIN_CHARS;
        $maximum = self::PASSWORD_MAX_CHARS;
        $expectedPasswordLengthMsg = $translator->translate("The password's length must be a mimimum of {$minimum} and a maximum of {$maximum} characters");
        $emptyeMailMsg = $translator->translate("Please fill in an eMail adress.");
        $invalidMxMsg = $translator->translate("The DNS configuration for the given hostname doesn't offer an MX record.");
        $invalideMailMsg = $translator->translate("The eMail adress must be in the form xy@hostname.tld .");
        $passwordsNotSameMsg = $translator->translate("The boths passwords aren't identical.");
        
        // wow, a lot of code. The framework will call all this stuff and instanciate the validators and so on

        // username
        $this->add(array(
            'name' => RegistrationForm::FIELD_USERNAME,
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            NotEmpty::IS_EMPTY => $emptyUsernameMsg )
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
          
          // password
          $this->add(array(
            'name' => RegistrationForm::FIELD_PASSWORD,
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags')),
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
                       // safe pw pattern: must at least contain one of those: (){}^?��"%&/\�`+*~#';.:_-+
                       'pattern' => '/^.*[?~^�$%_.;:\\\&�!-+\/\'\"�`(){}]+.*$/',  
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
                        )
                     )                     
                   ),            
                 )
              )
          );    
    }
}