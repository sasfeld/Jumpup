<?php

namespace JumpUpUser\Util\Messages;

/**
 *
 * @author slash1990
 *        
 */
interface IViewMessages {
	/*
	 * ..:: used in JumpUpUser\Util\View\RenderUser ::..
	*/
	const BIRTH_DATE = "Birth date";
	const HOME_CITY = "Home town";
	const SPOKEN_LANGUAGES = "Spoken languages";
	const PROFILE_PIC = "Profile picture";
	const EMAIL = "EMail address";
	/*
	 * ..:::::::::::::::::::::::::::::::::::::::::::::..
	*/
	
	/*
	 * ..:: used in view\profile\show.phtml ::..
	 */
	const LIST_VEHICLES = "Configure vehicles";
	const EDIT_PROFILE = "Edit profile";
	/*
	 * ..:::::::::::::::::::::::::::::::::::::..
	 */
	/*
	 * ..:: used in view\auth\login.phtml ::..
	 */
	const LOGIN_TITLE = "Login";
	/*
	 * ..:::::::::::::::::::::::::::::::::::::..
	 */
	/*
	 * ..:: used in view\profile\*.phtml ::..
	 */
	const PROFILE_CHANGE_TITLE = "Edit profile";
	const PROFILE_SHOW_TITLE = "Show profile";
	/*
	 * ..:::::::::::::::::::::::::::::::::::::..
	 */
	/*
	 * ..:: used in view\register\*.phtml ::..
	 */
	const REGISTER_CONFIRM_TITLE = "Confirm registration";
	const REGISTER_TITLE = "Registration";
	/*
	 * ..:::::::::::::::::::::::::::::::::::::..
	 */
	/*
	 * ..:: used in view\success\*.phtml ::..
	 */
	const SUCCESS_TITLE = "Success";
	/*
	 * ..:::::::::::::::::::::::::::::::::::::..
	 */
}

?>