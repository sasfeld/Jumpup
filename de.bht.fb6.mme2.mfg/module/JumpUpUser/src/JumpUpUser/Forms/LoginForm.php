<?php
namespace JumpUpUser\Forms;

use Zend\Form\Annotation;

/**
 * 
* We define the login form (e.g. used for the form building) here. 
*
* It will be built by using annotations from the doctrine/common dependency.
*
* @package    JumpUpUser\Forms
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      19.04.2013
 */
/**
 * 
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("LoginDumb") 
 */
class LoginForm {
    /**
     * 
     * name of the form field for the property rememberme.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_REMEMBER_ME = 'rememberme';
     /**
     * 
     * name of the form field for the property username.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_USERNAME = 'username';
     /**
     * 
     * name of the form field for the property password.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_PASSWORD = 'password';
    
    
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Username:"})
     */
    public $username;
    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Password:"})
     */
    public $password;
     /**
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Remember Me:"})
     */
    public $rememberme;    
    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Submit"})
     */
    public $submit;
}