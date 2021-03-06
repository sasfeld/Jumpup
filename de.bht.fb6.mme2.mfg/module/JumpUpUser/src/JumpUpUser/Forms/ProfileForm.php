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
 * @Annotation\Name("ProfileForm") 
 */
class ProfileForm {   
     /**
     * 
     * name of the form field for the property birthdate.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_BIRTHDATE = 'birthdate';
     /**
     * 
     * name of the form field for the property profilePic.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_PROFILE_PIC = 'profilepic';
     /**
     * 
     * name of the form field for the property homecity.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_HOMECITY = 'homecity';
     /**
     * 
     * name of the form field for the property homecity.
     * Should be the name of the property/attribute so the data binding works.
     * @var String
     */
    const FIELD_SPOKEN_LANGS = 'spokenlanguages';
    
    
    /**
     * @Annotation\Type("Zend\Form\Element\Date")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Birth date:"})
     */
    public $birthdate;
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Home City:"})
     */
    public $homecity;
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Spoken languages:"})
     */
    public $spokenlanguages;
    /**
     * @Annotation\Type("Zend\Form\Element\File")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Options({"label":"Profile pic:"})
     */
    public $profilepic;
    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Change profile"})
     */
    public $submit;
}