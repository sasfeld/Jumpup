<?php

return array(
    'router' => array(
        'routes' => array(
                   /*
                    'jump-up-user-register-index' => array(
                        'type'    => 'Literal',
                            'options' => array(
                            'route' => '/',
                            'defaults' => array(
                                'controller' => 'jump-up-user\Controller\Register',
                                'action'     => 'showform',
                            ),
                        ),
                    ),*/
            'listdriverbookings' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/listdriverbookings',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpDriver\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'viewBookings',
                    ),
                ),
            ),
               'driverjson' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/driverjson',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Json',
                            'action'        => 'vehicle',
                        ),
                    ),                    
                ),
                'editvehicle' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/editvehicle',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Vehicle',
                            'action'        => 'edit',
                        ),
                    ),                    
                ),
                'addvehicle' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/addvehicle',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Vehicle',
                            'action'        => 'add',
                        ),
                    ),                    
                ),
                'removevehicle' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/removevehicle',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Vehicle',
                            'action'        => 'remove',
                        ),
                    ),                    
                ),
                'listvehicles' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/listvehicles',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Vehicle',
                            'action'        => 'list',
                        ),
                    ),                   
                ),
                'addtrip' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/addtrip',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'AddTrip',
                            'action'        => 'step1',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'process' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'    => '/[:action]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                ),
                            ),
                        ),
                    ),
                ),
                'addtrip_error'=> array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/addtriperror',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'AddTrip',
                            'action'        => 'error',
                        ),
                    ),
                  ),
                  'addtrip_success'=> array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/addtripsuccess',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'AddTrip',
                            'action'        => 'success',
                        ),
                    ),
                  ),
                 'trips' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/trips',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Trips',
                            'action'        => 'list',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'default' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'    => '/[:action]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                ),
                            ),
                        ),
                    ),
                ), 
           
                'booking' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/bookings',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpDriver\Controller',
                            'controller'    => 'Bookings',
                            'action'        => 'list',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'default' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                //'route'    => '/[:controller[/:action]]',
                                'route'    => '/[:action]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(                      
                                ),
                            ),
                        ),
                    ),
                ),  
         ),         
    ),
    'controllers' => array(           
        'factories' => array(
            'JumpUpDriver\Controller\AddTrip' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                return new \JumpUpDriver\Controller\AddTripController(
                    $sm->get("doctrine.entitymanager.orm_default")
                );
            },
           'JumpUpDriver\Controller\Vehicle' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                return new \JumpUpDriver\Controller\VehicleController(
                    $sm->get("doctrine.entitymanager.orm_default")
                );
            }, 
           'JumpUpDriver\Controller\Json' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                return new \JumpUpDriver\Controller\JsonController(
                    $sm->get("doctrine.entitymanager.orm_default")
                );
            }, 
           'JumpUpDriver\Controller\Booking' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                return new \JumpUpDriver\Controller\BookingController(\Application\Util\ServicesUtil::getDoctrineEm($sm)
                    );
            }, 
        ),    
        'invokables' => array(
            //'JumpUpDriver\Controller\AddTrip' => 'JumpUpDriver\Controller\AddTripController',
            //'JumpUpDriver\Controller\AddTrip' => 'JumpUpDriver\Controller\AddTripController',
        ),
    ), 
    'view_manager' => array(
        'template_path_stack' => array(
            'JumpUpDriver' => __DIR__ . '/../view'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),  
    /*
     * ..:: doctrine ::..
     */
    'doctrine' => array(
        'driver' => array(
            'ApplicationDriver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/JumpUpDriver/Models')
            ),
            'orm_default' => array(
                'drivers' => array(
                     'JumpUpDriver\Models' => 'ApplicationDriver'
                )
            )
        )
      ), 
      /*
       * ..:::::::::::::...
       */
);

/*
return array(
    'controllers' => array(
        'invokables' => array(
            'ZendSkeletonModule\Controller\Skeleton' => 'ZendSkeletonModule\Controller\SkeletonController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'module-name-here' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/module-specific-root',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'ZendSkeletonModule\Controller',
                        'controller'    => 'Skeleton',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ZendSkeletonModule' => __DIR__ . '/../view',
        ),
    ),
);
*/
?>