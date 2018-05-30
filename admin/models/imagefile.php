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

use Joomla\Filesystem\Folder;
use Joomla\Image\Image;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/ImgWatermarkNames.php';

/**
 * Handles files of images with actions like
 * Creating Thumb, watermarked and turning and flipping of images
 *
 * @since 4.3.0
 */
class rsgallery2ModelImageFile extends JModelList // JModelAdmin
{
	/**
	 * Constructor.
	 *
	 * @since 4.3.0
	 */
	public function __construct()
	{
		global $rsgConfig, $Rsg2DebugActive;

		parent::__construct();

		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start __construct ImageFile');
		}

	}

	/**
	 * Creates a display image with size from config
	 * If memory of image not given it creates and destroys the created image
	 *
	 * @param string $originalFileName includes path (May be a different path then the original)
	 * @param  image $memImage
	 *
	 * @return image|bool|null if successful returns resized image handler
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
				$memImage = new image ($imgSrcPath);
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

			$IsImageCreated = $memImage->resize ($width, $height, false, image::SCALE_INSIDE);
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
	 * @param image $memImage
	 *
	 * @return image if successful
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
				$memImage = new image ($imgSrcPath);
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

			// ToDo: Use thumb styles from Joomla image
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

			//$thumbSizes = array ('250x100');
			$thumbSizes = array (str($width) . 'x' . str($height));

			$creationMethod = image::SCALE_INSIDE;

			// generateThumbs successfully ?
			if ($thumbs = $memImage->generateThumbs($thumbSizes, $creationMethod))
			{
				// Parent image properties
				$imgProperties = Image::getImageFileProperties($imgSrcPath);

				foreach ($thumbs as $thumb)
				{
					if ($thumb->toFile($imgDstPath, $imgProperties->type))
					{
						$IsImageCreated = True;
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

	/**
	 * Move given file to rsgallery2 original directory
	 *
	 * @param string $uploadPathFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return bool success
	 *
	 * @since 4.3.0
	 * @throws Exception
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

            $dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/'  .  $singleFileName;

            if ($Rsg2DebugActive)
            {
                JLog::add('    dstFileName: "' . $dstFileName . '"');
            }

	        $isMoved = JFile::move($uploadPathFileName, $dstFileName);
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
	 * @param string $srcFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return bool success
	 *
	 * @since 4.3.0
	 * @throws Exception
	 */
    public function copyFile2OriginalDir($srcFileName, $singleFileName, $galleryId)
    {
        global $rsgConfig;
        global $Rsg2DebugActive;

        $isCopied = false;

        try
        {
	        $dstFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/'  .  $singleFileName;

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
	 * @throws Exception
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
	 * Moves the file to rsg...Original path and creates all RSG2 images
	 *
	 * @param string $uploadPathFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
	 *
	 * @since 4.3.0
	 * @throws Exception
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
	 * Moves the file to rsg...Original and creates all RSG2 images
	 *
	 * @param string $uploadPathFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
	 *
	 * @since 4.3.0
	 * @throws Exception
	 */
	public function CopyImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start CopyImageAndCreateRSG2Images: (Imagefile)');
			JLog::add('    $uploadPathFileName: "' . $uploadPathFileName . '"');
			JLog::add('    $singleFileName: "' . $singleFileName . '"');
		}

//		if (false) {
		$urlThumbFile = '';
		$isCopied = false; // successful images
		$msg = '';

		try {
			$singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;
			if ($Rsg2DebugActive)
			{
				JLog::add('    $singlePathFileName: "' . $singlePathFileName . '"');
				$Empty = empty ($this);
				JLog::add('    $Empty: "' . $Empty . '"');
			}

			$isCopied = $this->copyFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId);

			if (true) {

				if ($isCopied)
				{
					list($isCopied, $urlThumbFile, $msg) = $this->CreateRSG2Images($singleFileName, $galleryId);
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
				JLog::add('CopyImageAndCreateRSG2Images: RuntimeException');
			}

			$OutTxt = '';
			$OutTxt .= 'Error executing CopyImageAndCreateRSG2Images: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit CopyImageAndCreateRSG2Images: '
				. (($isCopied) ? 'true' : 'false')
				. ' Msg: ' . $msg);
		}

		return array($isCopied, $urlThumbFile, $msg); // file is moved
	}

	/**
	 * Delegates the creation of display, thumb and watermark images
	 *
	 * @param string $uploadPathFileName Origin path file name
	 * @param string $singleFileName  Destination base file name
	 * @param int $galleryId May be used in destination path
	 *
	 * @return array ($isMoved, $urlThumbFile, $msg) Tells about success, the URL to the thumb file and a message on error
	 *
	 * @since 4.3.0
	 * @throws Exception
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

			$imageOriginal = new image ($singlePathFileName);

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

	/**
	 * Selects all recognised images names in given folder
	 * All other file names will be returned in the ignores list
	 *
	 * @param $extractDir folder with sub folders and images
	 *
	 * @return array  List of valid image files and List of ignored files (directories do npt count)
	 *
	 * @since 4.3.2
	 */
	public function SelectImagesFromFolder ($extractDir)//: array
	{
		global $rsgConfig; //, $Rsg2DebugActive;

		//--- Read (all) files from directory ------------------

		// $folderFiles = JFolder::files($ftpPath, '');
		// $tree = JFolder::listFolderTree($extractDir);
		$recurse = true;
		$fullPath = true;
		//$folderFiles = JFolder::files($extractDir, $filter = '.', $recurse, $fullPath);
		$folderFiles = Folder::files($extractDir, $filter = '.', $recurse, $fullPath);

		//--- Allowed file types ------------------

		// wrong: $this->allowedFiles = array('jpg', 'gif', 'png', 'avi', 'flv', 'mpg');
		// $imageTypes   = explode(',', $params->get('image_formats'));

		// ToDo: remove "allowed files" from config
		// Use all files which are identified as images
		// $allowedTypes = strtolower($rsgConfig->allowedFileTypes);
		// $allowedTypes = explode(',', strtolower($rsgConfig->allowedFileTypes));

		//--- select images ------------------

		$files = array ();
		$ignored = array ();

		try
		{
			foreach ($folderFiles as $file)
			{
				// ignore folders
				if (is_dir($file))
				{
					continue;
				}
				else
				{
					//--- File information ----------------------

					// ToDo: getimagesize() sollte nicht verwendet werden, um zu überprüfen,
					// ToDo: ob eine gegebene Datei ein Bild enthält. Statt dessen sollte
					// ToDo: eine für diesen Zweck entwickelte Lösung wie die
					// ToDo: Fileinfo-Extension(finfo_file) verwendet werden

					$img_info = @getimagesize($file);

					// check if file is definitely not an image
					if (empty ($img_info))
					{
						$ignored[] = $file;
					}
					else
					{
						//--- file may be an image -----------------------------

						// $mime   = $img_info['mime']; // mime-type as string for ex. "image/jpeg" etc.

						// ToDo: Check for allowed file types from config
						//if (!in_array(fileHandler::getImageType($ftpPath . $file), $allowedTypes))
						$valid_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
						if(in_array($img_info[2],  $valid_types))
						{
							//Add filename to list
							$files[] = $file;
						}
						else
						{
							$ignored[] = $file;
						}
					}
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing SelectImagesFromFolder: "' . $file . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return array ($files, $ignored);
	}

	/**
	 * rotate_images directs the master image and all dependent images to be turned by given degrees
	 *
	 * @param string [] $fileNames list of file names of images to be turned
	 * @param int $galleryId May be used in destination path
	 * @param double $angle Angle to turn the image
	 *
	 * @return int Number of successful turned images
	 *
	 * @since version 4.3.2
	 */
	public function rotate_images($fileNames, $galleryId, $angle)
	{
		$ImgCount = 0;

		$msg = "model images: rotate_images: " . '<br>';

		foreach ($fileNames as $fileName)
		{
			$IsSaved = $this->rotate_image($fileName, $galleryId, $angle);
			if ($IsSaved){
				$ImgCount++;
			}
		}

		return $ImgCount;
	}

	/**
	 * rotate_image rotates the master image by given degrees.
	 * All dependent images will be created anwew from the turned image
	 *
	 * @param string $fileName file name of image to be turned
	 * @param int $galleryId May be used in destination path
	 * @param double $angle Angle to turn the image
	 *
	 * @return bool success
	 *
	 * @since 4.3.2
	 */
	public function rotate_image($fileName, $galleryId, $angle)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isRotated = 0;

		try
		{
			//--- image source ------------------------------------------

			$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $fileName;
			if (JFile::exists($imgSrcPath))
			{
				$memImage = new image ($imgSrcPath);
			}
			if (empty ($memImage))
			{
				$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $fileName . '.jpg';
				if (JFile::exists($imgSrcPath))
				{
					$memImage = new image ($imgSrcPath);
				}
			}

			if ( ! empty ($memImage))
			{
				$type = IMAGETYPE_JPEG;

				//--- rotate and save ------------------

				$memImage->rotate($angle, -1, false);
				$memImage->toFile($imgSrcPath, $type);
				$memImage->destroy();

				list($isRotated, $urlThumbFile, $msg) = $this->CreateRSG2Images($fileName, $galleryId);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing rotate_image: "' . $fileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isRotated;
	}

	/**
	 * flip_images directs the master image and all dependent images to be flipped in given mode
	 *
	 * @param string [] $fileNames list of file names of images to be flipped
	 * @param int $galleryId May be used in destination path
	 * @param int $flipMode flip direction horiontal, vertical or both
	 *
	 * @return int Number of successful turned images
	 *
	 * @since 4.3.2
	 */
	public function flip_images($fileNames, $galleryId, $flipMode)
	{
		$ImgCount = 0;

		$msg = "model images: flip_images: " . '<br>';

		foreach ($fileNames as $fileName)
		{
			$IsSaved = $this->flip_image($fileName, $galleryId, $flipMode);
			if ($IsSaved){
				$ImgCount++;
			}
		}

		return $ImgCount;
	}

	/**
	 * flip_images directs the master image to be flipped in given mode
	 * All dependent images will be created anwew from the flipped image
	 *
	 * @param string $fileName File name of image to be flipped
	 * @param int $galleryId May be used in destination path
	 * @param int $flipMode flip direction horiontal, vertical or both
	 *
	 * @return bool success
	 *
	 * @since 4.3.2
	 */
	public function flip_image($fileName, $galleryId, $flipMode)
	{
		global $rsgConfig;
		global $Rsg2DebugActive;

		$isRotated = 0;

		try
		{
			//--- image source ------------------------------------------

			$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $fileName;
			if (JFile::exists($imgSrcPath))
			{
				$memImage = new image ($imgSrcPath);
			}
			if (empty ($memImage))
			{
				$imgSrcPath = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/' . $fileName . '.jpg';
				if (JFile::exists($imgSrcPath))
				{
					$memImage = new image ($imgSrcPath);
				}
			}

			if ( ! empty ($memImage))
			{
				$type = IMAGETYPE_JPEG;

				//--- rotate and save ------------------

				$memImage->flip($flipMode, false);
				$memImage->toFile($imgSrcPath, $type);
				$memImage->destroy();

				list($isRotated, $urlThumbFile, $msg) = $this->CreateRSG2Images($fileName, $galleryId);
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing flip_image: "' . $fileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isRotated;
	}
}

