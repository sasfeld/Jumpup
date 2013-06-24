<?php

namespace JumpUpUser\Util\View;

use Zend\Form\Form;
use Zend\Form\View\Helper\AbstractHelper;
use JumpUpUser\Models\User;
use JumpUpUser\Util\Messages\IViewMessages;
use Application\Util\FilesUtil;
use JumpUpUser\Util\UserUtil;
use JumpUpUser\Util\ServicesUtil;

/**
 *
 *
 *
 *
 *
 * This view service class offers functionalities to render information of a given user.
 * It realizes the view for the loggedIn user div and the whole profile.
 *
 * @package
 *
 *
 *
 *
 * @subpackage
 *
 *
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 19.06.2013
 */
class RenderUser extends AbstractHelper {
	/**
	 * The currently prepared String for the view.
	 *
	 * @var String
	 */
	private $renderingString;
	
	/**
	 * We follow the Zend View Helper idea here.
	 * The magic function __invoke is called by the ServiceManager (because he uses it like a function) with the expected parameter.
	 *
	 * @param
	 *        	form any User entity (the logged in user)
	 * @param
	 *        	minimal show only minimal information? Default is true.
	 * @return the String to be printed
	 */
	public function __invoke(User $user = null, $minimal = true) {
		$translator = $this->translator;
		
		// if the user is null, try to fetch it from the UserUtil
		if (null === $user) {
			$userUtil = $this->_getUserUtil();
			$user = $userUtil->getCurrentUser ();
		}
		
		$retString = "";
		// show user's info if an instance was fetched
		if (null !== $user) {			
			$retString .= "<ul>";
			$retString .= "<li>" . $user->getPrename () . " " . $user->getLastname () . "</li>";
			if (! $minimal) {				
				$eMail = $user->getEmail();
				if (null !== $eMail && "" !== $eMail) { // only render if configured
					$retString .= "<li>" . $translator->translate ( IViewMessages::EMAIL ) . ": <a href=\"mailto:\"".$eMail.'\">' . $eMail . "</a></li>";
				}
				$birthDate = $user->getBirthDate ();
				if (null !== $birthDate && "" !== $birthDate) { // only render if configured
					$retString .= "<li>" . $translator->translate ( IViewMessages::BIRTH_DATE ) . ": " . $birthDate . "</li>";
				}
				$spokenLanguages = $user->getSpokenLanguages ();
				if (null !== $spokenLanguages && "" !== $spokenLanguages) { // only render if configured
					$retString .= "<li>" . $translator->translate ( IViewMessages::SPOKEN_LANGUAGES ) . ": " . $spokenLanguages . "</li>";
				}
				$homeCity = $user->getHomeCity ();
				if (null !== $homeCity && "" !== $homeCity) { // only render if configured
					$retString .= "<li>" . $translator->translate ( IViewMessages::HOME_CITY ) . ": " . $homeCity . "</li>";
				}
				$profilePic = $user->getProfilePic ();
				if (null !== $profilePic && "" !== $profilePic) { // only render if configured
					$profilePicHtml = FilesUtil::prepareProfilePic ( $user );
					$retString .= "<li>" . $translator->translate ( IViewMessages::PROFILE_PIC ) . ": " . $profilePicHtml . "</li>";
				}
			}	
			$retString .= "</ul>";
		}
	
		
		return $retString;
	}
	private function _getViewHelper($plugin) {
		return $this->getView ()->getHelperPluginManager ()->get ( $plugin );
	}
	
	private function _getUserUtil() {
		return $this->view->getHelperPluginManager()->getServiceLocator()->get(ServicesUtil::CLASSPATH_USERUTIL);
		
	}
}

?>
