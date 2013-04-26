<?php
namespace JumpUpDriver\Util\Messages;



/**
 * 
* Interface which only contains messages for the view which are exported by the conrollers.
*
* @package    JumpUpDriver\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
interface IControllerMessages {
    
     /*
     * ..:: used in JumpUpDriver\Controller\AddTrip. ::..
     */
    const SUCCESS_ADD_TRIP = "Your trip was successfully added. You will find in your overview page.";
    const FATAL_ERROR_NOT_AUTHENTIFICATED = "Something went wrong. You are currently not logged in. Please login again, be sure to activate cookies and try it again.";
    
    /*
     * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */  
    

   
}