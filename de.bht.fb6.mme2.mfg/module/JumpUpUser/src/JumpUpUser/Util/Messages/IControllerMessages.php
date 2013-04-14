<?php
namespace JumpUpUser\Util\Messages;

use JumpUpUser\Models\User;

/**
 * 
* Interface which only contains messages for the view which are exported by the conrollers.
*
* @package    JumpUpUser\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
interface IControllerMessages {
    /**
     * Message shown after a succesfull registration (including persistence).
     * @var String
     */
    const SUCCESS_REGISTER = "You were successfully registered! Please check your eMails to confirm the account.";
    /**
     * Message shown as eMail subject after the registration (confirmation mail)
     * @var String
     */
    const CONFIRM_MAIL_SUBJECT = "jumup.me | Please confirm your registration";
    /**
     * Message shown after an unsucessfull confirmation.
     * @var String
     */
    const UNSUCCESS_CONFIRM = "The confirmation wasn't successfull. Maybe the user doesn't exist or the confirmation key is wrong. Please contact the support.";
    /**
     * Message shown after a sucessfull confirmation (the user recieves an eMail with the confirmation link in it).
     * @var String
     */
    const SUCCESS_CONFIRM = "You were successfully confirmed. You can now log in with using your credentials and have fun!";
    /**
     * Generate the confirmation mail for the given user.
     * @param User $user
     * @param String $confirmationLink the link to confirm the user
     */
    function generateConfirmationMailBody(User $user, $confirmationLink);
}