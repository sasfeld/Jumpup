<?php

namespace Application\Util;

use JumpUpUser\Models\User;
use JumpUpDriver\Models\Vehicle;

/**
 *
 * @author slash1990
 *        
 */
class FilesUtil {
	/**
	 * The base dir for all users' files.
	 * Needs a trailing '/' so it works fine.
	 * 
	 * @var String
	 */
	const BASEDIR = "public/";
	/**
	 * Path to the users' files directory.
	 * Should be relative to the BASEDIR.
	 * 
	 * @var String
	 */
	const USER_FILES_DIR = "public/pics";
	
	/**
	 * CHMOD when creating a new folder.
	 * Handed to the mkdir() command
	 * 
	 * @var int
	 */
	const CHMOD = 0777;
	
	/**
	 * Is the given file a profile pic?
	 * 
	 * @var don't care
	 */
	const TYPE_PROFILE_PIC = 0;
	/**
	 * Is the given file a vehicle pic?
	 * 
	 * @var don't care
	 */
	const TYPE_VEHICLE_PIC = 1;
	/**
	 * Max aimed width for each profile pic.
	 * @var int - pixels
	 */
	const PROFILE_PIC_MAX_WIDTH = 180;
	/**
	 * Max aimed width for each vehicle pic.
	 * @var int - pixels
	 */
	const VEHICLE_PIC_MAX_WIDTH = 700;
	
	const FILE_TYPE_JPEG = "jpeg";
	const FILE_TYPE_PNG = "png";
	const FILE_TYPE_GIF = "gif";
	
	/**
	 * Get the directory for a given user's files.
	 * 
	 * @param User $user
	 *        	the given user.
	 * @return String the path to the user's dir which is relative to the public folder.
	 */
	public static function getUsersFileDir(User $user) {
		return self::USER_FILES_DIR . "/" . $user->getId ();
	}
	
	/**
	 * Move an uploaded file (by ZendForm) to the user's file directory.
	 * 
	 * @param array $file
	 *        	an associative array as handled by $request->getFiles->...
	 * @param User $user
	 *        	the given user
	 * @param
	 *        	see constants above $type
	 * @param Vehicle $vehicle
	 * 			the given vehicle if the uploaded file is an vehicle. Default is null.
	 * @return the new path to the profile pic.
	 * @throws InvalidArgumentException if you user an invalid type or don't determine a vehicle although you are calling the vehicle mode.
	 */
	public static function moveUploadedFile(array $file, User $user, $type, Vehicle $vehicle = null) {
		if (! is_array ( $file )) {
			throw ExceptionUtil::throwInvalidArgument ( '$file', 'array', $file );
		}
		
		// set destination dirs depending on the type of pic
		$destinationDir = "";
		switch ($type) {
			case self::TYPE_PROFILE_PIC :
				$destinationDir = self::getUsersFileDir ( $user ) . "/profile";
				break;
			case self::TYPE_VEHICLE_PIC :
				if(null !== $vehicle) {
					// the destination dir doesn't contain the vehicle's id because we maybe didn't persist the entity at the moment of calling this method
					$destinationDir = self::getUsersFileDir ( $user ) . "/vehicle/" . $vehicle->getBrand() . "_" . $vehicle->getType();
				}
				else {
					throw ExceptionUtil::throwInvalidArgument('$vehicle', 'Vehicle', $vehicle);
				}				
				break;
			default :
				throw ExceptionUtil::throwInvalidArgument ( '$type', 'FilesUtil constants', $type );
		}
		
		// move file and create dir if neccessary
		if (! file_exists ( $destinationDir )) {
			// mkdir, set recursiveley to true
			mkdir ( $destinationDir, self::CHMOD, true );
		}
		$filename = $file ['name'];
		$filetmpname = $file ['tmp_name'];
		$path = $destinationDir . "/" . $filename;
		move_uploaded_file ( $filetmpname, $path );
		self::_resizeImage( $path, $type);
		return $path;
	}
	
