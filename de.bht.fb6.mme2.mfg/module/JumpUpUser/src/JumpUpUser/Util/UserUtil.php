<?php

namespace JumpUpUser\Util;

/**
 * 
* This non-static service/util class offers functions for general desires.
*
*
* @package    
* @subpackage 
* @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
* @license    GNU license
* @version    1.0
* @since      26.04.2013
 */
use JumpUpUser\Models\User;

use Zend\Authentication\AuthenticationService;

use Doctrine\ORM\EntityManager;
use Application\Util\ExceptionUtil;

class UserUtil {
    private $em;
    private $authService;
    
    /**
     * We need to construct the AuthService here.
     * 
     * @param EntityManager $em
     * @param AuthenticationService $authService
     */
    public function __construct(EntityManager $em, AuthenticationService $authService) {
        $this->em = $em;
        $this->authService = $authService;
    }
    
    /**
     * Get the User instance for the currenty logged in user.
	 *
     * @param EntityManager $em
     * @param AuthenticationService $authService
     */
    public function getCurrentUser() {
      if($this->authService->hasIdentity()) {
          $currentUser = $this->authService->getIdentity();
          $repoUser = $this->em->getRepository("JumpUpUser\Models\User");
          $user = $repoUser->findOneBy(array('username' => $currentUser));
          if(null !== $user) {
              return $user;
          }
      }
      return null;
    }
    
    /**
     * Get the user entity for a given id.
     * @param int $userId
     * @return the user entity which can be null if there wasn't any matching user.
     * @throws \InvalidArgumentException if the parameter isn't of type integer.
     */
    public function getUserById($userId) {
    	if(!is_int($userId)) {
    		throw ExceptionUtil::throwInvalidArgument('$userId', 'int', $userId);
    	}
    	$repoUser = $this->em->getRepository("JumpUpUser\Models\User");
    	$user = $repoUser->findOneBy(array('id' => $userId));
    	return $user;
    }
    
    /**
     * Perform an update on the DB for the given user.
     * @param User $user
     */
    public function updateUser(User $user) {      
        $this->em->merge($user); // update DB
        $this->em->flush(); 
    }
    
    /**
     * Check whether the given user has a configured / completed profile.
     * @param User $user
     * @return true if the user has configured the profile properly.
     */
    public static function isProfileConfigured(User $user) {
    	if(null === $user->getBirthDate() ||
    	   null === $user->getHomeCity() ||
    	   null === $user->getSpokenLanguages() ||
    	   null === $user->getProfilePic()) {
    		return false;
    	}
    	return true;
    }
    
}