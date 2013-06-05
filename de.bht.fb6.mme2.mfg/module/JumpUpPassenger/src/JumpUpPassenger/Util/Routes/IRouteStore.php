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
    /**
     * Route to the showtrip action which shows the result of the lookuptrips action.
     * @var String
     */
    const SHOW_TRIPS = "showtrips";
    /**
     * Route to the lookuptrips action which exports a form for the user so he can find trips.
     * @var String
     */
    const LOOKUP_TRIPS = "lookuptrips";
    /**
     * Route to the book trip action where the user can book a selected trip.
     * @var String
     */
    const BOOK_TRIP = "booktrip";
    /**
     * Route tp the error action within the BookingController. It is shown when any error occurs.
     * @var String
     */
    const BOOK_ERROR = "bookerror";
    
}