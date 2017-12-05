<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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
	JLog::add('==> ctrl.uploadFileProperties.php ');
}

jimport('joomla.application.component.controllerform');

/**
 * Functions supporting upload
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerUploadFileProperties extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since
	 *
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
    /**/

    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'UploadFileProperties', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return  parent::getModel($name, $prefix, $config);
    }

	/**
	 * Called after drop uploading images or zip file
	 *
	 * @since version
	 */
    public function prepareDroppedImages ()
    {
	    global $Rsg2DebugActive;
	    global $rsgConfig;

	    if($Rsg2DebugActive)
	    {
		    JLog::add('==> ctrl.uploadFileProperties.php/prepareDroppedImages');
	    }

	    $msg     = "controller.prepareDroppedImages: ";
	    $msgType = 'notice';

	    $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
	    if (!$canAdmin)
	    {
		    //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		    $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
		    $msgType = 'warning';
		    // replace newlines with html line breaks.
		    str_replace('\n', '<br>', $msg);

		    $this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
	    }
	    else
	    {
            //form.xcat.value = GalleryId;
            //form.selcat.value = bOneGalleryName4All;
            $session = JFactory::getSession();
            $actSessionId = $session->getId();

            //Retrieve data from submit form
            $input = JFactory::getApplication()->input;

		    $this->fileSessionId = $input->get('installer-token', '', 'STRING');
            //$this->isInOneGallery = $input->get('isInOneGallery', null, 'INT');
            $this->isInOneGallery = $input->get('selcat', null, 'INT');
            //$this->galleryId = $input->get('GalleryId', null, 'INT');
            $this->galleryId = $input->get('xcat', null, 'INT');
            //$this->fileSessionId = $input->get('session_id', '', 'STRING');

		    // ToDo: add number of detected files ... ?
		    $msg = 'done prepareDroppedImages: ' . '<br>';
		    $msg .= 'Prepare-actSessionId: ' . $actSessionId . '<br>';
		    $msg .= 'Prepare-fileSessionId: ' . $this->fileSessionId . '<br>';
		    $msg .= 'Prepare-isInOneGallery: ' . $this->isInOneGallery . '<br>';
		    $msg .= 'Prepare-galleryId: ' . $this->galleryId . '<br>';
		    //$msg .= '' . '<br> <br> <br> <br>';

		    $this->setRedirect('index.php?option=com_rsgallery2&view=UploadFileProperties'
                . '&isInOneGallery=' . $this->isInOneGallery
                . '&galleryId=' . $this->galleryId
                . '&fileSessionId=' . $this->fileSessionId
                , $msg);
	    }

	    // for next upload tell where to start
	    $rsgConfig->setLastUpdateType('upload_single');
    }

    public function assign2Gallery ()
    {
	    global $rsgConfig;
	    global $Rsg2DebugActive;


	    $dbgMessage = ''; // debug message
		$ImgCount = 0; // successful images

        // Return address if all is successful (or ? error)
	    $redirectUrl = 'index.php?option=com_rsgallery2&view=upload';

	    if($Rsg2DebugActive)
	    {
		    JLog::add('==> ctrl.uploadFileProperties.php/assign2Gallery');
	    }

	    $msg     = "controller.prepareDroppedImages: ";
	    $msgType = 'notice';

	    $canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
	    if (!$canAdmin)
	    {
		    //JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		    $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
		    $msgType = 'warning';
		    // replace newlines with html line breaks.
		    str_replace('\n', '<br>', $msg);

	    }
	    else
	    {   // can admin
	        $dbgMessage .= '--- assign2Gallery ----' . '<br>';

            //form.xcat.value = GalleryId;
            //form.selcat.value = bOneGalleryName4All;
            $session = JFactory::getSession();
            $actSessionId = $session->getId();

            //$dbgMessage .= '$session: ' . $session. '<br>';
            $dbgMessage .= '$actSessionId: ' . $actSessionId. '<br>';
            
            //Retrieve data from submit form
            $input = JFactory::getApplication()->input;
            $fileSessionId = $input->get('fileSessionId', '', 'STRING');

            $dbgMessage .= '$fileSessionId: ' . $fileSessionId. '<br>';

            //$this->isInOneGallery = $input->get('isInOneGallery', null, 'INT');
		    $isInOneGallery = $input->get('isInOneGallery', null, 'INT');
            $dbgMessage .= '$isInOneGallery: ' . $isInOneGallery. '<br>';

            //--- arrays -------------------------------------------

            // FileName
		    $FileNamesX = $input->get('FileNameX', array(), 'ARRAY');
            $FileNames = $input->get('FileName', array(), 'ARRAY');

            $dbgMessage .= '$FileNamesX: ' . json_encode($FileNamesX). '<br>';
            $dbgMessage .= '$FileNames: ' . json_encode($FileNames). '<br>';

		    // title
		    $titlesX = $input->get('titleX', array(), 'ARRAY');
		    $titles = $input->get('title', array(), 'ARRAY');

		    $dbgMessage .= '$titlesX: ' . json_encode($titlesX). '<br>';
		    $dbgMessage .= '$titles: ' . json_encode($titles). '<br>';

		    // delete
		    $deletesX = $input->get('deleteX', array(), 'ARRAY');
		    $deletes = $input->get('delete', array(), 'ARRAY');

		    $dbgMessage .= '$deletesX: ' . json_encode($deletesX). '<br>';
		    $dbgMessage .= '$deletes: ' . json_encode($deletes). '<br>';

		    // gallery ID
            $galleryIdsX = $input->get('galleryIdX', array(), 'ARRAY');
            $galleryIds = $input->get('galleryId', array(), 'ARRAY');

            $dbgMessage .= '$galleryIdsX: ' . json_encode($galleryIdsX). '<br>';
            $dbgMessage .= '$galleryIds: ' . json_encode($galleryIds). '<br>';

            // Description

            $descriptionsX = $input->get('descriptionX', array(), 'ARRAY');
            $descriptions = $input->get('description', array(), 'ARRAY');

            $dbgMessage .= '$descriptionsX: ' . json_encode($descriptionsX). '<br>';
            $dbgMessage .= '$descriptions: ' . json_encode($descriptions). '<br>';

            // ToDo: set redirect to images in gallery ?
		    //$this->setRedirect('index.php?option=com_rsgallery2&view=????', $msg, $msgType);


            $msg = 'assign2Gallery: ';
            // $msg .= '<br><br>' . $dbgMessage;


            // Url to restart from the found files view
		    $redirectRestartUrl = 'index.php?option=com_rsgallery2&view=UploadFileProperties'
			    . '&isInOneGallery=' . $isInOneGallery
			    . '&galleryId=' . $galleryIdsX[0]
			    . '&fileSessionId=' . $fileSessionId;

		    //----------------------------------------------------
		    // Transfer files and create image data in db
		    //----------------------------------------------------

		    // Image file handling model
		    $modelFile = $this->getModel('imageFile');
		    $modelDb = $this->getModel('image');

		    $isWatermarkActive = $rsgConfig->get('watermark');
		    if (!empty($isWatermarkActive))
		    {
			    $modelWatermark = $this->getModel('ImgWaterMark');
		    }

		    $Idx = 0;
			foreach ($FileNamesX as $fileName)
			{
				//--- collect Data ------------------------------------

				// same as below:: $imageName = isset($FileNamesX[$Idx]) ? basename($FileNamesX[$Idx]) : '';
				$imageName = basename($fileName);
				$title =  isset($titlesX[$Idx]) ? $titlesX[$Idx] : '';
				$galleryId =  isset($galleryIdsX[$Idx]) ? $galleryIdsX[$Idx] : 0;
				$description =  isset($descriptionsX[$Idx]) ? $descriptionsX[$Idx] : '';

				$delete = isset($deletesX[$Idx]) ? $deletesX[$Idx] : false;

				//If image is marked for deletion, delete and continue with next iteration
				if ($delete == 'true')
				{
					//Delete file from server
					unlink($fileName);
					continue;
				}

				//--- Transfer file ----------------------------------

				$isMoved = $modelFile->moveFile2OriginalDir($fileName); // ToDo: add gallery ID as parameter for subfolder or subfolder itself ...
				if ( ! $isMoved)
				{
					// File from other user may exist
					// lead to upload at the end ....
					$msg .= '<br>' . 'Move for file "' . $imageName . '" failed: It may be created by other user. Please try with different name.';
					$redirectUrl = $redirectRestartUrl;
				}
				else
				{   // file is moved

					//--- Create display  file ----------------------------------

					$isCreated = $modelFile->createDisplayImageFile($fileName);
					if (!$isCreated)
					{
						//
						$msg .= '<br>' . 'Create Display File for "' . $imageName . '" failed. Use maintenance -> Consolidate image database to check it ';
					}
					else
					{   // Display file is created

						//--- Create thumb file ----------------------------------

						$isCreated = $modelFile->createThumbImageFile($fileName);
						if (!$isCreated)
						{
							//
							$msg .= '<br>' . 'Create Thumb File for "' . $imageName . '" failed. Use maintenance -> Consolidate image database to check it ';
						}

						//--- Create watermark file ----------------------------------

						if (!empty($isWatermarkActive))
						{
							$isCreated = $modelWatermark->createMarkedFromBaseName(basename($fileName), 'original');
							if (!$isCreated)
							{
								//
								$msg .= '<br>' . 'Create Watermark File for "' . $imageName . '" failed. Use maintenance -> Consolidate image database to check it ';
							}
						}

						//--- create db item ----------------------------------

						// Model tells if successful
                        $imgId = $modelDb->createImageDbItem($imageName, $title, $galleryId, $description);
						if ($imgId < 1)
						{
							// ToDo: Db entry may exist but copy / move has failed then try to fix this

							// actual give an error
							$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
							$msg .= '<br>' . 'Create DB item for "' . $imageName . '" failed. Use maintenance -> Consolidate image database to check it ';
							$msgType = 'warning';

							// replace newlines with html line breaks.
							//str_replace('\n', '<br>', $msg);
						}
						else
						{
							// successful transfer
							$ImgCount += 1;
						}

					} // display file

				} // file is moved
			} // all files
	    }

	    if ($ImgCount != 1)
	    {
		    $msg .= 'Uploaded ' . $ImgCount . ' images'; // toDo: into gallery name $galleryId
	    }
	    else
	    {
		    $msg .= 'Uploaded ' . $ImgCount . ' image'; // toDo: into gallery name
	    }
	    $this->setRedirect($redirectUrl, $msg, $msgType);

	    return;
    } // assign gallery

} // class

