<?php
namespace Application\Util;
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
     * Generate a dynamice toString showing all attributes by php methods.
     * @param StdClass $object the object which's attributes will be printed.
     */
    static public function generateDynamicToString($object) {
       $attr = get_class_vars($object);
       $retStr = "Object ".get_class($object)." [";
       if(0 != count($attr)) {
           foreach ($attr as $attribute => $value) {
               $retStr .= "{$attribute} : {$value} <br />\n";
           }
       }
       $retStr .= "]";
       return $retStr;
       
    }
}
