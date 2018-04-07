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

use Joomla\Image\Image;

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
	 * If memory of image not given it creates and destroys the created image
	 *
	 * @param string $originalFileName includes path (May be a different path then the original)
	 * @param Jimage $memImage
	 *
	 * @return Jimage|bool|null if successful returns resized image handler
	 *
	 * @throws Exception
	 * @since 4.3.0
	 */
	public function createDisplayImageFile($originalFileName = '', $memImage = null)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsImageCreated = false;
		$IsImageLocal = false;

		try
		{
			$baseName    = basename($originalFileName);
			$imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $baseName . '.jpg';
			
			if ($Rsg2DebugActive)
			{
				JLog::add('==> start createDisplayImageFile from: "' . $originalFileName . '" -> "' . $imgDstPath . '"');
			}

			// Create memory image if not given
			//if ($memImage == null)
			if (empty ($memImage))
			{
				$IsImageLocal = True;
				$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
				$memImage = new JImage ($imgSrcPath);
			}

			// Make sure the resource handle is valid.
			if (!$memImage->isLoaded())
			{
				throw new \LogicException('No valid image was loaded.');
			}

			//---- target size -------------------------------------

			// target width
			$targetWidth = $rsgConfig->get('image_width');
			// source sizes
			$imgHeight = $memImage->getHeight();
			$imgWidth  = $memImage->getWidth();

			if ($imgWidth > $imgHeight)
			{
				// landscape
				$width = $imgWidth;
				$height = ($targetWidth / $imgWidth) * $imgHeight;
			}
			else
			{
				// portrait or square
				$width  = ($targetWidth / $imgHeight) * $imgWidth;
				$height = $targetWidth;
			}


			//--- Resize and save -----------------------------------

			$IsImageCreated = $memImage->resize ($width, $height, false, jimage::SCALE_INSIDE);
			if (!empty($IsImageCreated))
			{
				//--- Resize and save -----------------------------------
				$type = IMAGETYPE_JPEG;
				$memImage->toFile($imgDstPath, $type);;
			}

			// Release memory if created locally
			if ($IsImageLocal)
			{
				if (!empty($IsImageCreated))
				{
					$IsImageCreated = True;
				}
				$memImage->destroy();
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
			JLog::add('<== Exit createDisplayImageFile: ' . (($IsImageCreated) ? 'true' : 'false'));
		}

		return $IsImageCreated;
	}

	/**
	 * Creates a thumb image with size from config
	 * THe folder used is either orignal or display image.
	 * One of these must exist
	 * If memory of image not given it creates and destroys the created image
	 *
	 * @param string $originalFileName includes path (May be a different path then the original)
	 *
	 * @param Jimage $memImage
	 *
	 * @return Jimage if successful
	 *
	 * @throws Exception
	 * @since 4.3.0
	 */
	public function createThumbImageFile($originalFileName = '', $memImage = null)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$IsImageCreated = false;
		$IsImageLocal = false;

		try
		{
			$baseName    = basename($originalFileName);
			$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $baseName;
			if (! file_exists ($imgSrcPath))
			{
				$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $baseName;
			}
			$imgDstPath = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/' . $baseName . '.jpg';

			if ($Rsg2DebugActive)
			{
				JLog::add('==>start createThumbImageFile: "' . $imgSrcPath . '" -> "' . $imgDstPath . '"');
			}

			// Create memory image if not given
			//if ($memImage == null)
			if (empty ($memImage))
			{
				$IsImageLocal = True;
				$memImage = new JImage ($imgSrcPath);
			}

			// Make sure the resource handle is valid.
			if (!$memImage->isLoaded())
			{
				throw new \LogicException('No valid image was loaded.');
			}

			//---- target size -------------------------------------

			$thumbWidth = $rsgConfig->get('thumb_width');
			// source sizes
			$imgHeight = $memImage->getHeight();
			$imgWidth  = $memImage->getWidth();

			// ToDo: Use thumb styles from Joomla jimage
			// 0->PROPORTIONAL 1->SQUARE
			$thumbStyle = $rsgConfig->get('thumb_style');

			// Is thumb style square // ToDo: Thumb style -> enum  // ToDo: general: Config enums
			if ($thumbStyle == 1)
			{
				$width = $thumbWidth;
				$height = $thumbWidth;
			}
			else
			{
				// ??? $thumbWidth should be max ????
				if ($imgWidth > $imgHeight)
				{
					// landscape
					$width  = $imgWidth;
					$height = ($thumbWidth / $imgWidth) * $imgHeight;
				}
				else
				{
					// portrait or square
					$width  = ($thumbWidth / $imgHeight) * $imgWidth;
					$height = $thumbWidth;
				}
			}

			//--- create thumb and save directly -----------------------------------

			//$thumbSizes = array( '50x50', '250x100' );
			//$thumbSizes = array (str($width) . 'x' . str($height));
			$thumbSizes = array ('250x100');

//			$thumbsFolder = dirname($destFile) . DIRECTORY_SEPARATOR . 'thumbs';
//			// create thumbs resizing with forced proportions
//			$createdThumbs = createThumbs($destFile, $thumbSizes, $thumbsFolder, 1);

			$creationMethod = Jimage::SCALE_INSIDE;
//			$IsImageCreated = $memImage->createThumbs($thumbSizes, $creationMethod, $thumbsFolder, );

			// generateThumbs successfully ?
			if ($thumbs = $memImage->generateThumbs($thumbSizes, $creationMethod))
			{
				// Parent image properties
				$imgProperties = JImage::getImageFileProperties($imgSrcPath);

				foreach ($thumbs as $thumb)
				{
//					// Get thumb properties
//					$thumbWidth     = $thumb->getWidth();
//					$thumbHeight    = $thumb->getHeight();
//
//					// Generate thumb name
//					$filename       = pathinfo($this->getPath(), PATHINFO_FILENAME);
//					$fileExtension  = pathinfo($this->getPath(), PATHINFO_EXTENSION);
//					$thumbFileName  = $filename . '_' . $thumbWidth . 'x' . $thumbHeight . '.' . $fileExtension;
//
//					// Save thumb file to disk
//					$thumbFileName = $thumbsFolder . '/' . $thumbFileName;

					//if ($thumb->toFile($thumbFileName, $imgProperties->type))
					if ($thumb->toFile($imgDstPath, $imgProperties->type))
					{
						$IsImageCreated = True;
//						// Return Image object with thumb path to ease further manipulation
//						$thumb->path = $thumbFileName;
//						$thumbsCreated[] = $thumb;
					}
				}
			}

			// Release memory if created locally
			if ($IsImageLocal)
			{
				if (!empty($IsImageCreated))
				{
					$IsImageCreated = True;
				}
				$memImage->destroy();
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
			JLog::add('<== Exit createThumbImageFile: ' . (($IsImageCreated) ? 'true' : 'false'));
		}

		return $IsImageCreated;
	}

    // ToDo: add gallery ID as parameter for sub folder or sub folder itself ...
	/**
	 * @param $uploadPathFileName
	 * @param $singleFileName
	 * @param $galleryId
	 *
	 * @return bool
	 *
	 * @since 4.3.0
	 */
    public function moveFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isMoved = false;

        try
        {
            if ($Rsg2DebugActive)
            {
                JLog::add('==>start moveFile2OrignalDir: ');
                JLog::add('    uploadPathFileName: "' . $uploadPathFileName . '"');
                JLog::add('    singleFileName: "' . $singleFileName . '"');
            }


		if (true) {

            $dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/'  .  $singleFileName;

            if ($Rsg2DebugActive)
            {
                JLog::add('    dstFileName: "' . $dstFileName . '"');
            }

// return $isMoved;

            $isMoved = move_uploaded_file($uploadPathFileName, $dstFileName);
        }
        }
        catch (RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'moveFile2OrignalDir: "' . $uploadPathFileName . '" -> "' . $dstFileName . '"<br>';
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
		    JLog::add('<== Exit moveFile2OriginalDir: ' . (($isMoved) ? 'true' : 'false'));
	    }

	    return $isMoved;
    }

    // ToDo: add gallery ID as parameter for sub folder or sub folder itself ...
	/**
	 * @param $srcFileName
	 * @param $dstFileName
	 * @param $galleryId
	 *
	 * @return bool
	 *
	 * @since 4.3.0
	 */
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
		    JLog::add('<== Exit copyFile2OrignalDir: ' . (($isCopied) ? 'true' : 'false'));
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

            // Delete filename like original0817254a99efa36171c98a96a81c7214.jpg
            $imgPath = JPATH_ROOT . $rsgConfig->get('imgPath_watermarked') . '/' . $imageName;
            $IsImageDeleted = $this->DeleteImage($imgPath);
            if (!$IsImageDeleted)
            {
                // $IsImagesDeleted = false;
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
			JLog::add('<== Exit deleteImgItemImages: ' . (($IsImagesDeleted) ? 'true' : 'false'));
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

	/**
	 * Moves the file to rsg...Original and creates all RSG2 images
	 * @param $uploadPathFileName
	 * @param $singleFileName
	 * @param $galleryId
	 *
	 * @return array
	 *
	 * @since 4.3.0
	 */
	public function MoveImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start MoveImageAndCreateRSG2Images: (Imagefile)');
			JLog::add('    $uploadPathFileName: "' . $uploadPathFileName . '"');
			JLog::add('    $singleFileName: "' . $singleFileName . '"');
		}

