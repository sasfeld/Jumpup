<?php
namespace JumpUpDriver\Forms;

use Zend\Form\Annotation;

/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("RecommendationForm")
 */
class RecommendationForm {
  /**
   *
   * name of the form field for the property startPoint.
   * @var String
   */
  const FIELD_RECOMM_PRICE = 'price';
  /**
   *
   * name of the form field for the property bookingId.
   * @var String
   */
  const FIELD_BOOKING_ID = 'bookingId';
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Attributes({"recomm_form"})
   * @Annotation\Options({"label":"Your recommendation:","attributes":{"size":"5"}})
   */
  public $price; 
  /**
   * @Annotation\Type("Zend\Form\Element\Hidden")
   * @Annotation\Required({"required":"true" })
   */
  public $bookingId; 
  /**
   * @Annotation\Type("Zend\Form\Element\Submit")
   * @Annotation\Attributes({"value":"Submit"})
   */
  public $submit;
  
}

