<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

/**
 * Supports upload of images with access to last galleries and ...
 *
 * @since 4.3.0
 */
class rsgallery2ModelUpload extends JModelLegacy  // JModelForm
{
    protected $text_prefix = 'COM_RSGallery2';

    /**
     *
     *
     * @since 4.3.2
     */
    function uploadFromZip($zip_file, $galleryId, $isInOneGallery)
    {
        global $Rsg2DebugActive;

        $isUploaded = false;
/**
        $msg     = "uploadFromZip: ";
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';

        //Retrieve data from submit form
        $input       = JFactory::getApplication()->input;
        //	$zip_file       = $input->files->get('zip_file', array(), 'FILES');
        // 'FILES' is ignored as a *.zip file marked bad from function  isSafeFile inside get
        $zip_file = $input->files->get('zip_file', array(), 'raw');
        $selcat      = $input->get('selcat', null, 'INT');

        if ($Rsg2DebugActive)
        {
            $Delim = " ";
            // show active parameters
            $DebTxt = "==> upload.uploadFromZip.php$Delim----------$Delim";
            // array
            $DebTxt = $DebTxt . "\$zip_file: " . json_encode($zip_file) . "$Delim";;
            $DebTxt = $DebTxt . "\$selcat: " . $selcat . "$Delim";

            JLog::add($DebTxt); //, JLog::DEBUG);
        }

        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('uploadFromZip'));


        $this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);

        /**/

        return $isUploaded;
    }
    /**/


	/**
	 * Create an not used filename
	 * @param string $fileName
	 * @param int    $galleryId Is prepared for own folder per gallery
	 *
	 *
	 * @return array
	 *
	 * @since 4.3.2
	 *
	function createDestinationFileName ($fileName='emptyOnCreate', $galleryId=0)
	{
		// ToDo: Decide We could create a empty file to reserve it so it is not overwritten
		//       by other users
		global $rsgConfig;

		$singleFileName = $fileName;

		$originalPath    = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/';
		$dstPathFileName = $originalPath . $singleFileName;

		try
		{
			// Test if original can't be used as it does exist already
			if (JFile::exists($dstPathFileName))
			{
				// add number
				$singleFileName  = $singleFileName . '-01';
				$dstPathFileName = $originalPath . $singleFileName;

				while (JFile::exists($dstPathFileName))
				{
					// add number
					$singleFileName  = $singleFileName . '-01';
					$dstPathFileName = $originalPath . $singleFileName;
				}
			}
		}
		catch (RuntimeException $e)
		{
			$singleFileName = '';

			$OutTxt = '';
			$OutTxt .= 'Error executing createDestinationFileName: "' . $fileName . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}
		return array ($singleFileName, $dstPathFileName);
	}
	/**/

	/**
	 * @param $app
	 * @param $rsgConfig
	 * @param $uploadFileName
	 * @param $galleryId
	 * @param $msg
	 * @param $ajaxImgObject
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function createOneImageInDb($uploadFileName, $galleryId)//: array
	{
		//global $rsgConfig, $Rsg2DebugActive;

		// ToDo: try ... catch

		// Database Image model
        //$modelDb = $this->getModel('image');
        $modelDb = $this->getInstance('image', 'RSGallery2Model');
        //
        //--- Create Destination file name -----------------------

		// ToDo: use sub folder for each gallery and check within gallery
		// Each filename is only allowed once so create a new one if file already exist
		//
		$singleFileName = $modelDb->generateNewImageName($uploadFileName, $galleryId);

		$title = $singleFileName;
		/**
		 * // Handle title (? add info or not to title)
		 * if ($uploadFileName != $singleFileName)
		 * {
		 * // $title =  $uploadFileName;
		 * $title =  $singleFileName . '(' .$uploadFileName . ')';
		 *
		 * // $uploadFileName = $singleFileName;
		 * }
		 * /**/

		//--- add image information -----------------------

		// ToDo: use exif ...

		$description = '';

		//--- create db item ----------------------------------

