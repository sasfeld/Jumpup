<?php
  namespace Application\View\Helper;
  
  use Zend\Form\Form;

use Zend\Form\View\Helper\AbstractHelper;

use \Exception;

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
  *  Usage methods of this util class:
  *  
  *  1) use $this->renderForm($form): 
  *    this will return a string representing the rendering for all fields within the form
  *    
  *  2) use the rendering process:
  *    a process consists of the call $this->renderForm->startRendering().
  *    Now you can append personal fiels that you don't want to appear in the real form object (fields that you don't want to be validated for example).
  *    Stop the rendering by calling $this->renderForm->stopRendering(). The returned string is the rendered form now.
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
       * The form instance. Bound to a rendering process.
       * @var Form
       */
      private $form;
      /**
       * The state within a rendering process. Must match one of the constants in IRenderFormState.
       * @var integer, see IRenderFormState
       */
      private $renderState;
      /**
       * The currently prepared String for the view. 
       * @var String
       */
      private $renderingString;
      
      /**
       * We follow the Zend View Helper idea here. The magic function __invoke is called by the ServiceManager (because he uses it like a function) with the expected parameter.
       * @param form any Zend\Form\Form
       */
      public function __invoke($form = null) {   
        if(null == $form) { 
          $this->renderState = IRenderFormState::FINISHED;
          return $this;  // process mode (builder pattern)
        }    
        else { // direct rendering mode
          $output = $this->_prepareBody($form);          
          $output .= $this->_endForm($form) . PHP_EOL;          
          return $output;
        }
      }
      
     
      /**
       * Only prepare the form from the openTag to all given elements. The close tag isn't prepared here.
       * @param Form $form
       * @return String the prepared body
       */
      private function _prepareBody($form) {
        $form->prepare();
        $output = "";
        $output .= $this->view->form()->openTag($form) . PHP_EOL;
        $elements = $form->getElements();
        foreach($elements as $element) {
          $output .= $this->view->formRow($element) . PHP_EOL;
        }
        return $output;
      }
      
      /**
       * End the form.
       * @param Form $form
       * @return String the end of the form.
       */
      private function _endForm($form) {
        $output = $this->view->form()->closeTag($form) . PHP_EOL;      
        return $output;
      }
      
      /**
       * Start the rendering process of the form. 
       * @return RenderForm the instance itself (builder pattern)
       */
      public function startRendering($form) {
        if(IRenderFormState::FINISHED != $this->renderState) {
          throw new Exception("Illegal state of the RenderForm helper. Please consider the rendering process consisting of startRendering -> appendFields -> stopRendering.");
        }
        
        $this->renderingString = $this->_prepareBody($form);
        // set next state
        $this->renderState = IRenderFormState::INITALIZED;
        return $this; //  builder: return the instance itself so further operations are possible        
      }
      
      /**
       * Append hard-coded html fields here.
       * @param array $hardFields an array of fields in the simple form {"field1", "field2} etc.
       * @return RenderForm the instance itself (builder pattern)
       */
      public function appendFields(array $hardFields) {
        if(IRenderFormState::INITALIZED != $this->renderState && IRenderFormState::APPENDING != $this->renderState) {
          throw new Exception("Illegal state of the RenderForm helper. Please consider the rendering process consisting of startRendering -> appendFields -> stopRendering.");
        }
        
        $fieldsString = "";
        foreach ($hardFields as $field) {
          $fieldsString .= $field . PHP_EOL;
        }
        
        $this->renderingString .= $fieldsString; // concatenate rendering string
        $this->renderState = IRenderFormState::APPENDING;
        return $this;
      }
      
      /**
       * Stop the rendering process. At this point, the final rendering string will be returned.
       * @param Form $form
       * @throws Exception
       */
      public function stopRendering(Form $form) {
        if(IRenderFormState::APPENDING != $this->renderState) {
          throw new Exception("Illegal state of the RenderForm helper. Please consider the rendering process consisting of startRendering -> appendFields -> stopRendering.");
        }
        $this->renderingString .= $this->_endForm($form);
        $this->renderState = IRenderFormState::FINISHED;
        return $this->renderingString; // finally return the rendering string
      }
  }

  
  ?>
