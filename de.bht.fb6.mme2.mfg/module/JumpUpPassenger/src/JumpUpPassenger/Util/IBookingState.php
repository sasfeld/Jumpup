<?php
namespace JumpUpPassenger\Util;

/**
 *
 * This interface holds the BookingStates.
 * 
 * The states are used by the Booking entity.
 * 
 * States are the following:
 * start -> OFFER_FROM_PASSENGER <............................                             
 *                               -> ACCEPT                    |
 *                               -> DENY                      |
 *                               -> OFFER_FROM_DRIVER         |
 *       -> ACCEPT                                            |
 *       -> DENY                                              |
 *       -> ..................................................
 *                                                    
 *
 * @package    JumpUpPassenger\Controller
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      31.05.2013
 */
interface IBookingState {
  /**
   * The passenger makes an offer.
   */
  const OFFER_FROM_PASSENGER = 0;
  /**
   * Driver or passenger accept the offer.
   */
  const ACCEPT = 1;
  /**
   * Driver or passenger deny the offer.
   */
  const DENY = 2;
  /**
   * The driver makes an offer.
   */
  const OFFER_FROM_DRIVER = 3;
}
