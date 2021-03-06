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
     * Placeholder for a value which represents a minimum.
     * @var String
     */
    const MIN = "%min%";
    /**
     * Placeholder for a value which represents a maximum.
     * @var String
     */
    const MAX = "%max%";
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
     * Message shown when the user tries to login but isn't confirmed yet.
     * @var String
     */
    const NOT_CONFIRMED_YET = "You aren't confirmed yet. Please check your eMails for the confirmation link before you try to login.";
    /**
     * Message is shown when the user was successfully logged out.
     * @var String
     */
    const SUCCESS_LOGGING_OUT = "You've been successfully logged out.";
    /*
     * ..:: used in JumpUpUser\Filters\RegistrationFormFilter. ::..
     */
    const SUCCESS_CONFIRM = "You were successfully confirmed. You can now log in with using your credentials and have fun!";
    const REGISTER_EMPTY_USERNAME = "Please fill in an username.";
    const REGISTER_EXPECTED_USERNAME = "The username may only contain alphanumeric characters. It must begin with letter.";
    const REGISTER_SIZE_USERNAME = "The username's length must be a mimimum of %min% and a maximum of %max% characters";
    const REGISTER_EMPTY_PASSWORD = "Please fill in a password.";
    const REGISTER_EMPTY_REPEAT_PASSWORD = "Please fill in the repeat password.";
    const REGISTER_EXPECTED_PASSWORD = "The password must contain at least on special character.";
    const REGISTER_SIZE_PASSWORD = "The password's length must be a mimimum of %min% and a maximum of %max% characters";
    const REGISTER_EMPTY_EMAIL = "Please fill in an eMail adress.";
    const REGISTER_INVALID_MX = "The DNS configuration for the given hostname doesn't offer an MX record.";
    const REGISTER_INVALID_MAIL = "The eMail adress must be in the form xy@hostname.tld .";
    const REGISTER_PASSWORDS_NOT_EQUAL = "The boths passwords aren't identical.";
    const REGISTER_USER_ALREADY_EXISTS = "This user already exists. Please choose another username.";
    const REGISTER_EMPTY_PRENAME = "Please fill in your prename";
    const REGISTER_EMPTY_LASTNAME = "Please fill in your lastname";
    const REGISTER_EXPECTED_PRENAME = "The prename may only contain letters.";
    const REGISTER_EXPECTED_LASTNAME = "The lastname may only contain letters.";
    const REGISTER_EMAIl_ALREADY_EXISTS = "This eMail address already exists. Use the forgot password function to reboot.";
    /*
     * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */
    /*
     * ..:: used in JumpUpUser\Controllers\ProfileController. ::..
     */
    const CHANGE_PROFILE_SUCCESS = "Your profile was successfully changed.";
    const NOT_COMPLETED_PROFILE_YET = "You haven't configured your profile yet. Please be so kind to offer your information.";
	const ERROR_DELETING_PROFILE_PIC = "Couldn't delete the old profile picture. Please contact the support.";
	const NO_PROFILE_FOUND = "There was no profile found. Maybe the user doesn't exist.";
	const PROFILE_IMAGE_TYPES = "The file type must be one of types png, jpeg or gif.";
	/*
     * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */
    const FATAL_ERROR_NOT_AUTHENTIFICATED = "Please login to continue.";
    
    /**
     * Generate the confirmation mail for the given user.
     * @param User $user
     * @param String $confirmationLink the link to confirm the user
     */
    function generateConfirmationMailBody(User $user, $confirmationLink);
}