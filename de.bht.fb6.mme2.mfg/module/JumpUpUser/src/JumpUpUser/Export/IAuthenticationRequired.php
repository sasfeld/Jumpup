<?php
namespace JumpUpUser\Export;

/**
 * 
* Each controller which shall be within an authentication area needs to implement this interfacen.
*
* @package    JumpUpUser\Export
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      30.04.2013
 */
interface IAuthenticationRequired {
    /**
     * Check whether the user is authenticated.
     * @return boolean true if the user may see the page.
     */
    function _checkAuthentication();
}