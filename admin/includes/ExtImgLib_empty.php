<?php

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLibAbstract.php';



/**
 * GD2 handler class
 *
 * @package RSGallery2
 */
class external_empty extends externalImageLib// genericImageLib
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
	static function resizeImage($imgSrcPath, $imgDstPath, $targetWidth)
	{
		$app = JFactory::getApplication();
		$app->enqueueMessage('resizeImage called in "empty" image library', 'error');

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
	static function createSquareThumb($imgSrcPath, $imgDstPath, $thumbWidth)
	{
		$app = JFactory::getApplication();
		$app->enqueueMessage('createSquareThumb called in "empty" image library', 'error');

		return false;
	}



}

