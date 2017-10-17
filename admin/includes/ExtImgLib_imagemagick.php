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
	 * @param string $imgSrcPath      full path of source image
	 * @param string $imgDstPath      full path of target image
	 * @param int    $targetWidth width of target
	 *
	 * @return bool true if successfull, false if error
	 * @todo only writes in JPEG, this should be given as a user option
	 */
	static function resizeImage($imgSrcPath, $imgDstPath, $targetWidth)
	{
		global $rsgConfig;

		// if path exists add the final /
		$impath = $rsgConfig->get("imageMagick_path");
		$impath = $impath == '' ? '' : $impath . '/';

		$cmd = $impath . "convert -resize $targetWidth $imgSrcPath $targetWidth";
		exec($cmd, $results, $return);
		if ($return > 0)
		{
			//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_IMAGE_COULD_NOT_BE_MADE_WITH_IMAGEMAGICK').": ".$target);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_IMAGE_COULD_NOT_BE_MADE_WITH_IMAGEMAGICK') . ": " . $imgDstPath, 'error');

			return false;
		}
		else
		{
			return true;
		}
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
	static function createSquareThumb($imgSrcPath, $imgDstPath, $thumbWidth)
	{
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

		$IsCreated = external_GD2::resizeImage($imgSrcPath, $imgDstPath, $thumbWidth);

		return $IsCreated;
	}


}

