<?php
namespace JumpUpDriver\Models;


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
    protected $numberSeats;
    /**
     * @ORM\Column(type="string")
     */
    protected $legSpace;
    /**
     * @ORM\Column(type="integer")
     */
    protected $avgSpeed;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $picPath;
    
    public function Vehicle() {
        $this->brand = "";
        $this->type = "";
        $this->legSpace = "";
        $this->picPath = "";
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
           throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }
        $this->wastage = $val;
    }
    
   /**
     * Set the number of seats
     */
    public function setNumberSeats($val) {
        $intVal = (int) $val;
        
        if(!is_int($intVal)) {
           throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }      
        $this->numberSeats = $val;
        
    }
    
  /**
     * Set the leg space
     */
    public function setLegSpace($val) {
        if(is_string($val)) {
            $this->legSpace = $val;
        }
    }
    
  /**
     * Set the avg speed
     */
    public function setAvgSpeed($val) {
        $intVal = (int) $val;        
        if(!is_int($intVal)) {
           throw ExceptionUtil::throwInvalidArgument('$val', 'int', $val);
        }       
        $this->avgSpeed = $val;        
    }
    
  /**
     * Set the path to the corresponding pic.
     */
    public function setPicPath($val) {
        if(is_string($val)) {
            $this->picPath = $val;
        }
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
    
    public function getNumberSeats() {
        return $this->numberSeats;
    }
    
    public function getLegSpace() {
        return $this->legSpace;
    }
    
    public function getAvgSpeed() {
        return $this->avgSpeed;
    }
    
    public function getPicPath() {
        return $this->picPath;
    }  
    
    
}