		// Attention: Ajax (rare) race condition: Other user may use same file name in between ?

		// Model tells if successful
		// return image id on success
		$imgId = $modelDb->createImageDbItem($singleFileName, $title, $galleryId, $description);

		return array($singleFileName, $imgId);
	}

    /**
     * Moves the file to rsg...Original
     * @param $uploadPathFileName
     * @param $singleFileName
     * @param $galleryId
     * @param $msg
     * @param $rsgConfig
     *
     * @return array
     *
     * @since version
     */
    public function MoveImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig; //, $Rsg2DebugActive;

        $urlThumbFile = '';

        $msg = '';

        // Image file handling model
        //$modelFile = $this->getModel('imageFile');
        $modelFile = $this->getInstance('imageFile', 'RSGallery2Model');

        $isMoved = false; // successful images

        // ToDo: try ... catch

        $singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;

        $isMoved = $modelFile->moveFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId);
        if ($isMoved)
        {
            list($isMoved, $urlThumbFile, $msg) = $this->CopyImageAndCreateRSG2Images($singlePathFileName, $singleFileName, $galleryId, $msg, $rsgConfig);
        }
        else
        {
            // File from other user may exist
            // lead to upload at the end ....
            $msg .= '<br>' . 'Move for file "' . $singleFileName . '" failed: Other user may have tried to upload with same name at the same moment. Please try again or with different name.';
        }

        return array($isMoved, $urlThumbFile, $msg); // file is moved
    }

	/**
	 * @param $uploadPathFileName
	 * @param $singleFileName
	 * @param $galleryId
	 * @param $msg
	 * @param $rsgConfig
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function CopyImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig; //, $Rsg2DebugActive;

		$urlThumbFile = '';

		$msg = '';

		// Image file handling model
        //$modelFile = $this->getModel('imageFile');
        $modelFile = $this->getInstance('imageFile', 'RSGallery2Model');

		$isCreated = false; // successful images

		// ToDo: try ... catch

        $singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;

        // Check if file is already in RSG2 original directory
        if (realpath ($uploadPathFileName) != realpath ($singlePathFileName))
        {
            $isCopied = $modelFile->copyFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId);
        }
        else
        {
            $isCopied = true;
        }
		if (!$isCopied)
		{
			// File from other user may exist
			// lead to upload at the end ....
			$msg .= '<br>' . 'Copy for file "' . $singleFileName . '" failed: Other user may have tried to upload with same name at the same moment. Please try again or with different name.';
		}
		else
		{   // file is copied

			//--- Create display  file ----------------------------------

			$isCreated = $modelFile->createDisplayImageFile($singlePathFileName);
			if (!$isCreated)
			{
				//
				$msg .= '<br>' . 'Create Display File for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
			}
			else
			{   // Display file is created

				//--- Create thumb file ----------------------------------

				$isCreated = $modelFile->createThumbImageFile($singlePathFileName);
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

		return array($isCreated, $urlThumbFile, $msg); // file is moved
	}

	/**
	 * @param $extractDir folder with with sub folders and images
	 *
	 * @return array  List of valid image files and List of ignored files (directories do npt count)
	 *
	 * @since 4.3.2
	 */
	// Todo: ??? move to imageFile ???
	public function SelectImagesFromFolder ($extractDir)//: array
	{
		global $rsgConfig; //, $Rsg2DebugActive;

		//--- Read (all) files from directory ------------------

		// $folderFiles = JFolder::files($ftpPath, '');
		// $tree = JFolder::listFolderTree($extractDir);
		$recurse = true;
		$fullPath = true;
		$folderFiles = JFolder::files($extractDir, $filter = '.', $recurse, $fullPath);

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
		$file = '';

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
					// check for allowed file types
					//if (!in_array(fileHandler::getImageType($ftpPath . $file), $allowedTypes))
					if (true)  // check of allowed types may have to be activated later
					{
						continue;
					}
					else
					{
						// check if file is really
						if (!@getimagesize($file))
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
}
