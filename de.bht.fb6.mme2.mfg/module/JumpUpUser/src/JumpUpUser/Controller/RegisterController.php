<?php
namespace JumpUpUser\Controller;

    
use JumpUpUser\Forms\RegistrationForm;

use Zend\Mvc\Controller\AbstractActionController;
use JumpUpUser\Forms;

class RegisterController extends AbstractActionController 
{
    /**
     * This function is as default controller.    
     */
    
    public function showformAction()
    {        
        
        $form = new RegistrationForm('register');    

          
        return array('form' => $form);
    } 
}

?>