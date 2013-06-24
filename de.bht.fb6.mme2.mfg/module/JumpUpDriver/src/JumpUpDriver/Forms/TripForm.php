<?php
namespace JumpUpDriver\Forms;

use Zend\Form\Annotation;

/**
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("AddTripForm")
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
   *
   * name of the form field for the property vehicle.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_VEHICLE = 'vehicle';
  /**
   *
   * name of the form field for the property duration (googlemap).
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_DURATION = 'duration';
  /**
   *
   * name of the form field for the property distance (googlemap).
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_DISTANCE = 'distance';
  /**
   *
   * name of the form field for the property overviewPath (googlemap).
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_OVERVIEW_PATH = 'overviewPath';
  /**
   *
   * name of the form field for the property viaWaypoints (googlemap).
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_VIA_WAYPOINTS= 'viaWaypoints';
  /**
   *
   * name of the form field for the property numberSeats.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_NUMBER_SEATS = 'maxSeats';
  /**
   * key to the form field for the submit button.
   * @var String
   */
  const SUBMIT = 'submit';
  
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
   * @Annotation\Options({"label":"Trip date:"})
   */
  public $startDate;
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Attributes({"addtrip_form"})
   * @Annotation\Value({0})
   * @Annotation\Options({"label":"Price (€):","attributes":{"size":"5"},"value":0})
   */
  public $price;  
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Attributes({"addtrip_form"})
   * @Annotation\Validator({"name":"GreaterThan","options":{"min":"1"}})
   * @Annotation\Validator({"name":"LessThan","options":{"max":"8"}})
   * @Annotation\Options({"label":"Number of seats:","attributes":{"size":"5"}})
   */
  public $maxSeats;

  
}

