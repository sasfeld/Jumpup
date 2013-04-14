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
        'jump-up-user' => array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => '/register',
                'defaults' => array(
                    'controller'    => 'jump-up-user\Controller\Register',
                    'action'        => 'showform',
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
        'invokables' => array(
            'JumpUpUser\Controller\Index' => 'JumpUpUser\Controller\IndexController',
            'JumpUpUser\Controller\Register' => 'JumpUpUser\Controller\RegisterController',
        ),
    ), 
    'view_manager' => array(
        'template_path_stack' => array(
            'mfg-test-modul' => __DIR__ . '/../view'
        ),
    ),   
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