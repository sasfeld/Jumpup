<?php
namespace JumpUpDriver\Util\Routes;

/**
 * 
* The interface stores the keys of the routes' configurations in the module.config.php
*
*
* @package    JumpUpDriver\Util\Routes
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      19.04.2013
 */
interface IRouteStore {
    /**
     * 
     * Route to the AddTripController
     * @var String
     */
    const ADD_TRIP = "addtrip";
    
    /**
     * Route to the error action in the AddTripController 
     */
    const ADD_TRIP_ERROR = "addtrip_error";
    
    /**
     * Route to the success action after a successfull adding.
     */
    const ADD_TRIP_SUCCESS = "addtrip_success";
    /**
     * Route to the add vehicle action
     */
    const ADD_VEHICLE = "addvehicle";
    /**
     * Route to the list vehicles route.
     */    
    const LIST_VEHICLES = "listvehicles";
    /**
     * Route to the remove vehicle.
     */    
    const REMOVE_VEHICLE = "removevehicle";
    /**
     * Route to the edit vehicle.
     */    
    const EDIT_VEHICLE = "editvehicle";
    /**
     * Route to the action viewBookings within the BookingController.
     * @var String
     */
    const BOOK_DRIVER_OVERVIEW = "listdriverbookings";
    /**
     * Route to the action doRecommendation within the BookingController.
     * @var String
     */
    const BOOK_DO_RECOMMENDATION = "driverrecommendation";
    const BOOK_ERROR = "bookerror";
    const BOOK_DENY = "denybooking";
    const BOOK_APPLY = "applybooking";
    const DELETE_TRIP = "deletetrip";
    
}