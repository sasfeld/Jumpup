<?php
namespace JumpUpDriver\Forms;

use Zend\Form\Annotation;

/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("TripForm")
 */
class TripForm {
  /**
   *
   * name of the form field for the property startPoint.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_START_POINT = 'startPoint';
  /**
   *
   * name of the form field for the property startCoordinate.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_START_COORDINATE = 'startCoordinate';
  /**
   *
   * name of the form field for the property endPoint.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_END_POINT = 'endPoint';
  /**
   *
   * name of the form field for the property endCoordinate.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_END_COORDINATE = 'endCoordinate';
  /**
   *
   * name of the form field for the property startDate.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_START_DATE = 'startDate';
  /**
   *
   * name of the form field for the property price.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_PRICE = 'price';
  
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Start location:"})
   */
  public $startPoint;
  /**
   * @Annotation\Type("Zend\Form\Element\Hidden")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   */
  public $startCoordinate;
  /**
   * @Annotation\Type("Zend\Form\Element\Text")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"End location:"})
   */
  public $endPoint;
  /**
   * @Annotation\Type("Zend\Form\Element\Hidden")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   */
  public $endCoordinate;  
  /**
   * @Annotation\Type("Zend\Form\Element\Date")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Date:"})
   */
  public $startDate;
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Price:"})
   */
  public $price;
  /**
   * @Annotation\Type("Zend\Form\Element\Submit")
   * @Annotation\Attributes({"value":"Submit"})
   */
  public $submit;
  
}

