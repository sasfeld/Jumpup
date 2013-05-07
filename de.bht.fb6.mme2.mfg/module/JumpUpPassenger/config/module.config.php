<?php

return array(
    'router' => array(
        'routes' => array(                   
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
                    $sm->get("doctrine.entitymanager.orm_default")
                );
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