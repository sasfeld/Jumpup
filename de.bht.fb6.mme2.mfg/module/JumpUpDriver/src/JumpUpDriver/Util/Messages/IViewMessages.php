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
    const TRIP_DELETE = "Delete";
   	/*
     * ..:::::::::::::::::::::::::::::::::..
    */
    /* ..:: used in JumpUpDriver\view\vehicle\list.phtml ::../
     *
    */
    const VEHICLE_LIST_NO_VEHICLES = "You haven't configured any vehicle yet.";
    const VEHICLE_LIST_NO_PIC = "You havn't uploaded any vehicle pic yet.";
	const VEHICLE_LIST_ACTUAL_WHEEL = "Actual wheel";
	const VEHICLE_LIST_AIR_CONDITION = "Air condition";
	const VEHICLE_LIST_LEG_SPACE = "Leg space";
	const VEHICLE_LIST_SEATS = "Number of seats";
	const VEHICLE_LIST_AVG_SPEED = "Average speed (km/h)";
	const VEHICLE_LIST_WASTAGE = "Wastage (l/100km)";
	const VEHICLE_LIST_TYPE = "Type";
	const VEHICLE_LIST_BRAND = "Brand";
	const VEHICLE_LIST_CURRENT_PIC = "Current pic";
	const VEHICLE_LIST_LINK_ADD = "Add vehicle";
	const VEHICLE_LIST_LINK_EDIT = "Edit";
	const VEHICLE_LIST_LINK_REMOVE = "Remove";
	
	/* ..:: used in JumpUpDriver\view\vehicle\show.phtml ::../
	 *
	*/
	const SHOW_VEHICLES = "Vehicle information";
	const VEHICLE_LIST_NO_PIC_FOR_PASSENGER = "The driver hasn't uploaded any pic yet.";
	/*
	 * ..::::::::::::::::::::::::::::::::::::::::::::::::::..
	*/
	/* ..:: used in view\add-trip\step1.phtml ::../
	 *
	*/
	const ADD_TRIP_STEP1 = "Add trip";
	/* ..:::::::::::::::::::::::::::::::::::::::../
	 *
	*/
	/* ..:: used in view\vehicle\add.phtml ::../
	 *
	*/
	const VEHICLE_ADD = "Add vehicle information";
	/* ..:::::::::::::::::::::::::::::::::::::::../
	 *
	*/
	/* ..:: used in view\vehicle\edit.phtml ::../
	 *
	*/
	const VEHICLE_EDIT = "Edit vehicle information";
	/* ..:::::::::::::::::::::::::::::::::::::::../
	 *
	*/
	/* ..:: used in view\vehicle\list.phtml ::../
	 *
	*/
	const VEHICLE_LIST = "List vehicle information";
	/* ..:::::::::::::::::::::::::::::::::::::::../
	 *
	*/
}