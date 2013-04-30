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
     * Perform an update on the DB for the given user.
     * @param User $user
     */
    public function updateUser(User $user) {      
        $this->em->merge($user); // update DB
        $this->em->flush(); 
    }
    
}