<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;




use JumpUpUser\Util\Auth\CheckAuthentication;

use Application\Util\ServicesUtil;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use JumpUpUser\Util\UserUtil;


class Module
{  
    
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this->_setLangSelection($e);
        $this->_setPhpSettings();
    }
    
    /**
     * Set the prefered language (locale) depending on if the user is logged in or not.
     * @param MvcEvent $e
     */
    private function _setLangSelection(MvcEvent $e) {
        $sm = $e->getApplication()->getServiceManager();
        $authService = ServicesUtil::getAuthService($sm);
        if(CheckAuthentication::isAuthorized($authService, "")) {
            $userUtil = \JumpUpUser\Util\ServicesUtil::getUserUtil($sm);
            $loggedInUser = $userUtil->getCurrentUser();
            $localStr = $loggedInUser->getLocale();
            if(null !== $localStr) { // perform selection of local file
                $translator = ServicesUtil::getTranslatorService($sm);
                $translator->setLocale($localStr);
            }
        }
    }

    /**
     * Configure PHP settings for the lifetime of this request.
     */
    protected function _setPhpSettings()
    {
        $config = $this->getConfig();

        $this->_setExecutionTimeout($config['application']['php']['php_time_limit']);
    }

    /**
     * @param $executionTimeout
     */
    protected function _setExecutionTimeout($executionTimeout)
    {
        set_time_limit($executionTimeout);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
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
    				),
    			);
    }
}
