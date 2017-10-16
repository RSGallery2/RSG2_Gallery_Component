<?php

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLibAbstract.php';



/**
 * GD2 handler class
 *
 * @package RSGallery2
 */
class external_GD2 extends externalImageLib// genericImageLib
{

	/**
	 * image resize function
	 *
	 * @param string $source      full path of source image
	 * @param string $target      full path of target image
	 * @param int    $targetWidth width of target
	 *
	 * @return bool true if successfull, false if error
	 */
	static function resizeImage($source, $target, $targetWidth)
	{

		return false;
	}

	/**
	 * Creates a square thumbnail by first resizing and then cutting out the thumb
	 *
	 * @param string $source Full path of source image
	 * @param string $target Full path of target image
	 * @param int    $width  width of target
	 *
	 * @return bool true if successfull, false if error
	 */
	static function createSquareThumb($source, $target, $width)
	{
		global $rsgConfig;

		return false;
	}

	/**
	 * detects if gd2 image library is available
	 *
	 * @return string user friendly string of library name and version if detected
	 *                 empty if not detected,
	 */
	static function detect()
	{
		return ""; // ? Dummy name ?
		/**/
	}


}

