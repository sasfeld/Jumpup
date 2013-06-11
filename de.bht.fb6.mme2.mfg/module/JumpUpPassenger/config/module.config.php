<?php

return array(
    'router' => array(
        'routes' => array(  
            'dopassengerrecommendation' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/dopassengerrecommendation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'doRecommendation',
                    ),
                ),
            ),
            'applypassengerbooking' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/applypassengerbooking',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'apply',
                    ),
                ),
            ),
            'denypassengerbooking' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/denypassengerbooking',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'deny',
                    ),
                ),
            ),
            'listpassengerbookings' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/listpassbookings',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'viewBookings',
                    ),
                ),
            ),
            'bookerror' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/bookerror',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'error',
                    ),
                ),
            ),
            'booktrip' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/booktrip',
                    'defaults' => array(
                        '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                        'controller'    => 'Booking',
                        'action'        => 'bookTrip',
                    ),
                ),
            ),
            'tripsjson' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/tripsjson',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                            'controller'    => 'Json',
                            'action'        => 'trip',
                        ),
                    ),                    
                ),
            'lookuptrips' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/lookuptrips',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                            'controller'    => 'ViewTrips',
                            'action'        => 'lookUp',
                        ),
                    ),                    
                ),
            'showtrips' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/showtrips',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpPassenger\Controller',
                            'controller'    => 'ViewTrips',
                            'action'        => 'showTrips',
                        ),
                    ),                    
                ),
         ),         
    ),
    'controllers' => array(           
        'factories' => array(          
             'JumpUpPassenger\Controller\ViewTrips' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                return new \JumpUpPassenger\Controller\ViewTripsController(
                    \Application\Util\ServicesUtil::getDoctrineEm($sm));             
            },   
            'JumpUpPassenger\Controller\Booking' => function(Zend\Mvc\Controller\ControllerManager $cm) {
              $sm = $cm->getServiceLocator();
              return new \JumpUpPassenger\Controller\BookingController(
                   \Application\Util\ServicesUtil::getDoctrineEm($sm));
            },
            'JumpUpPassenger\Controller\Json' => function(Zend\Mvc\Controller\ControllerManager $cm) {
              $sm = $cm->getServiceLocator();
              return new \JumpUpPassenger\Controller\JsonController(
                   \Application\Util\ServicesUtil::getDoctrineEm($sm));
            },
        ),    
        'invokables' => array(
             ),
    ), 
    'view_manager' => array(
        'template_path_stack' => array(
            'JumpUpPassenger' => __DIR__ . '/../view'
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
                'paths' => array(__DIR__ . '/../src/JumpUpPassenger/Models')
            ),
            'orm_default' => array(
                'drivers' => array(
                     'JumpUpPassenger\Models' => 'ApplicationDriver'
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