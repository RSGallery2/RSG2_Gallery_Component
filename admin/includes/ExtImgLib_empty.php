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
class external_empty extends externalImageLib// genericImageLib
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
		$app = JFactory::getApplication();
		$app->enqueueMessage('resizeImage called in "empty" image library', 'error');

		return false;
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
		$app = JFactory::getApplication();
		$app->enqueueMessage('createSquareThumb called in "empty" image library', 'error');

		return false;
	}



}

