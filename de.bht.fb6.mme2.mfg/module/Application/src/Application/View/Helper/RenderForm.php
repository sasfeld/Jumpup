<?php
  namespace Application\View\Helper;
  
  use Zend\Form\Form;

use Zend\Form\View\Helper\AbstractHelper;

  /**
   * 
  * This view service class offers a functionality to render a Zend\Form object.
  *
  * You should configure it (module.config.php) as view service.
  * 
  * an example:
  * 'view_helpers' => array(
  *      'invokables'=> array(
  *          'renderForm' => 'Application\View\Helper\RenderForm' , 
  *      )     
  *  ),
  *  
  * 
  *  Now, any view can access the view helper using $this->renderForm()...
  *
  * @package    
  * @subpackage 
  * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
  * @license    GNU license
  * @version    1.0
  * @since      13.04.2013
   */
  class RenderForm extends AbstractHelper 
  {      
      /**
       * We follow the Zend View Helper idea here. The magic function __invoke is called by the ServiceManager (because he uses it like a function) with the expected parameter.
       * @param form any Zend\Form\Form
       */
      public function __invoke(Form $form) {
          $form->prepare();          
        
          $output = "";
          $output .= $this->view->form()->openTag($form) . PHP_EOL; 
          $elements = $form->getElements();
          foreach($elements as $element) {
              $output .= $this->view->formRow($element) . PHP_EOL;
          }
          $output .= $this->view->form()->closeTag($form) . PHP_EOL;
          
          return $output;
      }
  }

  
  ?>
