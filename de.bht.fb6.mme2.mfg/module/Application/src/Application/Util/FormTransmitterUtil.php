<?php
namespace Application\Util;

/**
 *
 * This static tool class offers help for the transmittion of Zend's form elements via the flashMessenger.
 * 
 * It decodes FormElements and transforms them to a String representation which can be decoded by another controller so the form can be reconstructed.
 *
 *
 * @package Application\Util
 * @subpackage
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 14.06.2013
 */
class FormTransmitterUtil {
	const DELIMITER = "::::";
	const MESSAGE_BEGIN = "..::";
	const MESSAGE_END = "::..";
	const ELEMENT_NAME = "name";
	const ELEMENT_VALUE = "value";
	const ELEMENT_MESSAGES = "messages";
	const MESSAGES_SEPARATOR = ";;";
	public static function getMessage($element) {
		$clazz = get_class ( $element );
		// ugly, but there's no interface or super class for form elements....
		if ('Zend\\Form\\Element\\Text' === $clazz || 'Zend\\Form\\Element\\Text' === $clazz || 'Zend\\Form\\Element\\Date' === $clazz || 'Zend\\Form\\Element\\Number' === $clazz) {
			return self::encodeMessage ( $element->getName (), $element->getValue (), $element->getMessages () );
		}
	}
	private static function encodeMessage($elementName, $elementValue, $elementMesages) {
		$resultingString = self::MESSAGE_BEGIN . "%{$elementName}%" . self::DELIMITER . "%{$elementValue}%" . self::DELIMITER . "%" . self::_getMessagesString ( $elementMesages ) . "%" . self::MESSAGE_END;
		return $resultingString;
	}
	private static function _getMessagesString(array $validationMessages) {
		$retString = "";
		foreach ( $validationMessages as $validationMessage ) {
			if ("" !== $retString) {
				$retString .= self::MESSAGES_SEPARATOR;
			}
			$retString .= $validationMessage;
		}
		return $retString;
	}
	private static function _decodeMessagesString($messagesString) {
		$cleanString = str_replace ( "%", "", $messagesString );
		$cleanString = str_replace ( self::MESSAGE_END, "", $cleanString );
		if ("" !== $cleanString) {
			return explode ( self::MESSAGES_SEPARATOR, $cleanString );
		}
		return null;
	}
	public static function decodeMessage($message) {
		if (self::isValidMessage ( $message )) {
			$strSplit = explode ( self::DELIMITER, $message );			
			$elementName = str_replace ( self::MESSAGE_BEGIN, "", $strSplit [0] );
			$elementName = str_replace ( "%", "", $elementName );
			$elementValue = str_replace ( "%", "", $strSplit [1] );
			$elementMessages = self::_decodeMessagesString ( $strSplit [2] );
			
			$resultingArray = array ();
			$resultingArray [self::ELEMENT_NAME] = $elementName;
			$resultingArray [self::ELEMENT_VALUE] = $elementValue;
			$resultingArray [self::ELEMENT_MESSAGES] = $elementMessages;
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
		if (false === strpos ( $message, self::MESSAGE_BEGIN ) || false === strpos ( $message, self::MESSAGE_END )) {
			return false;
		}
		return true;
	}
}