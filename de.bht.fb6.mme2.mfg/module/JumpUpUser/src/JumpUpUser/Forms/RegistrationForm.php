<?php
namespace JumpUpUser\Forms; 

use Zend\Stdlib\Hydrator\ObjectProperty;

use JumpUpUser\Filters\RegistrationFormFilter;

use Zend\I18n\Translator\Translator;

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
    /*
     * Consider that the following identifiers must be equal to the attributes' names in the User entity.
     * This is because the data will be binded automaticly.
     */
    /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_USERNAME = "username";
     /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_SUBMIT = "submit";
     /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_PASSWORD = "password";
     /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_REPEAT_PASSWORD = "repeat_password";
     /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_EMAIL = "email";
     /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_PRENAME = "prename";
     /**
     * identifier for the form field. Used by other instances (e.g. Filter)
     * @var String
     */
    const FIELD_LASTNAME = "lastname";
    
    /**
     * Constrcut a new registration form. 
     * @param $formname the name of the form as rendered in the view
     * @param $translator the Zend translator
     */
    public function __construct($formname, Translator $translator) {
        parent::__construct($formname);
        $this->setHydrator(new ObjectProperty());
        
        /*
         * Input Field: username
         */
        $fieldUsername = new Element\Text(); 
        $fieldUsername->setLabel($translator->translate('Username'))
            ->setAttributes(array(
                'class' => 'reg_form_input',
                'size'  => RegistrationFormFilter::USERNAME_MAX_CHARS,
               ))
            ->setName(self::FIELD_USERNAME);
         /*
          * Input Field: prename        
          */
        $fieldPrename = new Element\Text(); 
        $fieldPrename->setLabel($translator->translate('Prename'))
            ->setAttributes(array(
                'class' => 'reg_form_input',
                'size'  => '30',
               ))
            ->setName(self::FIELD_PRENAME);   
        /*
          * Input Field: lastname     
          */
        $fieldLastname = new Element\Text(); 
        $fieldLastname->setLabel($translator->translate('Lastname'))
            ->setAttributes(array(
                'class' => 'reg_form_input',
                'size'  => '30',
               ))
            ->setName(self::FIELD_LASTNAME);       
         /*
         * Input Field: password
         */
        $fieldPassword = new Element\Password(); 
        $fieldPassword->setLabel($translator->translate('Password'))
            ->setAttributes(array(
                'class' => 'reg_form_input',
                'size'  => '20',
               ))
            ->setName(self::FIELD_PASSWORD);    
         /*
         * Input Field: repeat password
         */
        $fieldRepeatPassword = new Element\Password(); 
        $fieldRepeatPassword->setLabel($translator->translate('Repeat password'))
            ->setAttributes(array(
                'class' => 'reg_form_input',
                'size'  => '20',
               ))
            ->setName(self::FIELD_REPEAT_PASSWORD);  
        /*
         * Input Field: eMail adress
         */
        $fieldEMail = new Element\Email(); 
        $fieldEMail->setLabel($translator->translate('eMail address'))
            ->setAttributes(array(
                'class' => 'reg_form_input',
                'size'  => '30',
               ))
            ->setName(self::FIELD_EMAIL);          
        /*
         * Submit button
         */
        $fieldSubmit = new Element\Submit();
        $fieldSubmit->setAttributes(array(
                'class' => 'reg_form_submit',
                'value' => $translator->translate('Submit'),
                ))
            ->setName(self::FIELD_SUBMIT);
        
        // the order is important for the view -> FIFO
        $this->add($fieldUsername);      
        $this->add($fieldPrename);
        $this->add($fieldLastname); 
        $this->add($fieldPassword);
        $this->add($fieldRepeatPassword);
        $this->add($fieldEMail);
        $this->add($fieldSubmit);
    }
    
}