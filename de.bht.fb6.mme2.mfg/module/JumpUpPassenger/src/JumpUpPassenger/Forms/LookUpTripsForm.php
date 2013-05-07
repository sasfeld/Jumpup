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
   *
   * name of the form field for the property endDate.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_END_DATE = 'endDate';
  /**
   *
   * name of the form field for the property priceFrom.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_PRICE_FROM = 'priceFrom';
  /**
   *
   * name of the form field for the property priceTo.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_PRICE_TO = 'priceTo';
  
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
   * @Annotation\Options({"label":"Date from:"})
   */
  public $startDate;  
  /**
   * @Annotation\Type("Zend\Form\Element\Date")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Date to:"})
   */
  public $endDate;  
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Price from:"})
   */
  public $priceFrom;  
  
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Price to"})
   */
  public $priceTo;  
  
  /**
   * @Annotation\Type("Zend\Form\Element\Submit")
   * @Annotation\Attributes({"value":"Submit"})
   */
  public $submit;
  
}

