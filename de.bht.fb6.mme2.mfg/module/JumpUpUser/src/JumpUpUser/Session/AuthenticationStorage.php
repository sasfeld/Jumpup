<?php
namespace JumpUpUser\Session;

use Zend\Authentication\Storage;

class AuthenticationStorage extends Storage\Session
{
	// --------------------------------------------------- x min ?
	// TODO secounds minutes? how much
    public function setRememberMe($rememberme = 0, $time = 1209600)
    {
        if(1 == $rememberme) {
            $this->session->getManager()->rememberMe($time);
        }
    }
    
    public function forgetMe() 
    {
        $this->session->getManager()->forgetMe();
    }
}