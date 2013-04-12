<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    // ...
    /*
     * Sascha: 
     * 
     * we define our navigation here. 
     * You can use it in any layout file:
     * 
     * echo $this->navigation('navigation')->menu(); 
     */
    'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Home',
                 'route' => 'home',
             ),
             array(
                 'label' => 'Page #1',
                 'route' => 'page-1',
                 'pages' => array(
                     array(
                         'label' => 'Child #1',
                         'route' => 'page-1-child',
                     ),
                 ),
             ),
             array(
                 'label' => 'Page #2',
                 'route' => 'page-2',
             ),
         ),
     ),
     'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         ),
     ),
     // ...
 );
