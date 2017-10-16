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
	 * @todo only writes in JPEG, this should be given as a user option
	 * @todo use constants found in http://www.php.net/gd rather than numbers
	 */
	static function resizeImage($source, $target, $targetWidth)
	{
		global $rsgConfig;
		// an array of image types

		$imageTypes = array( // ToDo: check if there is there predifined array ?
			IMAGETYPE_GIF => 'gif',
			IMAGETYPE_JPEG => 'jpg',
			IMAGETYPE_PNG => 'png',
			IMAGETYPE_SWF => 'swf',
			IMAGETYPE_PSD => 'psd',
			IMAGETYPE_BMP => 'bmp',
			IMAGETYPE_TIFF_II => 'tiff',
			IMAGETYPE_TIFF_MM => 'tiff',
			IMAGETYPE_JPC => 'jpc',
			IMAGETYPE_JP2 => 'jp2',
			IMAGETYPE_JPX => 'jpx',
			IMAGETYPE_JP2 => 'jP2',
			IMAGETYPE_SWC => 'swc',
			IMAGETYPE_IFF => 'iff',
			IMAGETYPE_WBMP => 'wbmp',
			IMAGETYPE_XBM => 'xbm',
			IMAGETYPE_ICO => 'ico'
		);


		$source     = rawurldecode($source);//fix: getimagesize does not like %20
		$target     = rawurldecode($target);//fix: getimagesize does not like %20
		$imgInfo    = getimagesize($source);

		if (!$imgInfo)
		{
			//JError::raiseNotice('ERROR_CODE', $source ." ". JText::_('COM_RSGALLERY2_IS_NOT_A_VALID_IMAGE_OR_IMAGENAME'));
			JFactory::getApplication()->enqueueMessage($source . " " . JText::_('COM_RSGALLERY2_IS_NOT_A_VALID_IMAGE_OR_IMAGENAME'), 'error');

			return false;
		}
		//list( $sourceWidth, $sourceHeight, $type, $attr ) = $imgInfo;
		list($sourceWidth, $sourceHeight, $type) = $imgInfo;

		// convert $type into a usable string
		$type = $imageTypes[$type];

		// check if we can read this type of file
		if (!function_exists('imagecreatefrom' . $type))
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_GD2_DOES_NOT_SUPPORT_READING_IMAGE_TYPE').' '.$type);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_GD2_DOES_NOT_SUPPORT_READING_IMAGE_TYPE') . ' ' . $type, 'error');

			return false;
		}

		// Determine target sizes: the $targetWidth that is put in this function is actually
		// the size of the largest side of the image, with that calculate the other side:
		// - landscape: function input $targetWidth is the actual $targetWidht
		// - portrait: function input $targetWidth is the height to achieve, so switch!
		if ($sourceWidth > $sourceHeight)
		{    // landscape
			$targetHeight = ($targetWidth / $sourceWidth) * $sourceHeight;
		}
		else
		{                                // portrait or square
			$targetHeight = $targetWidth;
			$targetWidth  = ($targetHeight / $sourceHeight) * $sourceWidth;
		}

		// load source image file into a resource
		$loadImg   = "imagecreatefrom" . $type;
		$sourceImg = $loadImg($source);
		if (!$sourceImg)
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_READING_SOURCE_IMAGE').': '.$source);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_READING_SOURCE_IMAGE') . ': ' . $source, 'error');

			return false;
		}
		// create target resource
		$targetImg = imagecreatetruecolor($targetWidth, $targetHeight);

		// resize from source resource image to target
		if (!imagecopyresampled(
			$targetImg,
			$sourceImg,
			0, 0, 0, 0,
			$targetWidth, $targetHeight,
			$sourceWidth, $sourceHeight
		)
		)
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_RESIZING_IMAGE').': '.$source);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_RESIZING_IMAGE') . ': ' . $source, 'error');

			return false;
		}
		// write the image
		if (!imagejpeg($targetImg, $target, $rsgConfig->get('jpegQuality')))
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_WRITING_TARGET_IMAGE').': '.$target);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_WRITING_TARGET_IMAGE') . ': ' . $target, 'error');

			return false;
		}
		//Free up memory
		imagedestroy($sourceImg);
		imagedestroy($targetImg);

		return true;
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
		$source = rawurldecode($source);//fix: getimagesize does not like %20
		//Create a square image, based on the set width
		$t_width  = $width;
		$t_height = $width;

		//Get details on original image
		$imgdata = getimagesize($source);
		//$width_orig     = $imgdata[0];
		//$height_orig    = $imgdata[1];
		$ext = $imgdata[2];

		switch ($ext)
		{
			case 1:    //GIF
				$image = imagecreatefromgif($source);
				break;
			case 2:    //JPG
				$image = imagecreatefromjpeg($source);
				break;
			case 3:    //PNG
				$image = imagecreatefrompng($source);
				break;
		}

		$width  = $t_width;    //New width
		$height = $t_height;   //New height
		list($width_orig, $height_orig) = getimagesize($source);

		if ($width_orig < $height_orig)
		{
			$height = ($t_width / $width_orig) * $height_orig;
		}
		else
		{
			$width = ($t_height / $height_orig) * $width_orig;
		}

		//if the width is smaller than supplied thumbnail size
		if ($width < $t_width)
		{
			$width  = $t_width;
			$height = ($t_width / $width_orig) * $height_orig;;
		}

		//if the height is smaller than supplied thumbnail size
		if ($height < $t_height)
		{
			$height = $t_height;
			$width  = ($t_height / $height_orig) * $width_orig;
		}

		//Resize the image
		$thumb = imagecreatetruecolor($width, $height);
		if (!imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig))
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_RESIZING_IMAGE').": ".$source);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_RESIZING_IMAGE') . ": " . $source, 'error');

			return false;
		}
		//Create the cropped thumbnail
		$w1     = ($width / 2) - ($t_width / 2);
		$h1     = ($height / 2) - ($t_height / 2);
		$thumb2 = imagecreatetruecolor($t_width, $t_height);
		if (!imagecopyresampled($thumb2, $thumb, 0, 0, $w1, $h1, $t_width, $t_height, $t_width, $t_height))
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_CROPPING_IMAGE').": ".$source);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_CROPPING_IMAGE') . ": " . $source, 'error');

			return false;
		}

		// write the image
		if (!imagejpeg($thumb2, $target, $rsgConfig->get('jpegQuality')))
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_ERROR_WRITING_TARGET_IMAGE').": ".$target);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_ERROR_WRITING_TARGET_IMAGE') . ": " . $target, 'error');

			return false;
		}
		else
		{
			//Free up memory
			imagedestroy($thumb);
			imagedestroy($thumb2);

			return true;
		}
	}

	/**
	 * detects if gd2 image library is available
	 *
	 * @return string user friendly string of library name and version if detected
	 *                 empty if not detected,
	 */
	static function detect()
	{
		$gd2Version = '';


		parent::detect ('gd2');
		// ToDO: Move to base lib


		/*
				if(strlen ($Gd2Version) < 1) {
					// echo "<br>false";
					return false;
				}
		/**/

		return $gd2Version;
	}
}

