<?php
namespace JumpUpPassenger\Forms;

use Zend\Form\Annotation;

/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("LookUpTripsForm")
 */
class LookUpTripsForm {
  /**
   *
   * name of the form field for the property startPoint.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_START_POINT = 'startPoint';
  /**
   *
   * name of the form field for the property endPoint.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_END_POINT = 'endPoint';  
  /**
   *
   * name of the form field for the property startDate.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_START_DATE = 'startDate';
  
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Start location:"})
   */
  public $startPoint; 
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"End location:"})
   */
  public $endPoint; 
  /**
   * @Annotation\Type("Zend\Form\Element\Date")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Date:"})
   */
  public $startDate;  
  /**
   * @Annotation\Type("Zend\Form\Element\Submit")
   * @Annotation\Attributes({"value":"Submit"})
   */
  public $submit;
  
}

