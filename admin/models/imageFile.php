<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

/**
 * Single image model
 * Db functions
 *
 * @since 4.3.0
 */
class rsgallery2ModelImageFile extends JModelList // JModelAdmin
{
	/**
	 * @var  externalImageLib contains external image library handler
	 */
	public $ImageLib = null;

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since 4.3.0
	 */
	public function __construct()
	{
		global $rsgConfig, $Rsg2DebugActive;

		parent::__construct();

		// ToDo: try catch
		// ToDo: ? fallback when lib is not existing any more ?
		
		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start __construct');
		}

		// Use rsgConfig to determine which image library to load
		$graphicsLib = $rsgConfig->get('graphicsLib');
		switch ($graphicsLib)
		{
			case 'gd2':
				// return GD
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_GD.php';
				$this->ImageLib = new external_GD2;
				break;
			case 'imagemagick':
				//return ImageMagick
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_imagemagick.php';
				$this->ImageLib = new external_imagemagick;
				break;
			case 'netpbm':
				//return Netpbm
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_netpbm.php';
				$this->ImageLib = new external_netpbm;
				break;
			default:
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_Empty.php';
				$this->ImageLib = new external_empty;
				//JError::raiseNotice('ERROR_CODE', JText::_('COM_RSGALLERY2_INVALID_GRAPHICS_LIBRARY') . $rsgConfig->get( 'graphicsLib' ));
				JFactory::getApplication()->enqueueMessage(JText::_('COM_RSGALLERY2_INVALID_GRAPHICS_LIBRARY') . $rsgConfig->get('graphicsLib'), 'error');

				return false;
		}
		
