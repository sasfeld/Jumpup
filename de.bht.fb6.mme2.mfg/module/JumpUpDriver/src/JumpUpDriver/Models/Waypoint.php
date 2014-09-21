<?php

namespace JumpUpDriver\Models;
use JumpUpDriver\Util\Exception_Util;

use JumpUpDriver\Util\StringUtil;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;

/**
* @ORM\Entity
* @Table(name="waypoint")
*/
class Waypoint {
    /**     
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
    * @ORM\Column(type="string")
    */
    protected $coord;
    /**
    * @ORM\Column(type="boolean")
    */
    protected $entryPoint;
    /**
     * @ManyToOne(targetEntity="Trip", inversedBy="waypoints")
     */
    protected $parentTrip;
    
    public function setCoord($coord) {
        if(!StringUtil::isString($coord)) {
            throw Exception_Util::throwInvalidArgument('$coord', 'string', $coord);
        }
        $this->coord = $coord;
    }
    
    public function setEntryPoint($entryPoint) {
        if(!is_bool($entryPoint)) {
            throw Exception_Util::throwInvalidArgument('$coord', 'string', $coord);
        }
        $this->entryPoint = $entryPoint;
    }
    
    public function getCoord() {
        return $this->coord;
    }
    
    public function getEntryPoint() {
        return $this->entryPoint;
    }
    
    public function setParentTrip(Trip $trip) {
        $this->parentTrip = $trip;
    }
    
    public function getParentTrip() {
        return $this->parentTrip;
    }
}