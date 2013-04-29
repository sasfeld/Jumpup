<?php
namespace Application\View\Helper;

use Application\Util\Exception_Util;

/**
 *
 * This class save a render state within a render process in the RenderForm helper class.
 *
 * @package    Application\View\Helper
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      13.04.2013
 */
interface IRenderFormState {
  const INITALIZED = 1;
  const APPENDING = 2;
  const FINISHED = 3; 
 
  
}