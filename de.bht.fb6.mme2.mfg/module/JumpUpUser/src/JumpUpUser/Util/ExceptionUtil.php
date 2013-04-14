<?php
namespace JumpUpUser\Util;
/**
 * 
* This util class offers static methods to throw unique exceptions.
* 
* Those exceptions offer a unique look & feel.
*
* @package    Application\util
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      06.04.2013
 */
class Exception_Util {
    /**
     * Throw a new unique InvalidArgumentException.
     * @param string $argName the name of the argument in the method header.
     * @param unknown_type $expectedType the expected type of the argument
     * @param unknown_type $givenValue the given value.
     * @return a new instance of InvalidArgumentException
     */
    static public function throwInvalidArgument($argName, $expectedType, $givenValue) {        
        return new InvalidArgumentException("The type of the parameter {$argName} must be a {$expectedType}! Your value was: {$givenValue}");
    }
    
   
    
}
?>
