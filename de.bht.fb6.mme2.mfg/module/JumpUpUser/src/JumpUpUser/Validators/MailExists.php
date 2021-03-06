<?php
namespace JumpUpUser\Validators;

/**
 *
 * General validator for validators which need access to the DB via doctrine.
 *
 *
 * @package    JumpUpUser\Validators
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      15.05.2013
 */
class MailExists extends AbstractDbValueValidator {
  
  /**
  * Key for the error message that the mail already exits.
  * @var doesn't matter but it's a string
  */
  const MAIL_ALDREADY_EXISTS = 'mailalreadyexits';
  
  
  /**
   * Predefined messages. %value% is a magic parameter. It will be placed by the user's input.
   * @var array of elements in the form (error_message_key => error_message_template)
   */
  protected $messageTemplates = array(
      self::MAIL_ALDREADY_EXISTS => "The mail adress %value% already exists. Please try to reset your password.",
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
    $user = $repoUser->findOneBy(array('email' => $value));
  
    if(null !== $user) {
      $this->error(self::MAIL_ALDREADY_EXISTS); // track error message
      return false; // user already exits -> validation fails
    }
  
    return true; // user doesn't exist
  }
}