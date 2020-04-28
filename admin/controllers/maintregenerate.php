<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2020 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintRegenerate.php ');
}

// ToDo: Remove following / Make own resize function
// rewrite to not use old J1,5 code....
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/img.utils.php');

jimport('joomla.application.component.controlleradmin');

/**
 *
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintRegenerate extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
	  * @since 4.3.0
    */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Checks if user has root status (is re.admin')
     *
     * @return    bool
     *
     * @since 4.3
     */
    function IsUserRoot()
    {
        $user     = JFactory::getUser();
        $canAdmin = $user->authorise('core.manage');

        return $canAdmin;
    }

    /**
	 * Samples a random display image from the specified gallery and compares dimensions against Config settings
	 *
	 * @param int $gid Gallery ID
	 *
	 * @return bool True if size has changed, false if not.
	 * @since 4.3.0
     *
	static function displaySizeChanged($gid)
	{
		global $rsgConfig;

		$gallery = rsgGalleryManager::_get($gid);
		$images  = $gallery->items();

		$RandomIdx = rand(0, count($images) - 1);
		$imageName = $images [$RandomIdx]->name;

		//$imageSize = getimagesize( imgUtils::getImgDisplayPath($imageName, true) );
		$imageSize = getimagesize(imgUtils::getImgDisplay($imageName, true));
		if ($imageSize[0] == $rsgConfig->get('image_width'))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
    /**/

    /**
     *
     *
     * @since 4.3.0
     */
	public function Cancel()
	{
		/*
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function Cancel');
		}

		$msg = 'All RSG2 Images and thumbs are deleted. ';
		// $app->redirect($link, $msg, $msgType='message');
		*/
		$msg = '';
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg);

	}

    /**
     * For given galleries regenerate the display or thumb images
     * It may be used after the sizes of images are changed in the configuration
     * ToDo: Tell user about needed time
     *
     * @since 4.3.0
     */
	function RegenerateImagesDisplay()
	{
		global $Rsg2DebugActive, $rsgConfig;

		$app   = JFactory::getApplication();
		$input = $app->input;
		$gid   = $input->get('gid', array(), 'ARRAY');
		// $forceCreate = $input->get('forceRegenerateAll', null, 'INT');

		if ($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function RegenerateImagesDisplay');
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Check credits ----------------------------------------------
		if (!$this->IsUserRoot())
		{
			// send back to maintenance
			$msg = 'Access denied';
			$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg);
		}

		//--- Is a gallery selected ?  ----------------------------------------------

		if (empty($gid))
		{
			$app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_GALLERY_SELECTED'));
			$app->redirect('index.php?option=com_rsgallery2&view=maintRegenerateImages');

			return;
		}

		//--- Each selected gallery -----------------------------------------------

		$error = 0;
		foreach ($gid as $id)
		{
			// Gallery exist
			if ($id > 0)
			{
			    // collect images of gallery
				$gallery = rsgGalleryManager::_get($id);
				$images  = $gallery->items();

				// Check if images exist
				if (count($images) == 0)
				{
					continue;
				}

				/*
				// Force creation or check old image size
				$DoCreate = $forceCreate;
				if (!$DoCreate) {
					// Peek one image for changed size
					// Check if resize is really needed on this image. It takes
					// a lot of resources when changing thumbs when dimensions did not change!
					$DoCreate = $this->thumbHasSizeChanged($id);
				}
				/**/

                //--- All Images--------------------------

				foreach ($images as $image)
				{
					// URL to orignal (or display)
					//$ImagePathOriginal = imgUtils::getImgOriginalPath($image->name, true);
					$ImagePathOriginal = imgUtils::getImgOriginal($image->name, true);

					//Get the name of the image
					$parts   = pathinfo($ImagePathOriginal);
					$newName = $parts['basename'];

					// Get the current width of the original image
					$size = getimagesize($ImagePathOriginal);
					if (!$size)
					{
						//error (no width found)
						$app->enqueueMessage(JText::sprintf('COM_RSGALLERY2_COULD_NOT_CREATE_DISPLAY_IMAGE_WITH_NOT_FOUND', $newName), $type = 'error');
						$error++;
						continue;
					}

					// the actual image width and height and its max
					$height = $size[1];
					$width  = $size[0];
					if ($height > $width)
					{
						$maxSideImage = $height;
					}
					else
					{
						$maxSideImage = $width;
					}

					// if original is wider or higher than display size, create a display image
					if ($maxSideImage > $rsgConfig->get('image_width'))
					{
						$result = imgUtils::makeDisplayImage($ImagePathOriginal, $newName, $rsgConfig->get('image_width'));
					}
					else
					{
						$result = imgUtils::makeDisplayImage($ImagePathOriginal, $newName, $maxSideImage);
					}

					// Creation of image failed ?
					if (!$result)
					{
						//	imgUtils::deleteImage( $newName );
						$app->enqueueMessage(JText::sprintf('COM_RSGALLERY2_COULD_NOT_CREATE_DISPLAY_IMAGE', $newName), $type = 'error');
						$error++;
					}
				}
			}
		}

		if ($error > 0)
		{
			$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_ERRORS_DISPLAY');
		}
		else
		{
			$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_NO_ERRORS');
		}

		// $msg = 'Regenerate display images done. '; // executeRegenerateThumbImages
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintRegenerateImages', $msg);
	}

	/**
	 * Function will regenerate thumbs for a specific gallery or set of galleries
	 * Perhaps by sampling the oldest thumb from the gallery and checking dimensions against current setting.
	 *
	 * @throws Exception
     *
     * @since 4.3.0
	 */
	function RegenerateImagesThumb()
	{
		global $Rsg2DebugActive;

		$app         = JFactory::getApplication();
		$input       = $app->input;
		$gid         = $input->get('gid', array(), 'ARRAY');
		$forceCreate = $input->get('forceRegenerateAll', null, 'INT');

		if ($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/RegenerateImagesThumb');
		}

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//--- Check credits ----------------------------------------------
		if (!$this->IsUserRoot())
		{
			// send back to maintenance
			$msg = 'Access denied';
			$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg);
		}

		//--- Gallery is selected ?  ----------------------------------------------

		if (empty($gid))
		{
			$app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_GALLERY_SELECTED'));
			$app->redirect('index.php?option=com_rsgallery2&view=maintRegenerateImages');

			return;
		}

		// Each selected gallery
		$error = 0;
		foreach ($gid as $id)
		{

			// Gallery does exist
			if ($id > 0)
			{
				$gallery = rsgGalleryManager::_get($id);
				$images  = $gallery->items();

				// Check if images exist
				if (count($images) == 0)
				{
					continue;
				}

				// Force creation or check old image size
				$DoCreate = $forceCreate;
				if (!$DoCreate)
				{
					// Peek one image for changed size
					// Check if resize is really needed on this image. It takes
					// a lot of resources when changing thumbs when dimensions did not change!
					$DoCreate = $this->thumbHasSizeChanged($id);
				}

				// No creation needed ?
				if (!$DoCreate)
				{
					$msg = $gallery->name . ': ' . JText::_('COM_RSGALLERY2_THUMBNAIL_SIZE_DID_NOT_CHANGE_REGENERATION_NOT_NEEDED');
					$app->enqueueMessage($msg);
					// $app->redirect('index.php?option=com_rsgallery2&view=maintRegenerateImages');
				}
				else
				{
					//--- All images ------------------------------
					foreach ($images as $image)
					{
						//$imageName = imgUtils::getImgOriginalPath($image->name, true);
						$imageName = imgUtils::getImgOriginal($image->name, true);
						if (!imgUtils::makeThumbImage($imageName))
						{
							//Error counter
							$error++;
							$msg = $gallery->name . ': ' . JText::_('COM_RSGALLERY2_THUMBNAIL_SIZE_DID_NOT_CHANGE_REGENERATION_NOT_NEEDED');
							$app->enqueueMessage($msg);
						}
					}
				}
			}
		}

		if ($error > 0)
		{
			$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_ERRORS');
		}
		else
		{
			$msg = JText::_('COM_RSGALLERY2_MAINT_REGEN_NO_ERRORS');
		}
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintRegenerateImages', $msg);
	}

	/**
	 * Samples a random thumb from the specified gallery and compares dimensions against Config settings
	 *
	 * @param Int $gid Gallery ID
	 *
	 * @return bool True if size has changed, false if not.
     *
     * @since 4.3.0
	 */
	static function thumbHasSizeChanged($gid)
	{
		global $rsgConfig;

		$gallery = rsgGalleryManager::_get($gid);
		$images  = $gallery->items();

		$RandomIdx = rand(0, count($images) - 1);
		$imageName = $images [$RandomIdx]->name;

		//$imageSize = getimagesize( imgUtils::getImgThumbPath($imageName, true) );
		$imageSize = getimagesize(imgUtils::getImgThumb($imageName, true));
		if ($imageSize[0] == $rsgConfig->get('thumb_width'))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

}


