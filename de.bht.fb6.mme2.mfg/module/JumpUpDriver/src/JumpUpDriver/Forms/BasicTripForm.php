<?php

namespace JumpUpDriver\Forms;
use Zend\Form\Annotation;


/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("BasicBookingForm")
 * @Annotation\Attributes({"class":"tripbasicform"})
 */
class BasicTripForm {
  /**
   *
   * name of the form field for the property bookingId.
   * @var String
   */
  const FIELD_TRIP_ID = 'tripId';
  /**
   * name of the submit element
   */
  const SUBMIT = 'submit';
  /**
   * @Annotation\Type("Zend\Form\Element\Hidden")
   * @Annotation\Required({"required":"true" })
   */
  public $tripId; 
  /**
   * @Annotation\Type("Zend\Form\Element\Submit")
   * @Annotation\Attributes({"value":"Submit"})
   */
  public $submit;
}

