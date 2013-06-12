<?php
namespace JumpUpDriver\Util\View;

/**
 *
 * This interface stores all the style names referenced in the styles.css in a central interface.
 *
 *
 * @package	   JumpUpDriver\Util\View	
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      11.06.2013
 */
interface ICssStyles {
	/* ..:: general ::../
	 *
	*/
	const NO_FLOAT = "nofloat";
	/*
	 * ..:::::::::::::..
	*/
	
	/* ..:: used in view-bookings.phtml ::../
	 *
	*/
	const TRIP = "trip";
	const BOOKING = "booking";
	const BOOKINGHEADLINE = "bookingheadline";
	const BOOKINGBASICFORM = "bookingbasicform";	
	const BOOKING_LIST = "bookinglist"; 
	/*
	 * ..:::::::::::::::::::::::::::::::::..
	*/
}