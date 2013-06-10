<?php

namespace JumpUpDriver\Forms;
use Zend\Form\Annotation;


/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("RecommendationForm")
 */
class BasicBookingForm {
  /**
   *
   * name of the form field for the property bookingId.
   * @var String
   */
  const FIELD_BOOKING_ID = 'bookingId';
  /**
   * name of the submit element
   */
  const SUBMIT = 'submit';
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

