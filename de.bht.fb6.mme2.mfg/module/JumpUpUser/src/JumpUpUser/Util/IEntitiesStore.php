<?php
namespace JumpUpUser\Util;
/**
 *
 * This interface stores the so-called "repositories" in the entity manager.
 * A repo is nothing more than a fully-qualified path to a model/entity.
 *
 * @package    JumpUpPassenger\Util
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      07.06.2013
 */
interface IEntitiesStore {
  /**
   * @see JumpUpUser\Models\User
   * @var String
   */
  const USER = "JumpUpUser\Models\User";
}