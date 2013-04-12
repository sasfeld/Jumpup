<?php
  namespace Application\View\Helper;
  
  use Zend\Form\View\Helper\AbstractHelper;

  class RenderForm extends AbstractHelper 
  {      
      public function __invoke($form) {
          $form->prepare();          
        
          $output = "";
         // $output .= $this->view->openTag($form) . PHP_EOL; 
          $elements = $form->getElements();
          foreach($elements as $element) {
              $output .= $this->view->formRow($element) . PHP_EOL;
          }
          // $output .= $this->form()->closeTag($form) . PHP_EOL;
          
          return $output;
      }
  }

  
  ?>
