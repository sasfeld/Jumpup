<?php

namespace Application\Util;

use JumpUpUser\Models\User;
/**
 *
 * @author slash1990
 *        
 */
class FilesUtil {
	/**
	 * The base dir for all users' files. Needs a trailing '/' so it works fine.
	 * @var String
	 */
	const BASEDIR = "public/";
	/**
	 * Path to the users' files directory.
	 * Should be relative to the BASEDIR.
	 * @var String
	 */
	const USER_FILES_DIR = "public/pics";

	/**
	 * CHMOD when creating a new folder. Handed to the mkdir() command
	 * @var int
	 */
	const CHMOD = 0777;
	
	/**
	 * Is the given file a profile pic?
	 * @var don't car
	 */
	const TYPE_PROFILE_PIC = 0;
	
	/**
	 * Get the directory for a given user's files.
	 * @param User $user the given user.
	 * @return String the path to the user's dir which is relative to the public folder.
	 */
	public static function getUsersFileDir(User $user) {
		return self::USER_FILES_DIR . "/" . $user->getId();
	}
	
	/**
	 * Move an uploaded file (by ZendForm) to the user's file directory.
	 * @param array $file an associative array as handled by $request->getFiles->...
	 * @param User $user the given user
	 * @param see constants above $type
	 * @return the new path to the profile pic.
	 */
	public static function moveUploadedFile(array $file, User $user, $type) {
		if(!is_array($file)) {
			throw ExceptionUtil::throwInvalidArgument('$file', 'array', $file);
		}
		
		$destinationDir = "";
		switch ($type) {
			case self::TYPE_PROFILE_PIC:
				$destinationDir = self::getUsersFileDir($user) . "/profile";
				break;
			default:
				throw ExceptionUtil::throwInvalidArgument('$type', 'FilesUtil constants', $type);
		}
		
		// move file and create dir if neccessary
		if(!file_exists($destinationDir)) {
			// mkdir, set recursiveley to true
			mkdir($destinationDir, self::CHMOD, true);			
		}
		$filename = $file['name'];
		$filetmpname = $file['tmp_name'];
		$path = $destinationDir . "/".$filename;
		move_uploaded_file($filetmpname, $path);		
		return $path;
	}
	
	/**
	 * Prepare the profile pic for the given user.
	 * @param User $user
	 * @param float $scale the scale factor (between 0 and 1.0). Default is 1.0 which is full size.
	 * @return String the html tag for rendering the user's profile pic.
	 */
	public static function prepareProfilePic(User $user, $scale = 1.0) {
		$picPath = self::getRealPath($user);
		return '<img src="'.$picPath.'" />';
	}
	
	/**
	 * Get the real path (the working relative path) to the given user's profile pic.
	 * @param User $user
	 * @return String the path
	 */
	public static function getRealPath(User $user) {
		$picPath = $user->getProfilePic();
		$picPath = str_replace(self::BASEDIR, "", $picPath);
		return $picPath;
	}
}

?>