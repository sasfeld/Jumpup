<?php
namespace JumpUpPassenger\Util\Messages;



/**
 * 
* Interface which only contains messages for the view which are exported by the conrollers.
*
* @package    JumpUpPassenger\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      07.05.2013
 */
interface IControllerMessages {
    
     /*
     * ..:: used in JumpUpPassenger\Controller\ViewTripsController. ::..
     */
    const ERROR_LOOKUP_FORM = "Please fill in all required fields."; 
    
    /*
     * ..:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */     
     /*
      * 
     * ..:: used in JumpUpPassenger\Controller\BookingController. ::..
     */
    const BOOKING_SUCCESS = "Congratulations, you have booked the trip successfully! Please be patient and wait for the driver to handle the booking. If the driver doesn't accept your recommended price, he will make another offer. You will be informed via eMail.";
    const ERROR_BOOKING_OVERBOOKING = "The trip you want to book cannot be booked anymore. Please try to find another trip.";    
    const ERROR_BOOKING_REQUEST = "Internal error: illegal request. If the error occurs again, please inform the support.";
    const ERROR_BOOKING_NOTRIP = "Internal error: No matching trip found. If the error occurs again, please inform the support.";    
    const ERROR_BOOKING_OVERVIEW = "Interal error: None booking could get fetched from the database. If the error occurs again, please inform the support.";
    const ERROR_NO_BOOKING = "Internal error: No booking instance.";
	const BOOKING_ERROR_OWN_TRIP = "Your cannot book your own trip. Please let other passengers the ability to book your ride.";
    /*
     * ..:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::..
     */     
    
    /* ..:: used in view-bookings.phtml ::../
     * 
     */
    const BOOKING_STATE_PASSENGER_RECOMM = "Your recommendation was sent to the driver. He needs to accept it before you can really do the trip.";
    const BOOKING_NO_BOOKINGS = "No bookings done";
    /*
     * ..:::::::::::::::::::::::::::::::::..
     */  
    
    const BOOKING_STATE_DRIVERS_RECOMM = "The driver recommended the following price. You can now accept or deny the booking definitly or make another offer. ";
   
}