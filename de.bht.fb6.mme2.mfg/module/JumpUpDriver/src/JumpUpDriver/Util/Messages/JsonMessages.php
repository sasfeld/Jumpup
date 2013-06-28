<?php

namespace JumpUpDriver\Util\Messages;

use Zend\I18n\Translator\Translator;
/**
 * 
* This (static) class offers messages for the json handler in the frontend.
*
* @package    JumpUpDriver\Util
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      24.06.2013
 */
class JsonMessages {
	const VEHICLE_RECOMMENDED_PRICE = "The recommended price for your vehicle is";
	const VEHICLE_RECOMMENDED_YOUR = "euro. Your wastage is";
	const VEHICLE_CALCULATION = "Calculation formula";
	/**
	 * Get an array which can be used to send a json representation of the translated messages to the frontend.
	 * @param Translator $translator
	 * @return multitype:NULL
	 */
	public static function getJson(Translator $translator) {
		return array('recommended' => $translator->translate(self::VEHICLE_RECOMMENDED_PRICE),
					'recommended_your' => $translator->translate(self::VEHICLE_RECOMMENDED_YOUR),	
					'calculation' => $translator->translate(self::VEHICLE_CALCULATION), 				
				
		);
	}
		
}

?>