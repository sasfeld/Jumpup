<?php
namespace JumpUpUser\Validators;

use JumpUpUser\Util\Exception_Util;

use Doctrine\ORM\EntityManager;

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
class UserExists extends AbstractDbValueValidator {
   
    /**
     * Key for the error message that the user already exits.   
     * @var doesn't matter but it's a string
     */
    const USER_ALDREADY_EXISTS = 'useralreadyexits';
      
    
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
        if(null === $this->entityManager)   {
            throw new Exception("No UserTable instance specified. You have to set the option userTable to the userTable instance so this method can access the DAO!");
        }
        $this->setValue($value); // insert "magic parameter"    
        
        $repoUser = $this->entityManager->getRepository("JumpUpUser\Models\User");
        $user = $repoUser->findOneBy(array('username' => $value));

        if(null !== $user) {
            $this->error(self::USER_ALDREADY_EXISTS); // track error message
            return false; // user already exits -> validation fails
        }
        
        return true; // user doesn't exist
    }    
    
    
}