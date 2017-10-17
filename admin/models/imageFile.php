<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author          finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

// Loaded with class require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLibAbstract.php';

/**
 * Single image model
 * Db functions
 *
 * @since 4.3.0
 */
class Rsgallery2ImageFile
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
	public function __construct($NewImageLib = null) // ToDo: Check if the name shall be given instead of libry object
	{
		global $rsgConfig;

		// ToDo: try catch
		// ToDo: ? fallback when lib is not existing any more ?

		// Image library is already defined (given by caller)
		if (!empty ($NewImageLib))
		{
			$this->ImageLib = $NewImageLib;
		}
		else
		{
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
		}
	}

	/*******************************************************************************************************
	 *
	 * !!! Moved from other file !!!
	 *
	 * ==> IMG
	 *******************************************************************************************************/


	/**
	 * Creates a display image with size from config
	 *
	 * @param $originalFileName includes path (May be todifferent path then original)
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
				JLog::add('==> createDisplayFile: "' . $imgSrcPath . '" -> "' . $imgDstPath . '"');
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
		}

		return $IsImageCreated;
	}

	/**
	 * Generic image resize function
	 * Uses in config defined grafic library
	 *
	 * @param string $imgSrcPath  full path of source image
	 * @param string $imgDstPath  full path of target image
	 * @param int    $targetWidth width of target
	 *
	 * @return bool true if successful
	 *
	 * @since 4.3.0
	 *
	 * public function resizeImage($imgSrcPath, $imgDstPath, $targetWidth)
	 * {
	 * $IsImageCreated = false;
	 *
	 * try
	 * {
	 * $IsImageCreated = $this->ImageLib->resizeImage($imgSrcPath, $imgDstPath, $targetWidth);
	 * }
	 * catch (RuntimeException $e)
	 * {
	 * $OutTxt = '';
	 * $OutTxt .= 'Error executing imageFile::resizeImage "'
	 * . '" for image source: "' . $imgSrcPath . '"<br>'
	 * . '" for image desti.: "' . $imgDstPath . '"<br>';
	 * $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
	 *
	 * $app = JFactory::getApplication();
	 * $app->enqueueMessage($OutTxt, 'error');
	 * }
	 *
	 * return $IsImageCreated;
	 * }
	 * /**/

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
				JLog::add('==> createThumbImageFile: "' . $imgSrcPath . '" -> "' . $imgDstPath . '"');
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
		}

		return $IsImageCreated;
	}


	public function moveFile2OriginalDir($srcFileName)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isMoved = false;

		// ToDo: if (JFile::exists(JPATH_DISPLAY . '/' . $basename) || JFile::exists(JPATH_ORIGINAL . '/' . $basename)) {
		try
		{
			$dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . basename($srcFileName);

			if ($Rsg2DebugActive)
			{
				JLog::add('==> moveFile2OrignalDir: "' . $srcFileName . '"');
			}

			$isMoved = rename($srcFileName, $dstFileName);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'moveFile2OrignalDir: "' . $srcFileName . '" -> "' . $dstFileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isMoved;
	}

	/*------------

		ToDo: create image libs ... and use / create following functions then

		ToDo: use ImgFile.php fur sub classes from here

	-------------*/

	/** other file class watermrk ..
	public function createWaterMarkImageFile($originalFileName)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isCreated = false;

		// if (JFile::exists(JPATH_DISPLAY . '/' . $basename) || JFile::exists(JPATH_ORIGINAL . '/' . $basename)) {
		try
		{
			$ImageLib = $this->ImageLib;

			// ToDo: make separate functions in each grafics lib
			// Actual short cut : use GD
			// Use rsgConfig to determine which image library is loaded
			$graphicsLib = $rsgConfig->get('graphicsLib');
			// Use GD even if $graphicsLib is different
			if ($graphicsLib != 'gd2')
			{
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ExtImgLib_GD.php';
				$ImageLib = new external_GD2;
			}


			$IsImageCreated = $ImageLib->resizeImage($imgSrcPath, $imgDstPath, $maxSideImage);


				$baseName    = basename($originalFileName);
			$srcFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
			$dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . '/' . $baseName;

			if ($Rsg2DebugActive)
			{
				JLog::add('==> createWatermarkFile: "' . $srcFileName . '" -> "' . $dstFileName . '"');
			}


			// seed is used ...
			// todo: copy and resize ...

			$isCreated = copy($srcFileName, $dstFileName);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'createThumbFile: "' . $srcFileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isCreated;
	}
	/**/

}
