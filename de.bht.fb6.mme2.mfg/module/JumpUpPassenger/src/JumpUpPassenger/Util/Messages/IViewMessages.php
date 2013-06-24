<?php
namespace JumpUpPassenger\Util\Messages;



/**
 * 
* Interface which only contains messages for the view which are called by view files (*.phtml).
*
* @package    JumpUpPassenger\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      24.06.2013
 */
interface IViewMessages {
    
    
    /* ..:: used in booking\.*.phtml ::../
     *
    */
   const VIEW_BOOKINGS_TITLE = "View bookings";
   const BOOK_TRIP_TITLE = "Book trip";
   const BOOKING_ERROR = "Booking system error";
   	/*
     * ..:::::::::::::::::::::::::::::::::..
    /* ..:: used in application\Util\View\Helper\RenderBookings.php ::../
     *
    */
	const VEHICLE = "Vehicle";
   	/*
     * ..:::::::::::::::::::::::::::::::::..
    */
    /* ..:: used in view-trips\.*.phtml ::../
     *
    */
   const LOOK_UP_TITLE = "Look for trips";
   /**
    * @deprecated because showTrips is not used
    */
   const SHOW_TRIPS_TITLE = "Show trips";
   	/*
     * ..:::::::::::::::::::::::::::::::::..
    */
  
}