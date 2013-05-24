<?php
namespace Application\Util;

/**
 *
 * This util class offers static methods to work with arrays.
 *
 *
 * @package    Application\util
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      24.05.2013
 */
class ArrayUtil {
  /**
   * Swap the last element in an array to a given free position (an index), e.g., after you removed an element in the array.
   * @param array $arrayToSwap
   * @param int $freePosition the index of the free position.
   */
  static public function swapToFreePos(array $arrayToSwap, $freePosition) {
    $lastPos = sizeof($arrayToSwap) - 1;
    $arrayToSwap[$freePosition] = $arrayToSwap[$lastPos];
    unset($arrayToSwap[$lastPos]);
  }
}