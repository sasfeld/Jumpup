<?php
namespace JumpUpDriver\Forms;

use Zend\Form\Annotation;

/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("VehicleForm")
 */
class VehicleForm {
  /**
   *
   * name of the form field for the property brand.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_BRAND = 'brand';
  /**
   *
   * name of the form field for the property type.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_TYPE = 'type';
  /**
   *
   * name of the form field for the property wastage.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_WASTAGE = 'wastage';
  /**
   *
   * name of the form field for the property numberSeats.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_NUMBER_SEATS = 'number_seats';
  /**
   *
   * name of the form field for the property legSpace.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_LEG_SPACE = 'leg_space';
  /**
   *
   * name of the form field for the property avg speed.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_AVG_SPEED = 'avg_speed';
  /**
   * Key for the radio input aircondtion
   * @var unknown
   */
  const FIELD_AIRCONDITION = 'aircondition';
  /**
   * Key for the radio input actualwheel.
   * @var unknown
   */
  const FIELD_ACTUAL_WHEEL = 'actualwheel';
  /**
   * Key for the file input vehiclepic.
   * @var unknown
   */
  const FIELD_VEHICLE_PIC = 'vehiclepic';
  
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Brand:"})
   */
  public $brand; 
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Type:"})
   */
  public $type;
 
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Wastage (l / 100km):"})
   */
  public $wastage;
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Number of seats:","attributes":{"size":"5"}})
   */
  public $numberseats;
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Leg space:"})
   */
  public $legspace;
   /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Average speed (km/h):","attributes":{"size":"5"}})
   */
  public $avgspeed;    
  /**
   * @Annotation\Type("Zend\Form\Element\Radio")
   * @Annotation\Options({
   *          "label":"Air condition:",
   *          "value_options":{"true":"yes", "false":"no"}
   * })
   */
  public $aircondition;
  /**
   * @Annotation\Type("Zend\Form\Element\Radio")
   * @Annotation\Options({
   *          "label":"Actual wheel:",
   *          "value_options":{"summer":"Summer", "winter":"Winter", "allweather":"All weather"}
   * })
   */
  public $actualwheel;
  /**
   * @Annotation\Type("Zend\Form\Element\File")
   * @Annotation\Required({"required":"false" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Vehicle pic:"})
   */
  public $vehiclepic;  
  /**
   * @Annotation\Type("Zend\Form\Element\Submit")
   * @Annotation\Attributes({"value":"Submit"})
   */
  public $submit;
  
}

