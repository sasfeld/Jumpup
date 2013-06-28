<?php

namespace JumpUpUser\Controller;

use JumpUpUser\Controller\ANeedsAuthenticationController;
use JumpUpUser\Forms\ProfileForm;
use Zend\Form\Annotation\AnnotationBuilder;
use JumpUpUser\Util\Routes\IRouteStore;
use Application\Util\FilesUtil;
use JumpUpUser\Util\Messages\IControllerMessages;
use JumpUpUser\Util\UserUtil;
use Zend\Stdlib\Hydrator\ClassMethods;
use JumpUpUser\Models\User;

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
	/**
	 * Param for the userId, used in showAction.
	 * @var String
	 */
	const PARAM_USER_ID = "userId";
	
	private $form;
	private function _getForm() {
		if (! isset ( $this->form )) {
			$profileForm = new ProfileForm ();
			$builder = new AnnotationBuilder ();
			$this->form = $builder->createForm ( $profileForm );
			$this->form->setAttribute ( 'action', IRouteStore::CHANGE_PROFILE );
			$this->form->setHydrator(new ClassMethods());
		}
		return $this->form;
	}
	
	/**
	 * This action is responsible for showing the logged in user's profile.
	 * input parameter: userId if not set, the current user's profile will be rendered
	 * exports: messages from the flash messenger and the instance of the logged in user
	 */
	public function showAction() {
		$redirect = IRouteStore::LOGIN;
		if ($this->_checkAuthentication ()) { // authentication required			
			$paramUserId = $this->request->getQuery(self::PARAM_USER_ID);
			
			$user = null;
			$linkEdit = null; // if set to null, the view shouldn't render the links
			$linkVehicle = null;
			$messages = $this->flashMessenger()->getMessages();
			if(null === $paramUserId) { // show logged in user's profile				
				$user = $this->getCurrentUser();
				if(!UserUtil::isProfileConfigured($user)) {
					array_push($messages, IControllerMessages::NOT_COMPLETED_PROFILE_YET);
				}
				$linkEdit = $this->_getLinkToProfile();
				$linkVehicle = $this->_getLinkToVehicle();
			}
			else { // show profile for user with the given id
				$user = $this->_getUser($paramUserId);
				if(null === $user) {
					array_push($messages, IControllerMessages::NO_PROFILE_FOUND);
				}
			}
			
			
			return array("messages" => $messages,
					"user" => $user,
					'linkVehicle' => $linkVehicle ,
					'linkEdit' => $linkEdit,
					);
		}
		$this->redirect()->toRoute($redirect);
		
	}
	
	/**
	 * Get the user entity.
	 * @param int $paramUserId the id of the entity
	 * @return the user instance or null if no entity was matched.
	 */
	protected function _getUser($paramUserId) {
		$userId = (int) $paramUserId;
		$userService = $this->_getUserService();
		$user = $userService->getUserById($userId);
		return $user;
	}
	
	/**
	 * @return the link to the vehicle controller.
	 */
	protected function _getLinkToVehicle() {
		return \JumpUpDriver\Util\Routes\IRouteStore::LIST_VEHICLES;
	}
	
	/**
	 * @return the link to the profile controller -> changeAction.
	 */
	protected function _getLinkToProfile() {
		return IRouteStore::CHANGE_PROFILE;
	}
	
	/**
	 * Delete the given user's old profile pic if neccessary.
	 * @param User $user
	 */
	protected function _deleteOldPic(User $user) {
		$profilePicPath = $user->getProfilePic();
		if(null !== $profilePicPath && @is_file($profilePicPath)) {
			$success = unlink($profilePicPath);
			if(!$success) {
				throw \Exception(IControllerMessages::ERROR_DELETING_PROFILE_PIC);
			}
		}
	}
	
	/**
	 * This action is responsible for changing the profile.
	 * exports: a form if the request is not a POST-request or the form is invalid
	 */
	public function changeAction() {
		$redirect = IRouteStore::LOGIN;
		if ($this->_checkAuthentication ()) { // authentication required
			$loggedInUser = $this->getCurrentUser();
			
			$form = $this->_getForm (); // change profile form
			$form->bind($loggedInUser);
		
			$request = $this->getRequest ();
			$messages = array ();
			
			if ($request->isPost ()) {
				// merge the post parameters for each file and each other input
				$post = array_merge_recursive ( $request->getPost ()->toArray (), $request->getFiles ()->toArray () );
				$form->setData ( $post );
				
				if ($form->isValid ()) {	
					$this->_deleteOldPic($loggedInUser); // only if neccessary				
					$profilePic = $post[ProfileForm::FIELD_PROFILE_PIC];
					if(null !== $profilePic && FilesUtil::_getFileType($profilePic['name']) !== null) {
						$pathProfilePic = FilesUtil::moveUploadedFile($profilePic, $loggedInUser, FilesUtil::TYPE_PROFILE_PIC);
						$birthDate = $request->getPost ( ProfileForm::FIELD_BIRTHDATE );
						$homeCity = $request->getPost( ProfileForm::FIELD_HOMECITY);
						$spokenLanguages = $request->getPost( ProfileForm::FIELD_SPOKEN_LANGS);
						$loggedInUser->setBirthdate($birthDate);
						$loggedInUser->setProfilePic($pathProfilePic);
						$loggedInUser->setHomeCity($homeCity);
						$loggedInUser->setSpokenLanguages($spokenLanguages);
						$this->_saveChangedUser($loggedInUser);
						$redirect = IRouteStore::SHOW_PROFILE;
						$this->flashMessenger()->clearMessages();
						$this->flashMessenger()->addMessage(IControllerMessages::CHANGE_PROFILE_SUCCESS);
						$this->redirect()->toRoute($redirect);
					}
					else {
						array_push($messages, \JumpUpUser\Util\Messages\IControllerMessages::PROFILE_IMAGE_TYPES);
					
					}
				}
				// else: fallthrough -> form will be rendered
			}
			
			return array (
					'form' => $form,
					'messages' => $messages,
					
			);
		}		
		$this->redirect()->toRoute($redirect);
		
	}
}

?>