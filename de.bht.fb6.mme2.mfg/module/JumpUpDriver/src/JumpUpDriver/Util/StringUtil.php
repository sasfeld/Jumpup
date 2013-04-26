<?php
namespace JumpUpDriver\Util;
/**
 * 
* This util class offers static methods to generate unique strings.
* 
* Those strings (e.g. by a __toString function) offer a unique look & feel.
*
* @package    Application\Util
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      06.04.2013
 */
class StringUtil {
    /**
     * Generate a unique string showing all attributes' names and their values.
     * @param string $className
     * @param array $attributes
     * @return String the concatenated string.
     */
    static public function generateToString($className, array $attributes) {
        $toString = "\n\n<br /><br />--------------------\n<br />Instance of $className <br /><br />\n\n";
        foreach ($attributes as $attribute => $value) {
            $toString .= "{$attribute}: {$value}<br />\n";
        }
        $toString .= "--------------------";
        return $toString;
    }
    
    /**
     * 
     * Check whether a given parameter is a string.
     * @param unknown_type $eMail
     * @return true if the given parameter is a string.
     * @throws InvalidArgumentException if the given type is not a string.
     */
    static  public function isString($eMail) {
        if(!is_string($eMail)) {
            throw Exception_Util::throwInvalidArgument("", "string", $eMail);
        }
        return true;
    }
    
    /**
     * 
     * Check whether the string ends with a specified char sequence.
     * @param String $string the given string
     * @param String $endOfString the end of the string to be checked
     * @return true if the string ends with the $endofString
     */
    static public function endsWith($string, $endOfString) {
        if(!is_string($string)) {
            throw Exception_Util::throwInvalidArgument("", "string", $string);
        }
        $end = substr($string, strlen($string) - strlen($endOfString));
        if(false != $end) {
            return ($end === $endOfString);
        }
        return false;
    }
}