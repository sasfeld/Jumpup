<?php 
namespace JumpUpUser\Models;
use Zend\Db\TableGateway\TableGateway;
 

/**
 * 
* This is an entity class for the User.
*
* The user information is stored here.
* 
* It's meant to be used as ObjectProperty (for data binding). Each property is expected to be a String. InvalidArgumentExceptions will be thrown if you try to place another type.
*
* @package    JumpUpUser\Models
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
use Application\Util\String_Util;

class User {
    /*
     * the properties must be public unfortunately. 
     * otherwise, there will be no data binding possible.
     */
    /**
     * 
     * property username
     * @var String
     */
    public $username;
    /**
     * 
     * property prename
     * @var String
     */
    public $prename;
    /**
     * 
     * property lastname
     * @var String
     */
    public $lastname;
    /**
     * 
     * property eMail
     * @var String
     */
    public $email;
    /**
     * 
     * property password. Please ensure that the password is encrypted.
     * @var String
     */
    public $password;
    /**
     * property confirmation_state (did the user confirm is registration).
     * @var boolean.
     */
    public $confirmation_state;
    
    
    public function User() {
        $this->username = "";
        $this->prename = "";
        $this->lastname = "";
        $this->eMail = "";
        $this->password = "";
    }
    
    /**
     * This method is used by the TableGateway instance to map the columns to our properties.
     * @see TableGateway
     * @param array $data an array of data provided by the TableGateway
     */
    public function exchangeArray(array $data)
    {
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->prename = (isset($data['prename'])) ? $data['prename'] : null;
        $this->lastname = (isset($data['lastname'])) ? $data['lastname'] : null;
        $this->eMail   = (isset($data['email'])) ? $data['email'] : null;
        $this->password   = (isset($data['password'])) ? $data['password'] : null;      
        $this->confirmation_state = (isset($data['confirmation_state'])) ? $data['confirmation_state'] : null;      
    }
    
    /**
     * Set the username.
     */
    public function setUsername($username) {
        if(is_string($username)) {
            $this->username = $username;
        }
    }
    
    /**
     * Enter description here ...
     * @param String $prename
     */
    public function setPrename($prename) {
        if(is_string($prename)) {
            $this->prename = $prename;
        }
    }
    
    /**
     * 
     * Enter description here ...
     * @param String $lastname
     */
    public function setLastname($lastname) {
        if(is_string($lastname)) {
            $this->lastname = $lastname;
        }
    }
    
    /**
     * 
     * Enter description here ...
     * @param String $eMail
     */
    public function setEmail($eMail)  {
        if(is_string($eMail)) {
            $this->email = $eMail;
        }
    }
    
    /**
     * 
     * Enter description here ...
     * @param String $password
     */
    public function setPassword($password) {
        if(is_string($password)) {
            $this->password = $password;
        }
    }
     /**
     * 
     * Enter description here ...
     * @param boolean $password
     */
    public function setConfirmationState($confirmationState) {
        if(is_bool($confirmationState)) {
            $this->confirmation_state = $confirmationState;
        }
    }
    
    /**
     * @return boolean
     */
    public function getConfirmationState() {
        return $this->confirmation_state;
    }
    
    /**
     * @return String     
     */
    public function getUsername() {
        return $this->username;
    }
    
     /**
     * @return String     
     */
    public function getPrename() {
        return $this->prename;
    }
    
     /**
     * @return String     
     */
    public function getLastname() {
        return $this->lastname;
    }
    
     /**
     * @return String     
     */
    public function getEmail() {
        return $this->email;
    }
    
     /**
     * @return String     
     */
    public function getPassword() {
        return $this->password;
    }   
    
    /*
     * see php doc
     */
    public function __toString() {
        /**return StringUtil::generateToString(get_class($this), array(
            'username' => $this->username,
            'prename' => $this->prename,
            'lastname' => $this->lastname,
            'eMail' => $this->eMail));    */
    }
    
    
    
}


?>