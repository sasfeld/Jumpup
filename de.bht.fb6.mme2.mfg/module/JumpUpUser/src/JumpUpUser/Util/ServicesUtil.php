<?php
namespace JumpUpUser\Util;
use JumpUpUser\Util\Messages\ConcreteControllerMessages;

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
}