<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace JumpUpUser;

use Zend\Authentication\AuthenticationService;

use Zend\Authentication\Adapter\DbTable;

use JumpUpUser\Session\AuthenticationStorage;
use JumpUpUser\Validators\UserExists;
use JumpUpUser\Util\Messages\ConcreteControllerMessages;
use Zend\Db\TableGateway\TableGateway;
use JumpUpUser\Models\User;
use Zend\Db\ResultSet\ResultSet;
use JumpUpUser\Models\UserTable;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(            
                /*
                 * export our session-based AuthenticationStorage               
                 */
                 'JumpUpUser\Session\AuthenticationStorage' => function($sm) {
                    return new AuthenticationStorage('login');
                 },
                 /*
                  * very basic AuthService
                  */
                  'AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
                    $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                     $dbTableAuthAdapter  = new DbTable($dbAdapter, 
                                              'user','username','password', "MD5(?)");
                     
                   
                    
                    $authService = new AuthenticationService();             
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('JumpUpUser\Session\AuthenticationStorage'));
                      
                    return $authService;
                },
                /*
                 * export our ConcreteControllerMessages.
                 */
                 'JumpUpUser\Util\Messages\ConcreteControllerMessages' =>  function($sm) {
                    $translator = $sm->get('Translator');                 
                    $concreteContrMessages = new ConcreteControllerMessages($translator);
                    return $concreteContrMessages;
                },
                /*
                 * export our UserTable.
                 */
                'JumpUpUser\Models\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();            
                    $resultSetPrototype->setArrayObjectPrototype(new User());                  
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}

/*
namespace ZendSkeletonModule;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
} */
?>
