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
     * Generate the confirmation mail for the given user.
     * @param User $user
     * @param String $confirmationLink the link to confirm the user
     */
    function generateConfirmationMailBody(User $user, $confirmationLink);
}