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
   * name of the form field for the property startCoord.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_START_COORD = 'startCoord';
  /**
   *
   * name of the form field for the property endPoint.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_END_POINT = 'endPoint';  
  /**
   *
   * name of the form field for the property endCoord.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_END_COORD = 'endCoord';  
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
   *
   * name of the hard-coded form field for the user Id. Necessary for the frontend so it can check which user is requesting the trips.
   * @var String
   */
  const FIELD_USER_ID = 'userId';
  /**
   *
   * name of the form field for the property maxDistance.
   * Should be the name of the property/attribute so the data binding works.
   * @var String
   */
  const FIELD_MAX_DISTANCE = 'maxDistance';
  /**
   *
   * name of the button.
   * @var String
   */
  const BUTTON = 'tripsBtn';
  
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
   * @Annotation\Options({"label":"Price to:"})
   */
  public $priceTo;   
 
  /**
   * @Annotation\Type("Zend\Form\Element\Number")
   * @Annotation\Required({"required":"true" })
   * @Annotation\Filter({"name":"StripTags"})
   * @Annotation\Options({"label":"Maximum distance (km):"})
   */
  public $maxDistance;   
 
  
}

