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
    const INFO_NO_VEHICLES = "You haven't configured any vehicles yet. Please create a vehicle so the system can offer price recommendations.";
    const ERROR_DELETE_TRIP_ACTIVE_BOOKINGS = "There are active bookings within the trip. You can't delete the trip. Please try to cancel the bookings before.";
    const SUCCESS_DELETE_TRIP = "The trip was successfully deleted!";
    /*
     * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */  
    
    /*
     * ..:: used in JumpUpDriver\Controller\VehicleController. ::..
     */
    const SUCCESS_ADD_VEHICLE = "Your vehicle was successfully added.";
    const SUCCESS_EDIT_VEHICLE = "Your vehicle was successfully edited.";
    const SUCCESS_DELETE_VEHICLE = "Your vehicle was successfully removed.";
    const DELETE_VEHICLE_NO_ID = "Something went wrong. The way you tried to use this function doesn't work.";

    /*
     * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */  
    /*
     * ..:: used in JumpUpDriver\Controller\BookingController. ::..
     */
    const ERROR_BOOKING_REQUEST = "Internal error: illegal request. If the error occurs again, please inform the support.";
    const ERROR_NO_BOOKING = "Internal error: No booking instance.";
    /*
     * ..::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */  
    
    /* ..:: used in view-bookings.phtml ::../
     *
    */
    const BOOKING_STATE_PASSENGER_RECOMM = "The passenger recommended the following price. You can now apply, deny or make another decision.";
    const BOOKING_NO_BOOKINGS = "No bookings done";
    /*
     * ..:::::::::::::::::::::::::::::::::..
    */

   
}