<?php

namespace JumpUpPassenger\Util\Messages;

use Zend\I18n\Translator\Translator;
/**
 * 
* This (static) class offers messages for the json handler in the frontend.
*
* @package    JumpUpPassenger\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      24.06.2013
 */
class JsonMessages {
	const LOOK_UP_LOCATION_DISTANCE = "Location distance (km)";
	const LOOK_UP_DESTINATION_DISTANCE = "Destination distance (km)";
	const LOOK_UP_DRIVER = "Driver";
	const LOOK_UP_START_DATE = "Start date";
	const LOOK_UP_OVERALL_PRICE = "Overall price (euro)";
	const LOOK_UP_CURRENT_BOOKINGS = "Current bookings";
	const LOOK_UP_VEHICLE = "Vehicle";
	const LOOK_UP_LEG_SPACE = "Leg space";
	const LOOK_UP_AVG_SPEED = "Average speed (km/h)";
	const LOOK_UP_WASTAGE = "Wastage (l/100km)";
	const LOOK_UP_AIR_CONDITION = "Air condition";
	const LOOK_UP_NUMBER_SEATS = "Number of seats";
	const LOOK_UP_ACTUAL_WHEEL = "Actual wheel";
	const LOOK_UP_BIRTH_DATE = "Birth date";
	const LOOK_UP_SPOKEN_LANGS = "Spoken languages";
	const LOOK_UP_EMAIL = "eMail";
	const LOOK_UP_HOME_TOWN = "Home town";
	const LOOK_UP_PRICE_RECOM = "Your price recommendation (euro)";
	const LOOK_UP_BOOK = "Book";
	const LOOK_UP_TO = "to";
	
	/**
	 * Get an array which can be used to send a json representation of the translated messages to the frontend.
	 * @param Translator $translator
	 * @return multitype:NULL
	 */
	public static function getJson(Translator $translator) {
		return array('location_distance' => $translator->translate(self::LOOK_UP_LOCATION_DISTANCE),					
				'destination_distance' => $translator->translate(self::LOOK_UP_DESTINATION_DISTANCE),
				'driver' => $translator->translate(self::LOOK_UP_DRIVER),
				'start_date' => $translator->translate(self::LOOK_UP_START_DATE),
				'overall_price' => $translator->translate(self::LOOK_UP_OVERALL_PRICE),
				'current_bookings' => $translator->translate(self::LOOK_UP_CURRENT_BOOKINGS),
				'vehicle' => $translator->translate(self::LOOK_UP_VEHICLE),
				'leg_space' => $translator->translate(self::LOOK_UP_LEG_SPACE),
				'avg_speed' => $translator->translate(self::LOOK_UP_AVG_SPEED),
				'wastage' => $translator->translate(self::LOOK_UP_WASTAGE),
				'air_condition' => $translator->translate(self::LOOK_UP_AIR_CONDITION),
				'number_seats' => $translator->translate(self::LOOK_UP_NUMBER_SEATS),
				'actual_wheel' => $translator->translate(self::LOOK_UP_ACTUAL_WHEEL),
				'birth_date' => $translator->translate(self::LOOK_UP_BIRTH_DATE),
				'spoken_langs' => $translator->translate(self::LOOK_UP_SPOKEN_LANGS),
				'email' => $translator->translate(self::LOOK_UP_EMAIL),
				'home_town' => $translator->translate(self::LOOK_UP_HOME_TOWN),
				'price_recom' => $translator->translate(self::LOOK_UP_PRICE_RECOM),
				'book' => $translator->translate(self::LOOK_UP_BOOK),
				'to' => $translator->translate(self::LOOK_UP_TO),
		);
	}
		
}

?>