<?php

namespace Application\Util;

class FormTransmitterUtil {
	const DELIMITER = ":";
	const MESSAGE_BEGIN = "..::";
	const MESSAGE_END = "::..";
	const ELEMENT_NAME = "name";
	const ELEMENT_VALUE = "value";
	
	public static function getMessage($element) {
		$clazz = get_class ( $element );
		// ugly, but there's no interface or super class for form elements....
		if ("Zend\Form\Element\Text" === $clazz || "Zend\Form\Element\Text" === $clazz || "tZend\Form\Element\Date" === $clazz || "Zend\Form\Element\Number" === $clazz) {
			return self::encodeMessage ( $element->getName (), $element->getValue () );
		}
	}
	
	private static function encodeMessage($elementName, $elementValue) {
		$resultingString = self::MESSAGE_BEGIN . "%{$elementName}%" . self::DELIMITER . "%{$elementValue}%" . self::MESSAGE_END;
		return $resultingString;
	}
	
	public static function decodeMessage($message) {
		if (self::isValidMessage ( $message )) {			
			$strSplit = explode ( self::DELIMITER, $message );
			$elementName = $strSplit [0];
			$elementValue = $strSplit [1];
			
			$resultingArray = array ();
			$resultingArray [self::ELEMENT_NAME] = $elementName;
			$resultingArray [self::ELEMENT_VALUE] = $elementValue;
			return $resultingArray;
		}
		return null;
	}
	
	/**
	 *
	 * @param
	 *        	message
	 */
	public static function isValidMessage($message) {
		if (false === strpos (  $message, self::MESSAGE_BEGIN ) || false === strpos ( $message, self::MESSAGE_END )) {
			return false;
		}
		return true;
	}
}