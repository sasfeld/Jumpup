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
                'login' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/auth',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpUser\Controller',
                            'controller'    => 'Auth',
                            'action'        => 'login',
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
                 'success' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/success',
                        'defaults' => array(
                            '__NAMESPACE__' => 'JumpUpUser\Controller',
                            'controller'    => 'Success',
                            'action'        => 'index',
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
            'JumpUpUser\Controller\Auth' => 'JumpUpUser\Controller\AuthController',
            'JumpUpUser\Controller\Success' => 'JumpUpUser\Controller\SuccessController',
        ),
    ), 
    'view_manager' => array(
        'template_path_stack' => array(
            'JumpUpUser' => __DIR__ . '/../view'
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