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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLibAbstract.php';



/**
 * GD2 handler class
 *
 * @package RSGallery2
 */
class external_netpbm extends externalImageLib// genericImageLib
{

	/**
	 * image resize function
	 *
	 * @param string $imgSrcPath      full path of source image
	 * @param string $imgDstPath      full path of target image
	 * @param int    $targetWidth width of target
	 *
	 * @return bool true if successfull, false if error
	 * @since 4.3.0
     */
	static function resizeImage($imgSrcPath, $imgDstPath, $targetWidth)
	{
		global $rsgConfig;

		$IsCreated = false;

		// if path exists add the final /
		$netpbm_path = $rsgConfig->get("netpbm_path");
		$netpbm_path = $netpbm_path == '' ? '' : $netpbm_path . '/';

		$cmd = $netpbm_path . "anytopnm $imgSrcPath | " .
			$netpbm_path . "pnmscale -width=$targetWidth | " .
			$netpbm_path . "pnmtojpeg -quality=" . $rsgConfig->get("jpegQuality") . " > $imgDstPath";
		// If anything goes wrong, the error messages are
		// returned in $output: resize is successful when !$output is true.
		@exec($cmd, $output);

		// ToDo: check if return "true" is boolean or how it returns ...
		if ($output === true)
		{
			$IsCreated = true;
		}
		else
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing external_netpbm:resizeImage:: "' . '<br>';
			// ToDo Add Arguments ...
			$OutTxt .= 'Error: "' .$output . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $IsCreated;
	}

	/**
	 * Creates a square thumbnail by first resizing and then cutting out the thumb
	 *
	 * @param string $imgSrcPath Full path of source image
	 * @param string $imgDstPath Full path of target image
	 * @param int    $thumbWidth  width of target
	 *
	 * @return bool true if successfull, false if error
	 * @since 4.3.0
     */
	static function createSquareThumb($imgSrcPath, $imgDstPath, $thumbWidth)
	{
		// ToDo: thumb type ??
		global $rsgConfig;

		// ToDo: thumb type ??

		$IsCreated = false;

		// ToDo: Check for square thumb functions and use them


		// ToDo: Check for square thumb functions and use them
		// google: ImageMagick square thumb // imagemagick crop square

		//http://superuser.com/questions/275476/square-thumbnails-with-imagemagick-convert/

		// a script which allows me to upload an image, square it and then resize it all in one "move"... Even with GD?!? Any suggestions?
		// ImageMagick will do that
		//Code:
		//convert input.jpg -thumbnail x200 -resize "200x<" -resize 50% -gravity center -crop 100x100+0+0 +repage -format jpg -quality 91 square.jpg
		// convert input.jpg -thumbnail \"100x100^\" -gravity center -crop 100x100+0+0 +repage -quality 91 crop.jpg 

		// WideImage library is very elegant and higher level PHP library for image processing.
		// it's at wideimage sourceforge net (can't post links yet)
		// "WideImage, an object-oriented PHP image library"
		// sample
		// wiImage::load('image.png')->resize(50, 30)->saveToFile('new-image.jpg', 30);

		$IsCreated = external_netpbm::resizeImage($imgSrcPath, $imgDstPath, $thumbWidth);

		return $IsCreated;

	}



}

