<?php
namespace JumpUpDriver\Util;
/**
 *
 * This interface stores the so-called "repositories" in the entity manager.
 * A repo is nothing more than a fully-qualified path to a model/entity.
 *
 * @package    JumpUpDriver\Util
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      12.06.2013
 */
interface IEntitiesStore {
  /**
   * @see JumpUpDriver\Models\Trip
   * @var String
   */
  const TRIP = 'JumpUpDriver\Models\Trip';
  /**
   * @see JumpUpDriver\Models\Vehicle
   * @var String
   */
  const VEHICLE = 'JumpUpDriver\Models\Vehicle';

}