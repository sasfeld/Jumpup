<?php

namespace JumpUpUser\Controller;

use JumpUpUser\Controller\ANeedsAuthenticationController;
use JumpUpUser\Forms\ProfileForm;
use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpUser\Util\Routes\IRouteStore;
use Application\Util\FilesUtil;
use JumpUpUser\Util\Messages\IControllerMessages;

/**
 *
 *
 * This controller is responsible for the user's profile and all depending actions.
 *
 * @package JumpUpPassenger\Controller
 * @subpackage
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 19.06.2013
 */
class ProfileController extends ANeedsAuthenticationController {
	private $form;
	private function _getForm() {
		if (! isset ( $this->form )) {
			$profileForm = new ProfileForm ();
			$builder = new AnnotationBuilder ();
			$this->form = $builder->createForm ( $profileForm );
			$this->form->setAttribute ( 'action', IRouteStore::CHANGE_PROFILE );
		}
		return $this->form;
	}
	
	/**
	 * This action is responsible for changing the profile.
	 * exports: a form if the request is not a POST-request or the form is invalid
	 */
	public function changeAction() {
		if ($this->_checkAuthentication ()) { // authentication required
			$loggedInUser = $this->getCurrentUser();
			
			$form = $this->_getForm (); // change profile form
			$request = $this->getRequest ();
			$messages = array ();
			
			if ($request->isPost ()) {
				// merge the post parameters for each file and each other input
				$post = array_merge_recursive ( $request->getPost ()->toArray (), $request->getFiles ()->toArray () );
				$form->setData ( $post );
				
				if ($form->isValid ()) {					
					$pathProfilePic = FilesUtil::moveUploadedFile($post[ProfileForm::FIELD_PROFILE_PIC], $loggedInUser, FilesUtil::TYPE_PROFILE_PIC);
					$birthDate = $request->getPost ( ProfileForm::FIELD_BIRTHDATE );
					$loggedInUser->setBirthdate($birthDate);
					$loggedInUser->setProfilePic($pathProfilePic);
					$this->_saveChangedUser($loggedInUser);
					array_push($messages, IControllerMessages::CHANGE_PROFILE_SUCCESS);
				}
				// else: fallthrough -> form will be rendered
			}
			
			return array (
					'form' => $form,
					'messages' => $messages 
			);
		}
	}
}

?>