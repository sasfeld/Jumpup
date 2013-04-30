<?php
namespace Application\Util;

use \JumpUpUser\Session\AuthenticationStorage;


use Zend\Authentication\AuthenticationService;
use \JumpUpUser\Util\Messages\ConcreteControllerMessages;


/**
 * 
* Small util class with static methods which offers often used services from the service manager.
*
*
* @package    
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
use JumpUpUser\Models\UserTable;
use Zend\ServiceManager\ServiceManager;
class ServicesUtil {
    /**
     * fully qualified name (classpath) of the UserTable class
     * @var String 
     */
    const CLASSPATH_USERTABLE = 'JumpUpUser\Models\UserTable';
    /**
     * fully qualified name (classpath) of the ConcreteConrtollerMessages class
     * @var String 
     */
    const CLASSPATH_CONTROLLER_MESSAGES = 'JumpUpUser\Util\Messages\ConcreteControllerMessages';
    /**
     * name of the AuthService as configured in the Module.php
     * @var String
     */
    const CLASSPATH_AUTH_SERVICE = 'AuthService';
    /**
     * full< qualified name of the SessionStorage as configured in the Module.php
     * @var String
     */
    const CLASSPATH_AUTH_STORAGE_SERVICE = 'JumpUpUser\Session\AuthenticationStorage';
    /**
     * 
     * name of the translator service
     * @var String
     */
    const CLASSPATH_TRANSLATOR = 'translator';
    /**
     * Get the UserTable instance from the ServiceManager.
     * @see UserTable
     * @param ServiceManager $sm
     */
    static public function getUserTable(ServiceManager $sm) {
        return $sm->get(self::CLASSPATH_USERTABLE);        
    }    
    /**
     * Get the ConcreteControllerMessages instance from the ServiceManager.
     * @see ConcreteControllerMessages
     * @param ServiceManager $sm
     */
    static public function getControllerMessages(ServiceManager $sm) {
        return $sm->get(self::CLASSPATH_CONTROLLER_MESSAGES);
    }
    
    /**
     * Get the AuthService instance.
     * @see AuthenticationService
     */
    static public function getAuthService(ServiceManager $sm) {
        return $sm->get(self::CLASSPATH_AUTH_SERVICE);
    }
    
    /**
     * Get the SessionStorage instance.
     * @see
     */
    static public function getSessionStorageService(ServiceManager $sm) {
        return $sm->get(self::CLASSPATH_AUTH_STORAGE_SERVICE);
    }
    
/**
     * Get the translator instance.
     * @see
     */
    static public function getTranslatorService(ServiceManager $sm) {
        return $sm->get(self::CLASSPATH_TRANSLATOR);
    }
}