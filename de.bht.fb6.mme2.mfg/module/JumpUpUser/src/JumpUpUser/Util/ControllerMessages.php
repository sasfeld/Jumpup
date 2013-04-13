<?php
namespace JumpUpUser\Util;

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
interface ControllerMessages {
    /**
     * Message shown after a succesfull registration (including persistence).
     * @var String
     */
    const SUCCESS_REGISTER = "You were successfully registered! Please check your eMails to confirm the account."; 
}