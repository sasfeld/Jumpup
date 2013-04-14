<?php
namespace JumpUpUser\Validators;

use JumpUpUser\Models\UserTable;

use Zend\I18n\Translator\Translator;

use Zend\Validator\AbstractValidator;

/**
 * 
* This validator only checks if a user already exists.
*
*
* @package    
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      14.04.2013
 */
class UserExists extends AbstractValidator {
    /**
     * 
     * 
     * @var Translator
     */
    private $translator;
    /**
     * 
     * @var UserTable
     */
    private $userTable;
    /**
     * Key for the error message that the user already exits.   
     * @var doesn't matter but it's a string
     */
    const USER_ALDREADY_EXISTS = 'useralreadyexits';
    
    
    /**
     * This method is called when you set an option.
     * You have to set the userTable option before using the validator!
     * @param UserTable $userTable
     */
    public function setUserTable(UserTable $userTable) {
        $this->userTable = $userTable;
    }
    
    /**
     * Predefined messages. %value% is a magic parameter. It will be placed by the user's input.
     * @var array of elements in the form (error_message_key => error_message_template)
     */
    protected $messageTemplates = array(
        self::USER_ALDREADY_EXISTS => "The user %value% already exists. Please choose another username",
    );
    
    /**
     * (non-PHPdoc)
     * @see Zend\Validator.ValidatorInterface::isValid()
     */
    public function isValid($value) {    
        if(null === $this->userTable)   {
            throw new Exception("No UserTable instance specified. You have to set the option userTable to the userTable instance so this method can access the DAO!");
        }
        $this->setValue($value); // insert "magic parameter"    

        if($this->userTable->userExits($value)) {
            $this->error(self::USER_ALDREADY_EXISTS); // track error message
            return false; // user already exits -> validation fails
        }
        
        return true; // user doesn't exist
    }    
    
    
}