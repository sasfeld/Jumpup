<?php
namespace JumpUpDriver\Util\Messages;



/**
 * 
* Interface which only contains messages for the view which are called by view files (*.phtml).
*
* @package    JumpUpDriver\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      10.06.2013
 */
interface IViewMessages {
    
    
    /* ..:: used in view-bookings.phtml ::../
     *
    */
    const BOOKING_STATE = "State";
    const BOOKING_DATE = "Date";
    const BOOKING_DEPART_TIME = "Departure time";
    const BOOKING_DENY = "Deny";
    const BOOKING_APPLY = "Accept";
    const BOOKING_PASSENGER = "Passenger";
   	const BOOKING_NO_TRIPS = "You haven't configured any trips yet.";
    /*
     * ..:::::::::::::::::::::::::::::::::..
    */

   
}