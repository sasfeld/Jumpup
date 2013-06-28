<?php

namespace Application\Util;

use Application\Util\ExceptionUtil;
/**
 * 
* This util class offers static methods to work with international units like dates,...
* 
* Those strings (e.g. by a __toString function) offer a unique look & feel.
*
* @package    Application\Util
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      25.06.2013
 */
class IntlUtil {
	
	/**
	 * Get the german date representation for an input string.
	 * @param String $str
	 * @return string, the formatted date
	 */
	public static function strToDeDate($str) {
		if(!is_string($str)) {
			throw ExceptionUtil::throwInvalidArgument('$str', 'string', $str);
		}
		
		$datum_de = trim(date("d.m.Y ", strtotime($str)));
		return $datum_de;
	}
}

?>