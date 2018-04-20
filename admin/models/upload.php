<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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
	 * @param $uploadFileName
	 * @param $galleryId
	 *
	 * @return array
	 *
	 * @since 4.3.0
     */
	public function createOneImageInDb($uploadFileName, $galleryId)//: array
	{
		global $rsgConfig; //, $Rsg2DebugActive;

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

        //--- add image information -----------------------

        // If IPTC parameter in config is true and the user left either the image title
        // or description empty in the upload step we want to get that IPTC data.
        if ($rsgConfig->get('useIPTCinformation'))
        {
            getimagesize($uploadFileName, $imageInfo);
            if (isset($imageInfo['APP13']))
            {
                $iptc = iptcparse($imageInfo['APP13']);

                //--- title -----------------

                $IPTC_title = $iptc["2#005"][0];
                if (!is_null($IPTC_title))
                {
                    $title = $IPTC_title;
                }

                //--- description -----------------

                $IPTC_caption = $iptc["2#120"][0];
                if (!is_null($IPTC_caption))
                {
                    $description = $IPTC_caption;
                }
            }
        }
        else {
            // Standard initialisation

            // $imgTitle = substr($parts['basename'], 0, -(strlen($parts['extension']) + ($parts['extension'] == '' ? 0 : 1)));
            $shortFileName = pathinfo($singleFileName, PATHINFO_FILENAME);
            //echo '$shortFileName: "' . $shortFileName . '"';
            $title = $shortFileName;
            $description = '';
        }
		//--- create db item ----------------------------------

		// Attention: Ajax (rare) race condition ofr order:
        //            Other user may use same file name in between ?

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
     *
     * @return array
     *
     * @since 4.3.0
     */
	/**
    public function MoveImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start MoveImageAndCreateRSG2Images:');
			JLog::add('    $uploadPathFileName: "' . $uploadPathFileName . '"');
			JLog::add('    $singleFileName: "' . $singleFileName . '"');
		}

//		if (false) {
        $urlThumbFile = '';
		$isMoved = false; // successful images
        $msg = '';

		try {
			// Image file handling model
			//$modelFile = $this->getModel('imageFile');
			$modelFile = $this->getInstance('imageFile', 'RSGallery2Model');

			$singlePathFileName = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/' . $singleFileName;
			if ($Rsg2DebugActive)
			{
				JLog::add('    $singlePathFileName: "' . $singlePathFileName . '"');
				$Empty = empty ($modelFile);
				JLog::add('    $Empty: "' . $Empty . '"');
			}
			
// return array($isMoved, $urlThumbFile, $msg); // file is moved

			$isMoved = $modelFile->moveFile2OriginalDir($uploadPathFileName, $singleFileName, $galleryId);

		
		if (true) {

			if ($isMoved)
			{
				list($isMoved, $urlThumbFile, $msg) = $this->CopyImageAndCreateRSG2Images($singlePathFileName, $singleFileName, $galleryId);
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
	/**/

	/**
	 * @param $uploadPathFileName
	 * @param $singleFileName
	 * @param $galleryId
	 *
	 * @return array
	 *
	 * @since 4.3.0
     */

	/**
	public function CopyImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId)//: array
	{
		global $rsgConfig, $Rsg2DebugActive;

		$urlThumbFile = '';

		$msg = '';

		if ($Rsg2DebugActive)
		{
			JLog::add('==>Start CopyImageAndCreateRSG2Images: ' . $singleFileName );
		}

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

		if ($Rsg2DebugActive)
		{
			JLog::add('<== Exit CopyImageAndCreateRSG2Images: ' 
				. (($isCreated) ? 'true' : 'false')
				. ' Msg: ' . $msg);
		}
		
		return array($isCreated, $urlThumbFile, $msg); // file is moved
	}
	/**/


}
