<?php
namespace JumpUpUser\Validators;

use Doctrine\ORM\EntityManager;

use Zend\Validator\AbstractValidator;

/**
 * 
* General validator for validators which need access to the DB via doctrine.
*
*
* @package    JumpUpUser\Validators
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      15.05.2013
 */
abstract class AbstractDbValueValidator extends AbstractValidator {
  /**
   *
   *
   * @var Translator
   */
  private $translator;
  /**
   *
   * @see EntitiyManager
   */
  protected $entityManager;
  
  /**
   * This method is called when you set an option.
   * You have to set the entityManager option before using the validator!
   * @param EntityManager $em
   * @throws InvalidArgumentException if the argument is null
   */
  public function setEntityManager(EntityManager $em) {
    if(null === $em) {
      throw Exception_Util::throwInvalidArgument('$em', EntityManager, 'null');
    }
    $this->entityManager = $em;
  }
  
  /**
   * (non-PHPdoc)
   * @see \Zend\Validator\ValidatorInterface::isValid()
   */
  abstract public function isValid($value);
  
}