	/**
	 * Resize the image immediatly. Overwrite the existing image and replace it by the resized one.
	 * @param String $path the path to the uploaded pic
	 * @param class constants $type the type (PROFILE or VEHICLE pic).
	 */
	private static function _resizeImage($path, $type) {
		$imgType = self::_getFileType($path);	
		if(null === $imgType) {
			throw ExceptionUtil::throwInvalidArgument('$path', 'file type png, gif or jpg', $path);
		}	
		
		list($imgWidth, $imgHeight) = getimagesize($path);
		$aimedWidth = null;
		switch($type) {
			case self::TYPE_PROFILE_PIC:
				$aimedWidth = self::PROFILE_PIC_MAX_WIDTH;
				break;
			case self::TYPE_VEHICLE_PIC:
				$aimedWidth = self::VEHICLE_PIC_MAX_WIDTH;
				break;
			default:
				throw ExceptionUtil::throwInvalidArgument('$type', 'type constants in FilesUtil', $type);
		}
		
		if(null !== $aimedWidth && $imgWidth > $aimedWidth) {			
			$newHeight = ($imgHeight / $imgWidth) * $aimedWidth;	

			$source = "";
			switch($imgType) {
				case self::FILE_TYPE_GIF:
					$source = imagecreatefromgif($path);
					break;
				case self::FILE_TYPE_PNG:
					$source = imagecreatefrompng($path);
					break;
				case self::FILE_TYPE_JPEG:
					$source = imagecreatefromjpeg($path);
					break;
				default:
					throw ExceptionUtil::throwInvalidArgument('$type', 'type constants in FilesUtil', $type);
			}			
			$thumb = imagecreatetruecolor($aimedWidth, $newHeight);	   			
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $aimedWidth, $newHeight, $imgWidth, $imgHeight);
			switch($imgType) {
				case self::FILE_TYPE_GIF:
					imagegif($thumb, $path);
					break;
				case self::FILE_TYPE_PNG:
					imagepng($thumb, $path);
					break;
				case self::FILE_TYPE_JPEG:
					imagejpeg($thumb, $path);
					break;
				default:
					throw ExceptionUtil::throwInvalidArgument('$type', 'type constants in FilesUtil', $type);
			}			
		}
		
			
	}
	
	/**
	 * Get the file type from the extension (.*) for a given path.
	 * @param String $path the path
	 * @return one of the FILE_TYPE_CONSTANTS above or null if we don't support the given file type.
	 */
	public static function _getFileType($path) {
		if(!is_string($path)) {
			throw ExceptionUtil::throwInvalidArgument('$path', 'String', $path);
		}
		$strpos = strpos($path, ".");
		$ext = substr($path, $strpos + 1, strlen($path) - $strpos  );
		switch($ext) {
			case "jpg":
			case "jpeg":
				return self::FILE_TYPE_JPEG;				
			case "png":
				return self::FILE_TYPE_PNG;
			case "gif":
				return self::FILE_TYPE_GIF;
			default:
				return null;
		}	
	}
	
// 	/**
// 	 * Resize the image immediatly. Overwrite the existing image and replace it by the resized one.
// 	 * @param String $path the path to the uploaded pic
// 	 * @param class constants $type the type (PROFILE or VEHICLE pic).
// 	 */
// 	private static function _resizeImage($path, $type) {
// 		$img = new \Imagick($path);
// 		$dimensions = $img->getimagegeometry();
// 		$imgWidth = $dimensions['width'];
// 		$imgHeight = $dimensions['height'];
// 		$aimedWidth = null;
// 		switch($type) {
// 			case self::TYPE_PROFILE_PIC:
// 				$aimedWidth = self::PROFILE_PIC_MAX_WIDTH;
// 				break;
// 			case self::TYPE_VEHICLE_PIC:
// 				$aimedWidth = $imgWidth;
// 				break;
// 			default:
// 				throw ExceptionUtil::throwInvalidArgument('$type', 'type constants in FilesUtil', $type);
// 		}
		
// 		if(null !== $aimedWidth) {
// 			$newHeight = $imgHeight / $aimedWidth;
// 			$img->scaleimage($aimedWidth, $newHeight);
// 			$img->writeimage($path);
// 		}
		
// 		$img->destroy();		
// 	}
	
	/**
	 * Prepare the profile pic for the given user.
	 * 
	 * @param User $user        	
	 * @param float $scale
	 *        	the scale factor (between 0 and 1.0). Default is 1.0 which is full size.
	 * @return String the html tag for rendering the user's profile pic.
	 */
	public static function prepareProfilePic(User $user, $scale = 1.0) {
		$picPath = self::getRealPath ( $user );
		return '<img src="' . $picPath . '" />';
	}
	
	/**
	 * Get the real path (the working relative path) to the given user's profile pic.
	 * 
	 * @param User $user        	
	 * @return String the path
	 */
	public static function getRealPath(User $user) {
		$picPath = $user->getProfilePic ();
		$picPath = str_replace ( self::BASEDIR, "", $picPath );
		return $picPath;
	}
	
	/**
	 * Prepare the vehicle pic for the given vehicle.
	 *
	 * @param Vehicle $vehicle
	 * @param float $scale
	 *        	the scale factor (between 0 and 1.0). Default is 1.0 which is full size.
	 * @return String the html tag for rendering the vehicle pic.
	 */
	public static function prepareVehiclePic(Vehicle $vehicle, $scale = 1.0) {
		$picPath = self::getRealVehiclePath($vehicle );
		return '<img src="' . $picPath . '" />';
	}
	
	/**
	 * Get the real path (the working relative path) to the given vehicle.
	 * 
	 * @param Vehicle $vehicle       	
	 * @return String the path or null if no pic is available
	 */
	public static function getRealVehiclePath(Vehicle $vehicle) {
		$picPath = $vehicle->getVehiclepic ();
		if (null !== $picPath && "" !== $picPath) {
			$picPath = str_replace ( self::BASEDIR, "", $picPath );
			return $picPath;
		}
		return null;
	}
}

?>