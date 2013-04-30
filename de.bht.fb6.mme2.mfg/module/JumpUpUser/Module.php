<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace JumpUpUser;

use JumpUpUser\Util\UserUtil;

use JumpUpUser\Util\Routes\IRouteStore;

use JumpUpUser\Util\ServicesUtil;

use JumpUpUser\Util\Routes\AuthorizationUtil;

use Zend\Mvc\Router\RouteMatch;

use Zend\Mvc\MvcEvent;

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
    /**
     * 
     * This event method will be called on each request (bootsstrap).
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e) {         
       /* if(null != $e->getRequest()) {    
            $requestUrl = $e->getRequest()->getUriString();
            $sm = $e->getApplication()->getServiceManager();
            $as = ServicesUtil::getAuthService($sm);    
            $isAuthorized = AuthorizationUtil::isAuthorized($as, $requestUrl);
            if(!$isAuthorized) {   
                if(null != $e->getController()) {
                   $e->getController()->redirect()->toRoute(IRouteStore::LOGIN); 
                }   
                else {                                     
                    echo "--> you are not authorized";
                    exit;
                }
            } 
        }*/
    }
    
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
                 * export our user util
                 */ 
               'JumpUpUser\Util\UserUtil' => function($sm) {
                    $em = $sm->get('doctrine.entitymanager.orm_default');
                    $authService = $sm->get('AuthService');
                    $userUtil = new UserUtil($em, $authService);
                    return $userUtil;
                 },
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
?>