//		if (false) {
		$urlThumbFile = '';
		$isMoved = false; // successful images
		$msg = '';

		try {
			$singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;
			if ($Rsg2DebugActive)
			{
				JLog::add('    $singlePathFileName: "' . $singlePathFileName . '"');
				$Empty = empty ($this);
				JLog::add('    $Empty: "' . $Empty . '"');
			}

			// return array($isMoved, $urlThumbFile, $msg); // file is moved

			$isMoved = $this->moveFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId);

			if (true) {

				if ($isMoved)
				{
					list($isMoved, $urlThumbFile, $msg) = $this->CreateRSG2Images($singleFileName, $galleryId);
				}
				else
				{
					// File from other user may exist
					// lead to upload at the end ....
					$msg .= '<br>' . 'Move for file "' . $singleFileName . '" failed: Other user may have tried to upload with same name at the same moment. Please try again or with different name.';
				}
			}
		}
		catch (RuntimeException $e)
		{
			if ($Rsg2DebugActive)
			{
				JLog::add('MoveImageAndCreateRSG2Images: RuntimeException');
			}

			$OutTxt = '';
			$OutTxt .= 'Error executing MoveImageAndCreateRSG2Images: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit MoveImageAndCreateRSG2Images: '
				. (($isMoved) ? 'true' : 'false')
				. ' Msg: ' . $msg);
		}

		return array($isMoved, $urlThumbFile, $msg); // file is moved
	}

	/**
	 *
	 * @param $uploadPathFileName
	 * @param $singleFileName
	 * @param $galleryId
	 *
	 * @return array
	 *
	 * @since 4.3.0
	 */
	public function CreateRSG2Images($singleFileName, $galleryId)//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		$urlThumbFile = '';
		$msg = ''; // ToDo: Raise errors instead

		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start CreateRSG2Images: ' . $singleFileName );
		}


		$isCreated = false; // successful images

		// ToDo: try ... catch

		// file exists
		$singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;
		if (JFile::exists($singlePathFileName))
		{
			// Create memory image

			$imageOriginal = new JImage ($singlePathFileName);

			//--- Create display  file ----------------------------------

			$isCreated = $this->createDisplayImageFile($singlePathFileName, $imageOriginal);
			if (!$isCreated)
			{
				//
				$msg .= '<br>' . 'Create Display File for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
			}
			else
			{   // Display file is created

				//--- Create thumb file ----------------------------------

				$isCreated = $this->createThumbImageFile($singlePathFileName, $imageOriginal);
				if (!$isCreated)
				{
					//
					$msg .= '<br>' . 'Create Thumb File for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
				}
				else
				{
					// Create URL for thumb
					$urlThumbFile = JUri::root() . $rsgConfig->get('imgPath_thumb') . '/' . $singleFileName . '.jpg';

					//--- Create watermark file ----------------------------------

					$isWatermarkActive = $rsgConfig->get('watermark');
					if (!empty($isWatermarkActive))
					{
						//$modelWatermark = $this->getModel('ImgWaterMark');
						$modelWatermark = $this->getInstance('imgwatermark', 'RSGallery2Model');

						$isCreated = $modelWatermark->createMarkedFromBaseName(basename($singlePathFileName), 'original');
						if (!$isCreated)
						{
							//
							$msg .= '<br>' . 'Create Watermark File for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
						}
					}
					else
					{
						// successful transfer
						$isCreated = true;
					}
				}
			} // display file

		}
		else
		{
			$OutTxt = ''; // ToDo: Raise errors instead
			$OutTxt .= 'CreateRSG2Images Error. Could not find original file: "' . $singlePathFileName . '"';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');

			if ($Rsg2DebugActive)
			{
				JLog::add($OutTxt);
			}
		}

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit CreateRSG2Images: '
				. (($isCreated) ? 'true' : 'false')
				. ' Msg: ' . $msg);
		}

		return array($isCreated, $urlThumbFile, $msg); // file is moved
	}


}