        if ($Rsg2DebugActive)
        {
            JLog::add('<==Exit __construct: ');
        }

	}


	/**
	 * Creates a display image with size from config
	 *
	 * @param string $originalFileName includes path (May be a different path then the original)
	 *
	 * @return bool  true if successful
	 *
	 * @since 4.3.0
	 */
	public function createDisplayImageFile($originalFileName)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsImageCreated = false;

		try
		{
			$baseName    = basename($originalFileName);
			$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
			$imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $baseName . '.jpg';

			if ($Rsg2DebugActive)
			{
				JLog::add('==> start createDisplayImageFile: "' . $imgSrcPath . '" -> "' . $imgDstPath . '"');
			}

			$width  = getimagesize($imgSrcPath);
			$height = $width[1];
			$width  = $width[0];
			if ($height > $width)
			{
				$maxSideImage = $height;
			}
			else
			{
				$maxSideImage = $width;
			}

			$userWidth = $rsgConfig->get('image_width');

			// if original is wider or higher than display size, create a display image
			if ($maxSideImage > $userWidth)
			{
				$IsImageCreated = $this->ImageLib->resizeImage($imgSrcPath, $imgDstPath, $userWidth);
			}
			else
			{
				$IsImageCreated = $this->ImageLib->resizeImage($imgSrcPath, $imgDstPath, $maxSideImage);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing createDisplayImageFile for image name: "' . $originalFileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}

		}

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit createDisplayImageFile: ');
		}

		return $IsImageCreated;
	}

	/**
	 * Creates a thumb image with size from config
	 *
	 * @param $originalFileName
	 *
	 * @return bool true if successful
	 *
	 * @since 4.3.0
	 */
	public function createThumbImageFile($originalFileName)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsImageCreated = false;

		try
		{
			$thumbWidth = $rsgConfig->get('thumb_width');

			$baseName    = basename($originalFileName);
			$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
			$imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $baseName . '.jpg';

			if ($Rsg2DebugActive)
			{
				JLog::add('==>start createThumbImageFile: "' . $imgSrcPath . '" -> "' . $imgDstPath . '"');
			}

// ??			$IsImageCreated = $this->ImageLib->createSquareThumb($imgSrcPath, $imgDstPath, $thumbWidth);

			// Is thumb style square // ToDo: Thumb style -> enum  // ToDo: general: Config enums
			if ($rsgConfig->get('thumb_style') == 1)
			{
				$IsImageCreated = $this->ImageLib->createSquareThumb($imgSrcPath, $imgDstPath, $thumbWidth);
			}
			else
			{
				$IsImageCreated = $this->ImageLib->resizeImage($imgSrcPath, $imgDstPath, $thumbWidth);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing createThumbImageFile for image name: "' . $originalFileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}
		}

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit createThumbImageFile: ');
		}

		return $IsImageCreated;
	}

    // ToDo: add gallery ID as parameter for sub folder or sub folder itself ...
    public function moveFile2OriginalDir($uploadFileName, $singleFileName, $galleryId)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isMoved = false;

        try
        {
            if ($Rsg2DebugActive)
            {
                JLog::add('==>start moveFile2OrignalDir: "' . $singleFileName . '"');
            }

		if (true) {

            $dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/'  .  $singleFileName;

            if ($Rsg2DebugActive)
            {
                JLog::add('    uploadFileName: "' . $uploadFileName . '"');
            }

            $isMoved = move_uploaded_file($uploadFileName, $dstFileName);
        }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'moveFile2OrignalDir: "' . $uploadFileName . '" -> "' . $dstFileName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive)
            {
                JLog::add($OutTxt);
            }
        }

	    if ($Rsg2DebugActive)
	    {
		    JLog::add('<== Exit moveFile2OriginalDir: ');
	    }

	    return $isMoved;
    }

    // ToDo: add gallery ID as parameter for sub folder or sub folder itself ...
    public function copyFile2OriginalDir($srcFileName, $dstFileName, $galleryId)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isCopied = false;

        try
        {
	        $dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/'  .  $dstFileName;

            if ($Rsg2DebugActive)
            {
                JLog::add('==> start copyFile2OrignalDir: "' . $dstFileName . '"');
            }

            $isCopied = JFile::copy($srcFileName, $dstFileName);
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'copyFile2OrignalDir: "' . $srcFileName . '" -> "' . $dstFileName . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = JFactory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');

            if ($Rsg2DebugActive)
            {
                JLog::add($OutTxt);
            }
        }

	    if ($Rsg2DebugActive)
	    {
		    JLog::add('<== Exit copyFile2OrignalDir: ');
	    }

	    return $isCopied;
    }

    // create watermark -> watermark has separate class


	/**
	 * Deletes all children of given file name of RSGallery image item
	 * (original, display, thumb and watermarked representation)
	 *
	 * @param string $imageName Base filename for images to be deleted
	 * @return bool True on success
	 *
	 * @since 4.3.0
	 */
	public function deleteImgItemImages($imageName)
	{
		global $rsgConfig, $Rsg2DebugActive;

		$IsImagesDeleted = false;

		try
		{
			$IsImagesDeleted = true;

			if ($Rsg2DebugActive)
			{
				JLog::add('==> start deleteImgItemImages: "' . $imageName .'"');
			}

			// Delete existing images
			$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $imageName;
			$IsImageDeleted = $this->DeleteImage($imgPath);
			if (!$IsImageDeleted)
			{
				$IsImagesDeleted = false;
			}

			$imgPath        = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $imageName . '.jpg';
			$IsImageDeleted = $this->DeleteImage($imgPath);
			if (!$IsImageDeleted)
			{
				$IsImagesDeleted = false;
			}

			$imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $imageName . '.jpg';;
			$IsImageDeleted = $this->DeleteImage($imgPath);
			if (!$IsImageDeleted)
			{
				$IsImagesDeleted = false;
			}

			// ToDo: Create filename like original0817254a99efa36171c98a96a81c7214.jpg
			// destination  path file name
			$watermarkFilename = ImgWatermarkNames::createWatermarkedPathFileName($imageName, 'original');
			$IsWatermarkDeleted = $this->DeleteImage($watermarkFilename);
			if (!$IsWatermarkDeleted)
			{
				$watermarkFilename = ImgWatermarkNames::createWatermarkedPathFileName($imageName, 'display');
				$IsWatermarkDeleted = $this->DeleteImage($watermarkFilename);
				if (!$IsWatermarkDeleted)
				{

				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing deleteRowItemImages: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit deleteImgItemImages: ');
		}

		return $IsImagesDeleted;
	}

	/**
	 * Delete given file
	 * @param string $filename
	 *
	 * @return bool True on success
	 *
	 * @since 4.3.2
	 */
	private function DeleteImage($filename='')
	{
		global $Rsg2DebugActive;

		$IsImageDeleted = true;

		try
		{
			if (file_exists($filename))
			{
				$IsImageDeleted = unlink($filename);
			}
			else
			{
				// it is not existing so it may be true
				$IsImageDeleted = true;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing DeleteImage for image name: "' . $filename . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}

		}

		return $IsImageDeleted;
	}


}
