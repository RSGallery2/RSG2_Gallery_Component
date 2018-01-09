<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2017-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

/**
 * Encapsule calls of image functions to use different libraries
 * @package     ${NAMESPACE}
 *
 * @since 4.3.2
 */
abstract class externalImageLib
{

	/*
    public $XXXLastName;
    public $XXXFirstName;
    public $XXXBirthDate;
	/**/

	/**
	 * image resize function
	 *
	 * @param string $source      full path of source image
	 * @param string $target      full path of target image
	 * @param int    $targetWidth width of target
	 *
	 * @return bool true if successfull, false if error
	 */
	abstract static function resizeImage($source, $target, $targetWidth);

	/**
	 * Creates a square thumbnail by first resizing and then cutting out the thumb
	 *
	 * @param string $source Full path of source image
	 * @param string $target Full path of target image
	 * @param int    $width  width of target
	 *
	 * @return bool true if successfull, false if error
	 */
	abstract static function createSquareThumb($source, $target, $width);


	/**
	 * detects if image library is available
	 *
	 * @return string user friendly string of library name and version if detected
	 *                 empty if not detected,
	 */
	// abstract static function detect();


	/*--------------------------------------------
	global functions
	--------------------------------------------*/
	static function detectExistance($ImagLibName) //ToDo: Replace in all calls with correct path ....
	{
		global $rsgConfig;

		$LibVersion = "";

		switch ($ImagLibName)
		{
			case 'gd2':

				if (extension_loaded('gd'))
				{
					if (function_exists('gd_info'))
					{
						$gdInfoArray = gd_info();
						$LibVersion  = 'gd2 ' . $gdInfoArray["GD Version"];
					}
				}
				break;

			case 'imagemagick':

				// if path exists add the final /
				$impath = $rsgConfig->get("imageMagick_path");
				$impath = $impath == '' ? '' : $impath . '/';

				@exec($impath . 'convert -version', $output, $status);
				if (!$status)
				{
					if (preg_match("/imagemagick[ \t]+([0-9\.]+)/i", $output[0], $matches))
					{
						// echo '<br>ImageMagick: ' . $matches[0];
						$LibVersion = $matches[0];
					}
					else
					{
						$LibVersion = "";
					}
				}
				break;

			case 'netpbm':
				/*+
				static function detect($shell_cmd = '', $output = '', $status = '')
				{
					@exec($shell_cmd . 'jpegtopnm -version 2>&1', $output, $status);
					if (!$status)
					{
						if (preg_match("/netpbm[ \t]+([0-9\.]+)/i", $output[0], $matches))
						{
							// echo '<br>netpbm: ' + $matches[0];
							return $matches[0];
						}
						else
						{
							return false;
						}
					}

					return true;
				}
				/**/

				break;

			default:



				break;
		}


		return $LibVersion;
	}
}

