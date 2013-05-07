<?php
namespace JumpUpPassenger\Util\Routes;

/**
 * 
* The interface stores the keys of the routes' configurations in the module.config.php
*
*
* @package    JumpUpPassenger\Util\Routes
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      07.05.2013
 */
interface IRouteStore {
    /**
     * 
     * Route to the lookuperror action within the ViewTripsController.
     * @var String
     */
    const LOOKUP_ERROR = "lookuperror";  
    
}