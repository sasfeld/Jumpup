<?php
namespace JumpUpDriver\Models;


use Application\Util\StringUtil;

use JumpUpDriver\Util\ExceptionUtil;

use JumpUpUser\Models\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne as OneToOne;
use Doctrine\ORM\Mapping\OneToMany as OneToMany;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;

/**
 * @ORM\Entity
 */
class Vehicle {
    /**     
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $brand;
    /**
     * @ManyToOne(targetEntity="JumpUpUser\Models\User", inversedBy="vehicles")
     */
    protected $owner;
    /**
     * @ORM\Column(type="string")
     */
    protected $type;
    /**
     * @ORM\Column(type="integer")
     */
    protected $wastage;
    /**
     * @ORM\Column(type="integer")
     */
    protected $numberseats;
    /**
     * @ORM\Column(type="string")
     */
    protected $legspace;
    /**
     * @ORM\Column(type="integer")
     */
    protected $avgspeed;
    /**
     * @ORM\Column(type="string")
     */
    protected $aircondition;
    /**
     * @ORM\Column(type="string")
     */
    protected $actualwheel;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $vehiclepic;
    /**
     * @OneToMany(targetEntity="JumpUpDriver\Models\Trip", mappedBy="vehicle")
     */
    protected $intrips;
    
    /**
     * Get all the trips in which this vehicle is referenced.
	 * @return array of Trip $intrips
	 */
	public function getIntrips() {
		return $this->intrips;
	}

	/**
     * Get the full path to the vehicle pic.
	 * @return the $vehiclepic
	 */
	public function getVehiclepic() {
		return $this->vehiclepic;
	}

	/**
	 * Set the full path to the vehicle pic.
	 * @param String $vehiclepic
	 */
	public function setVehiclepic($vehiclepic) {
		if(!is_string($vehiclepic)) {
			throw \Application\Util\ExceptionUtil::throwInvalidArgument('$vehiclepic', 'String', $vehiclepic);
		}
		$this->vehiclepic = (string) $vehiclepic;
	}

	/**
	 * @return the $aircondition
	 */
	public function getAircondition() {
		return $this->aircondition;
	}

	/**
	 * @return the $actualwheel
	 */
	public function getActualwheel() {
		return $this->actualwheel;
	}

	/**
	 * @param field_type $aircondition
	 */
	public function setAircondition($aircondition) {
		$this->aircondition = $aircondition;
	}

	/**
	 * @param field_type $actualwheel
	 */
	public function setActualwheel($actualwheel) {
		$this->actualwheel = $actualwheel;
	}

	public function Vehicle() {
        $this->brand = "";
        $this->type = "";
        $this->legSpace = "";
        $this->picPath = "";
    }
    
    public function setId($val) {
        $intVal = (int) $val;
        
        if(!is_int($intVal)) {
           throw \Application\Util\ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }
        $this->id = $intVal;
    }
    
  /**
     * Set the brand
     */
    public function setBrand($val) {
        if(is_string($val)) {
            $this->brand = $val;
        }
    }
    
  /**
     * Set the type
     */
    public function setType($val) {
        if(is_string($val)) {
            $this->type = $val;
        }
    }
    
  /**
     * Set the owner
     */
    public function setOwner(User $val) {
       $this->owner = $val;
    }   
 
    
  /**
     * Set the wastage
     */
    public function setWastage($val) {   
        $intVal = (int) $val;
        
        if(!is_int($intVal)) {
           throw \Application\Util\ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }
        $this->wastage = $intVal;
    }
    
   /**
     * Set the number of seats
     */
    public function setNumberseats($val) {
        $intVal = (int) $val;
        
        if(!is_int($intVal)) {
           throw \Application\Util\ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }      
        $this->numberseats = $intVal;
        
    }
    
  /**
     * Set the leg space
     */
    public function setLegspace($val) {
        if(is_string($val)) {
            $this->legspace = $val;
        }
    }
    
  /**
     * Set the avg speed
     */
    public function setAvgspeed($val) {
        $intVal = (int) $val;        
        if(!is_int($intVal)) {
           throw \Application\Util\ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }       
        $this->avgspeed = $intVal;        
    }
    
    
    public function getId() {
        return $this->id;
    }
    
    public function getBrand() {
        return $this->brand;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getOwner() {
        return $this->owner;
    }
    
    public function getWastage() {
        return $this->wastage;
    }
    
    public function getNumberseats() {
        return $this->numberseats;
    }
    
    public function getLegspace() {
        return $this->legspace;
    }
    
    public function getAvgspeed() {
        return $this->avgspeed;
    }
    
  
    public function toJson() {
        return array("id" => $this->getId(),
                    "ownerId" => $this->getOwner()->getId(),
                    "brand" => $this->getBrand(), 
                    "type" => $this->getType(), 
                    "legspace" => $this->getLegspace(), 
                    "wastage" => $this->getWastage(), 
                    "avgspeed" => $this->getAvgspeed(), 
                    "numberseats" => $this->getNumberSeats(),
        			"aircondtion" => $this->getAircondition(),
        		    "actualwheel" => $this->getActualwheel(),
         );
    }
    
   
    
}