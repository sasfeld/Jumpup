<?php
namespace JumpUpUser\Util\Routes;

/**
 * 
* The interface stores the keys of the routes' configurations in the module.config.php
*
*
* @package    JumpUpUser\Util\Routes
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      19.04.2013
 */
interface IRouteStore {
    /**
     * 
     * Route to the login action within the AuthController.
     * @var String
     */
    const LOGIN = "login";
    /**
     * 
     * Route to the success action within the AuthController.
     * @var String
     */
    const LOGIN_SUCCESS = "success";
    /**
     * 
     * Route to the logout action within the AuthController.
     * @var String
     */
    const LOGOUT = "logout";
    /**
     * Route to the register controller.
     * @var String
     */
    const REGISTER = "jump-up-user";
    
}