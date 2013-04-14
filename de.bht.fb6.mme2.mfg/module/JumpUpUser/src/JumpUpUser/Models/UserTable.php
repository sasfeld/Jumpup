<?php
namespace JumpUpUser\Models; 
use Zend\Db\TableGateway\Exception\RuntimeException;

use JumpUpUser\Models\User;
use Zend\Db\TableGateway\TableGateway;

/**
 * 
* This class represents a database table. It offers all the @see User entities.
*
*
* @package    JumpUpUser\Models
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      13.04.2013
 */
class UserTable {    
    protected $tableGateway;

    /**
     * Constrcut a new UserTable. Should be done by a factory.
     * @param TableGateway $tableGateway the ORM gateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * Fetch all User entities.
     * @see User
     * @return a list of User entities.
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    /**
     * Check if a user exists.
     * @param String $username
     * @return true if the user exits
     */
    public function userExits($username) {
        try {
            $this->getUser($username);
            return true;
        }
        catch (RuntimeException $e) { // we except that, see getUser()
            return false;
        }
    }
    
    /**
     * Fetch a single user.
     * The user is identified by his username.
     * @param String $username
     * @throws InvalidArgumentException if the argument is not a String.
     * @throws Exception if the row couln't be found.
     */
    public function getUser($username) {
        if(!is_string($username)) {
             throw new InvalidArgumentException("The type of the parameter username must be a string! Your value was: {$username}");
        }
        $username = (string) $username;
        $rowset = $this->tableGateway->select(array('username' => $username));
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException("Could not find row $username");
        }
        return $row;
    }
    
    /**
     * Persist a User in the database.
     * @see User
     * @param User $user
     * @throws Exception if the $user doesn't have a username 
     */
    public function saveUser(User $user) {
        if(null === $user->getUsername() || "" === $user->getUsername()) {
            throw new Exception("Illegal user. He must have an username.");
        }
         $data = array( // columns in the DB to be fileld.
            'username' => $user->getUsername(),
            'prename' => $user->getPrename(),           
            'lastname'  => $user->getLastname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'confirmation_key' => $user->getConfirmationKey(),
        );

        $username = (string) $user->getUsername();      
        try { // check whether user exists
            $this->getUser($username);
            // user exists, so update the row   
            $this->tableGateway->update($data, array('username' => $username));         
        }
        catch (RuntimeException $e) { // we expect an exception -> the user doesn't exist.
            $this->tableGateway->insert($data);
        }           
    }
    
    
}