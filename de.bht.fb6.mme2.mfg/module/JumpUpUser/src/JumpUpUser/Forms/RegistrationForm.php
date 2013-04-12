<?php
namespace JumpUpUser\Forms; 

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * 
* This is a (Zend\Form\Form) class.
*
* It contains \Form\Fields which can be rendered by a view.
*
* @package    JumpUpUser\Forms
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      12.04.2013
 */
class RegistrationForm extends Form {
    const FIELD_USERNAME = "username";
    
    /**
     * Constrcut a new registration form. 
     * @param $formname the name of the form as rendered in the view
     */
    public function __construct($formname) {
        parent::__construct($formname);
        $fieldUsername = new Element\Text(); 
        $fieldUsername->setLabel('Username')
            ->setAttributes(array(
                'class' => 'input_username',
                'size'  => '30',
               ))
            ->setName(self::FIELD_USERNAME);
        
        $this->add($fieldUsername);
    }
    
}