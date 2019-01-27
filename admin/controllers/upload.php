<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

/*=======================================================================================*/
defined('_JEXEC') or die;

use Joomla\Archive\Archive;

\JLoader::import('joomla.filesystem.file');
\JLoader::import('joomla.filesystem.folder');

jimport('joomla.application.component.controllerform');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes\configDb.php');

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.upload.php ');
}

/**
 * Functions supporting upload
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerUpload extends JControllerForm
{
	/**
	 * Proxy for getModel.
	 * @param string $name
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 */
    public function getModel($name = 'Upload', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return  parent::getModel($name, $prefix, $config);
    }

    /**
     * Takes a zip file from user and delegates the upload.
     * The files will be extracted and assigned to the given gallery
     * The dependent files display and thumb will also be created
     * The user will be redirected to the imageproperties
     * page where he can change title and description
     *
     * @since 4.3.2
     */
    function uploadFromZip()
    {
        global $Rsg2DebugActive, $rsgConfig;

        $msg     = "uploadFromZip: ";  // ToDo: Remove ->empty messge
        $msgType = 'notice';

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	    $app = JFactory::getApplication();

	    // fallback link
	    $link = 'index.php?option=com_rsgallery2&view=upload';

	    // Database IDs of created images
	    $cids = array();

	    // Prepare variables needed /created inside brackets {} for phpstorm code check
	    $isHasError = false;
	    $zipPathFileName = '';
	    //$extractDir = '';

	    // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        }
        else
        {
            try
            {
	            //--- Retrieve data from submit form -------------------

	            $input = JFactory::getApplication()->input;
	            // toDo why not: $zip_file = $input->get('zip_file');
	            // toDo why not: $zip_file = $input->files->get('zip_file', array(), 'FILES');
	            // 'FILES' is ignored as a *.zip file marked bad from function  isSafeFile inside get ignored
	            $zip_file       = $input->files->get('zip_file', array(), 'raw');
	            $isInOneGallery = $input->get('isInOneGallery', null, 'INT');
	            $galleryId      = $input->get('SelectGalleries01', null, 'INT');

	            if ($Rsg2DebugActive)
	            {
		            $Delim = " ";
		            // show active parameters
		            $DebTxt = "==> upload.uploadFromZip.php$Delim----------$Delim";
		            // array
		            $DebTxt = $DebTxt . "\$zip_file: " . json_encode($zip_file) . "$Delim";;
		            $DebTxt = $DebTxt . "\$isInOneGallery: " . $isInOneGallery . "$Delim";
		            $DebTxt = $DebTxt . "\$galleryId: " . $galleryId . "$Delim";

		            JLog::add($DebTxt); //, JLog::DEBUG);
	            }

	            //$app = JFactory::getApplication();
	            //$app->setUserState('com_rsgallery2.last_used_uploaded_zip', $zip_file);
	            //$rsgConfig->setLastUpdateType('upload_zip_pc');
	            configDb::write2Config ('last_update_type', 'upload_zip_pc', $rsgConfig);

	            //--- Check zip file name -------------------

	            // Clean up filename to get rid of strange characters like spaces etc
	            //$uploadZipName = JFile::makeSafe($zip_file['name']);
	            $uploadZipName = \JFile::makeSafe($zip_file['name']);

	            if ($zip_file['name'] !== \JFile::makeSafe($zip_file['name']) || preg_match('/\s/', \JFile::makeSafe($zip_file['name'])))
	            {
		            //$app = JFactory::getApplication();
		            $app->enqueueMessage(JText::_('COM_    _WARNFILENAME'), 'error');
		            $isHasError = true;
	            }

	            if ($Rsg2DebugActive)
	            {
		            JLog::add('\JFile::makeSafe:' . strval($isHasError));
	            }

	            $extractDir = '';

	            //--- Upload zip -------------------

	            if ( ! $isHasError)
	            {
		            //--- Create random upload directory -------------------

		            // ToDo: is it deleted again ?

		            // Create unique upload directory and store it for cleanup at the end.
		            $tmpDir = uniqid('rsgUpload_'); // 'rsginstall_'
		            $extractDir = JPath::clean(JPATH_ROOT . '/media/' . $tmpDir );

		            //--- Upload zip -------------------

		            // Upload directory will contain *.zip file and extracted image files (for a moment)
		            $zipPathFileName = $extractDir . strtolower($uploadZipName);

		            // Move uploaded file (this is truly uploading the file)
		            // *.zip needs $allow_unsafe = true since J3.4.x
		            // upload(string $src, string $dst, boolean $use_streams = false, boolean $allow_unsafe = false, boolean $safeFileOptions = array()) : boolean
		            $IsUploaded = JFile::upload($zip_file['tmp_name'], $zipPathFileName, false, true);
		            if (!$IsUploaded)
		            {
			            //$app = JFactory::getApplication();
			            $app->enqueueMessage(JText::_('COM_    _WARNFILENAME'), 'error');
			            $isHasError = true;
		            }

	            }

	            if ($Rsg2DebugActive)
	            {
		            JLog::add('Upload zip: ' . strval($isHasError));
	            }

	            //--- Extract images -------------------

	            if ( ! $isHasError)
	            {
		            //---  -------------------

		            // ToDo: Check how it is done in Joomla upload ZIP
		            $archive = new Archive();
		            $isExtracted = $archive->extract($zipPathFileName, $extractDir);
		            if (!$isExtracted)
		            {
			            $app->enqueueMessage(JText::_('COM_    _WARNFILENAME'), 'error');
			            $isHasError = true;
		            }
	            }

	            if ($Rsg2DebugActive)
	            {
		            JLog::add('Extract:' . strval($isHasError));
	            }

	            //--- Remove uploaded zip file -------------------

	            if ( ! $isHasError)
	            {
		            // Remove uploaded file on successful extract
		            JFile::delete($zipPathFileName);
	            }

	            //--- Create list of image files -------------------

	            if ( ! $isHasError)
	            {
		            $modelFile = $this->getModel('imageFile');
		            list($files, $ignored) = $modelFile->SelectImagesFromFolder ($extractDir);

		            if ($Rsg2DebugActive)
		            {
			            JLog::add('Select Images:' . count($files));
		            }

		            // Images exist
		            if ($files)
		            {
			            $modelDb = $this->getModel('image');

			            foreach ($files as $filePathName)
			            {
				            //----------------------------------------------------
				            // Transfer files and create image data in db
				            //----------------------------------------------------

				            //--- Create Destination file name -----------------------

				            $filePathName = realpath ($filePathName);
				            $baseName = basename($filePathName);

				            // ToDo: use sub folder for each gallery and check within gallery
				            // Each filename is only allowed once so create a new one if file already exist
				            $useFileName = $modelDb->generateNewImageName($baseName, $galleryId);

				            //----------------------------------------------------
				            // Create image data in db
				            //----------------------------------------------------

				            $title = $baseName;
				            $description = '';

				            $imgId = $modelDb->createImageDbItem($useFileName, '', $galleryId);
				            if (empty($imgId))
				            {
					            // actual give an error
					            //$msg     .= '<br>' . JText::_('JERROR_ALERTNOAUTHOR');
					            $msg     .= '<br>' . 'Create DB item for "' . $baseName . '" failed. Use maintenance -> Consolidate image database to check it ';
					            $msgType = 'warning';
					            $app->enqueueMessage($msg, $msgType);

					            if ($Rsg2DebugActive)
					            {
						            JLog::add('createImageDbItem failed: ' . $filePathName);
					            }
				            }
				            else
				            {
					            $cids [] = $imgId;

					            //--- Move file and create display, thumbs and watermarked images ---------------------

					            list($isCreated, $urlThumbFile, $msg) = $modelFile->MoveImageAndCreateRSG2Images($filePathName, $useFileName, $galleryId);
					            if (!$isCreated)
					            {
						            // ToDo: remove $imgId from $cids [] array and from image database

						            if ($Rsg2DebugActive)
						            {
							            JLog::add('MoveImageAndCreateRSG2Images failed: ' . $filePathName . ', ' . $useFileName);
						            }

						            $msgType = 'warning';
						            $app->enqueueMessage($msg, $msgType);
					            }
				            }
			            } // files
		            }
		            else
		            {
			            if ($Rsg2DebugActive)
			            {
				            JLog::add('No Files found: ');
			            }

			            // No files found
			            $app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_VALID_IMAGES_FOUND_IN')
				            . ' ' . JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE'));
		            }

	            }

                //--- Remove added files -----------------------------
				if ( ! empty ($extractDir))
				{

					Jfolder::delete($extractDir);

					/* ToDo: Is there something more to delete ?
					$delete = JFolder::files($app->get('tmp_path') . '/', uniqid('banners_tracks_'), false, true);

					if (!empty($delete))
					{
						if (!JFile::delete($delete))
						{
							// JFile::delete throws an error
							$this->setError(JText::_('COM_BANNERS_ERR_ZIP_DELETE_FAILURE'));

							return false;
						}
					}
					/**/
				}
				
	            //--- Image(s) transferred successfully ? --------------------

	            if (!empty ($cids))
	            {
		            $link = 'index.php?option=' . $this->option . '&view=ImagesProperties&' . http_build_query(array('cid' => $cids));
                    $msg .= JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');

		            if ($Rsg2DebugActive)
		            {
			            JLog::add('uploadFromZip: Success');
		            }
	            }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from Zip file failed');
                    $msgType = 'error';

	                // No files found
	                $app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_VALID_IMAGES_FOUND_IN')
		                . ' ' . JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE') . ' ' . basename($uploadZipName) );

	                if ($Rsg2DebugActive)
	                {
		                JLog::add('empty ($cids):');
	                }
                }
            }
            catch (RuntimeException $e)
            {
	            if ($Rsg2DebugActive)
	            {
		            JLog::add('uploadFromZip: RuntimeException');
	            }

                $OutTxt = '';
                $OutTxt .= 'Error executing uploadFromZip: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $app->enqueueMessage(JText::_('uploadFromZip'));

//	    $cids = $this->input->get('cid', 0, 'int');
	    $this->setRedirect($link, $msg, $msgType);
    }
    /**/


    /**
     * Takes a image files from server directory given by user
     * and delegates the upload. The files will be assigned to
     * the given gallery. The dependent files display and thumb
     * will also be created
     * The user will be redirected to the imageproperties
     * page where he can change title and description
     *
     * @since 4.3
     */
    function uploadFromFtpFolder()
    {
        global $Rsg2DebugActive, $rsgConfig;

	    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $msg     = "uploadFromFtpFolder"; // ToDo: Remove
        $msgType = 'notice';

	    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	    $app = JFactory::getApplication();

	    // fallback link
	    $link = 'index.php?option=com_rsgallery2&view=upload';

	    // Database IDs of created images
	    $cids = array();

	    // Prepare variables needed /created inside brackets {} for phpstorm code check
	    $isHasError = false;

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
	            //--- Retrieve data from submit form -------------------

	            $input = JFactory::getApplication()->input;
                // One gallery for all image:
                // ToDo: rename in view and here
                $isInOneGallery = $input->get('selcat', null, 'INT');
                // image ID:
                // ToDo: rename in view and here
                $galleryId = $input->get('xcat', null, 'INT');
                $ftpPath = $input->get('ftppath', null, 'RAW');

                if ($Rsg2DebugActive) {
                    $Delim = " ";
                    // show active parameters
                    $DebTxt = "==> upload.uploadFromZip.php$Delim----------$Delim";
                    $DebTxt = $DebTxt . "\$ftpPath: " . $ftpPath . "$Delim";
                    $DebTxt = $DebTxt . "\$isInOneGallery: " . $isInOneGallery . "$Delim";
                    $DebTxt = $DebTxt . "\$galleryId: " . $galleryId . "$Delim";

                    JLog::add($DebTxt); //, JLog::DEBUG);
                }

                //$rsgConfig->setLastUsedFtpPath($ftpPath);
	            configDb::write2Config ('last_used_ftp_path', $ftpPath, $rsgConfig);
                //$rsgConfig->setLastUpdateType('upload_folder_server');
	            configDb::write2Config ('last_update_type', 'upload_folder_server', $rsgConfig);

		        // Add trailing slash to source path, clean function will remove it when unnecessary
	            // $ftpPath = JPath::clean($ftpPath . '/' );

	            if (file_exists($ftpPath) && is_dir($ftpPath))
	            {
		            //--- select valid file names from ftp folder -------------------------------
		            if ($Rsg2DebugActive)
		            {
			            JLog::add('Valid folder:' . strval($ftpPath));
		            }

		            //$updModel = $this->getModel('Upload');
		            $modelFile = $this->getModel('imageFile');
		            list($files, $ignored) = $modelFile->SelectImagesFromFolder ($ftpPath);

		            if ($Rsg2DebugActive)
		            {
			            JLog::add('Select Images:' . count($files));
			            JLog::add('Ignored Images:' . count($ignored));
		            }

		            // Images exist
		            if ($files)
		            {
			            $modelDb = $this->getModel('image');

			            foreach ($files as $filePathName)
						{
							//----------------------------------------------------
							// Transfer files and create image data in db
							//----------------------------------------------------

							//--- Create Destination file name -----------------------

							$filePathName = realpath ($filePathName);
							$baseName = basename($filePathName);

							// ToDo: use sub folder for each gallery and check within gallery
							// Each filename is only allowed once so create a new one if file already exist
							$useFileName = $modelDb->generateNewImageName($baseName, $galleryId);

							//----------------------------------------------------
							// Create image data in db
							//----------------------------------------------------

							$title = $baseName;
							$description = '';

							$imgId = $modelDb->createImageDbItem($useFileName, '', $galleryId);
							if (empty($imgId))
							{
								// actual give an error
								//$msg     .= '<br>' . JText::_('JERROR_ALERTNOAUTHOR');
								$msg     .= '<br>' . 'Create DB item for "' . $baseName . '" failed. Use maintenance -> Consolidate image database to check it ';
								$msgType = 'warning';
								$app->enqueueMessage($msg, $msgType);

								if ($Rsg2DebugActive)
								{
									JLog::add('createImageDbItem failed: ' . $filePathName);
								}
							}
							else
							{
				                $cids []= $imgId;

								//--- Move file and create display, thumbs and watermarked images ---------------------

								list($isCreated, $urlThumbFile, $msg) = $modelFile->CopyImageAndCreateRSG2Images($filePathName, $useFileName, $galleryId);
								if (!$isCreated)
								{
									// ToDo: remove $imgId from $cids [] array and from image database

									if ($Rsg2DebugActive)
									{
										JLog::add('CopyImageAndCreateRSG2Images failed: ' . $filePathName . ', ' . $useFileName);
									}

									$msgType = 'warning';
									$app->enqueueMessage($msg, $msgType);
								}
							}
			            } // files
		            }
		            else
		            {
			            if ($Rsg2DebugActive)
			            {
				            JLog::add('No Files found: ' . $ftpPath);
			            }

			            // No files found
			            $app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_VALID_IMAGES_FOUND_IN')
				            . ' ' . JText::_('COM_RSGALLERY2_FTP_PATH') . ' ' . $ftpPath . "<br>"
				            . JText::_('COM_RSGALLERY2_PLEASE_CHECK_THE_PATH'));
		            }
	            }
				else
				{
					if ($Rsg2DebugActive)
					{
						JLog::add('Folder not found: ' . $ftpPath);
					}

					// folder does not exist
					$app->enqueueMessage($ftpPath . ' ' . JText::_('COM_RSGALLERY2_FU_FTP_DIR_NOT_EXIST'));
				}

	            //--- Image(s) transferred successfully ? --------------------

	            if (!empty ($cids))
	            {
		            $link = 'index.php?option=' . $this->option . '&view=ImagesProperties&' . http_build_query(array('cid' => $cids));
                    $msg .= JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');

		            if ($Rsg2DebugActive)
		            {
			            JLog::add('uploadFromZip: Success');
		            }
                }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from FTP folder failed');
                    $msgType = 'error';

	                // No files found
	                $app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_VALID_IMAGES_FOUND_IN')
		                . ' ' . JText::_('COM_RSGALLERY2_FTP_PATH')  );

	                if ($Rsg2DebugActive)
	                {
		                JLog::add('empty ($cids):');
	                }
                }
            }
            catch (RuntimeException $e)
            {
	            if ($Rsg2DebugActive)
	            {
		            JLog::add('uploadFromZip: RuntimeException');
	            }

	            $OutTxt = '';
                $OutTxt .= 'Error executing uploadFromFtpFolder: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                //$app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $app->enqueueMessage(JText::_('uploadFromFtpFolder'));

	    $this->setRedirect($link, $msg, $msgType);
    }
    /**/

    /**
     * The dropped file will be uploaded. The dependent files
     * display and thumb will also be created
     * The gallery id was created before and is read from the
     * ajax parameters
     *
     * @since 4.3
     */
    function uploadAjaxSingleFile()
    {
        global $rsgConfig, $Rsg2DebugActive;

	    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $IsMoved = false;
        $msg = 'uploadAjaxSingleFile';

        $app = JFactory::getApplication();

	    try {
            if ($Rsg2DebugActive) {
                // identify active file
                JLog::add('==> uploadAjaxSingleFile');
            }

		    // do check token
		    if ( ! JSession::checkToken()) {
			    $errMsg = JText::_('JINVALID_TOKEN') . " (01)";
			    $hasError = 1;
			    echo new JResponseJson($msg, $errMsg, $hasError);
			    $app->close();
		    }

		    $input = JFactory::getApplication()->input;
            $oFile = $input->files->get('upload_file', array(), 'raw');

            $uploadPathFileName = $oFile['tmp_name'];
            $fileType    = $oFile['type'];
            $fileError   = $oFile['error'];
            $fileSize    = $oFile['size'];

		    // Changed name on existing file name
		    $originalFileName = JFile::makeSafe($oFile['name']);
		    $uploadFileName = $input->get('dstFileName', '', 'string');

		    // for next upload tell where to start
	        //$rsgConfig->setLastUpdateType('upload_drag_and_drop');
		    configDb::write2Config ('last_update_type', 'upload_drag_and_drop', $rsgConfig);

	        if ($Rsg2DebugActive)
            {
                // identify active file
                JLog::add('$uploadPathFileName: "' . $uploadPathFileName . '"');
                JLog::add('$originalFileName: "' . $originalFileName . '"');
                JLog::add('$uploadFileName: "' . $uploadFileName . '"');
                JLog::add('$fileType: "' . $fileType . '"');
                JLog::add('$fileError: "' . $fileError . '"');
                JLog::add('$fileSize: "' . $fileSize . '"');
            }

            // ToDo: Check session id
            // $session_id      = JFactory::getSession();

            //--- check user ID --------------------------------------------

            $ajaxImgObject['file'] = $uploadFileName; // $dstFile;
	        // some dummy data for error messages
	        $ajaxImgObject['cid']  = -1;
	        $ajaxImgObject['dstFile']  = '';
	        $ajaxImgObject['originalFileName'] = $originalFileName;

		    //--- gallery ID --------------------------------------------

	        $galleryId = $input->get('gallery_id', 0, 'INT');
		    // wrong id ?
            if ($galleryId < 1)
            {
	            //$app->enqueueMessage(JText::_('COM_RSGALLERY2_INVALID_GALLERY_ID'), 'error');
	            //echo new JResponseJson;
	            echo new JResponseJson($ajaxImgObject, 'Invalid gallery ID at drag and drop upload', true);

	            $app->close();
	            return;
            }

            //--- image ID --------------------------------------------

            $imgId = $input->get('cid', 0, 'INT');
            // wrong id ?
            if ($galleryId < 1)
            {
                //$app->enqueueMessage(JText::_('COM_RSGALLERY2_INVALID_GALLERY_ID'), 'error');
                //echo new JResponseJson;
                echo new JResponseJson($ajaxImgObject, 'Invalid image ID at drag and drop upload', true);

                $app->close();
                return;
            }

            $ajaxImgObject['cid']  = $imgId;

            $singleFileName = $uploadFileName;

		    //----------------------------------------------------
	        // for debug purposes fetch image order
		    //----------------------------------------------------

		    $imageOrder = $this->imageOrderFromId ($imgId);
		    $ajaxImgObject['order']  = $imageOrder;

		    //----------------------------------------------------
		    // Move file and create display, thumbs and watermarked images
		    //----------------------------------------------------

            $modelFile = $this->getModel('imageFile');
		    list($isCreated, $urlThumbFile, $msg) = $modelFile->MoveImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId);
	        if (!$isCreated)
	        {
		        // ToDo: remove $imgId fom image database
		        if ($Rsg2DebugActive)
		        {
			        JLog::add('MoveImageAndCreateRSG2Images failed: ' . $uploadFileName . ', ' . $singleFileName);
		        }

		        echo new JResponseJson($ajaxImgObject, $msg, true);
		        $app->close();
		        return;
	        }

            if ($Rsg2DebugActive)
            {
                JLog::add('<== uploadAjax: After MoveImageAndCreateRSG2Images isCreated: ' . $isCreated );
            }

            $ajaxImgObject['dstFile'] = $urlThumbFile; // $dstFileUrl ???

		    if ($Rsg2DebugActive) {
			    JLog::add('    $ajaxImgObject: ' . json_encode($ajaxImgObject));
			    JLog::add('    $msg: "' . $msg . '"');
			    JLog::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
		    }

            echo new JResponseJson($ajaxImgObject, $msg, !$isCreated);
	        //echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

            if ($Rsg2DebugActive) {
                JLog::add('<== Exit uploadAjaxSingleFile');
            }

        } catch (Exception $e) {
		    if ($Rsg2DebugActive) {
			    JLog::add('    Exception: ' . $e->getMessage());
			}

            echo new JResponseJson($e);
			
        }

        $app->close();
    }

	/**
	 * The database entry for the image will be created here
	 * It is called for each image for preserving the correct
	 * ordering before uploading the images
	 * Reason: The parallel uploaded images may appear unordered
	 *
	 * @since 4.3.0
	 * @throws Exception
	 */
	function uploadAjaxReserveDbImageId ()
	{
		global $rsgConfig, $Rsg2DebugActive;

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = 'uploadAjaxReserveImageInDB';

		$app = JFactory::getApplication();

		// ToDo: security check

		try {
			if ($Rsg2DebugActive) {
				// identify active file
				JLog::add('==> uploadAjaxInsertInDB');
			}

			// do check token
			if ( ! JSession::checkToken()) {
				$errMsg = JText::_('JINVALID_TOKEN') . " (02)";
				$hasError = 1;
				echo new JResponseJson($msg, $errMsg, $hasError);
				$app->close();
			}

			$input = JFactory::getApplication()->input;
			//$oFile = $input->files->get('upload_file', array(), 'raw');
			//$uploadFileName    = JFile::makeSafe($oFile['name']);

            //--- file name  --------------------------------------------

            $uploadFileName = $input->get('upload_file', '', 'string');
			$fileName = JFile::makeSafe($uploadFileName);

// ==>			joomla replace spaces in filenames
// ==>			'file_name' => str_replace(" ", "", $file_name);
			$baseName = basename($fileName);

			if ($Rsg2DebugActive)
			{
				// identify active file
				JLog::add('$uploadFileName: "' . $uploadFileName . '"');
				JLog::add('$fileName: "' . $fileName . '"');
				JLog::add('$baseName: "' . $baseName . '"');
			}

			// ToDo: Check session id
			// $session_id      = JFactory::getSession();

			$ajaxImgDbObject['fileName'] = $uploadFileName; // $dstFile;
			// some dummy data for error messages
			$ajaxImgDbObject['cid']  = -1;
			$ajaxImgDbObject['dstFile'] = '$fileName';

			//--- gallery ID --------------------------------------------

			$galleryId = $input->get('gallery_id', 0, 'INT');
			// wrong id ?
			if ($galleryId < 1)
			{
				//$app->enqueueMessage(JText::_('COM_RSGALLERY2_INVALID_GALLERY_ID'), 'error');
				//echo new JResponseJson;
				$msg .= ': Invalid gallery ID at drag and drop upload';

				if ($Rsg2DebugActive)
				{
					JLog::add($msg);
				}

				echo new JResponseJson($ajaxImgDbObject, $msg, true);

				$app->close();
				return;
			}

            //--- dropListIdx --------------------------------------------

            // Return index into files list
            $dropListIdx = $input->get('imagesDroppedListIdx', -1, 'INT');
            $ajaxImgDbObject['imagesDroppedListIdx']  = (string) $dropListIdx;

            //--- Check 4 allowed image type ---------------------------------

			// May be checked when opening file ...

			//----------------------------------------------------
			// Create image data in db
			//----------------------------------------------------

			$modelDb = $this->getModel('image');

			//--- Create Destination file name -----------------------

			// ToDo: use sub folder for each gallery and check within gallery
			// Each filename is only allowed once so create a new one if file already exist
			$useFileName = $modelDb->generateNewImageName($baseName, $galleryId);
			$ajaxImgDbObject['dstFileName'] = $useFileName;

			//--- create image data in DB --------------------------------

			$title = $baseName;
			$description = '';

			$imgId = $modelDb->createImageDbItem($useFileName, '', $galleryId, $description);
			if (empty($imgId))
			{
				// actual give an error
				//$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
				$msg     .= 'uploadAjaxReserveImageInDB: Create DB item for "' . $baseName . '"->"' . $useFileName . '" failed. Use maintenance -> Consolidate image database to check it ';

				if ($Rsg2DebugActive)
				{
					JLog::add($msg);
				}

				// replace newlines with html line breaks.
				//str_replace('\n', '<br>', $msg);
				echo new JResponseJson($ajaxImgDbObject, $msg, true);

				$app->close();
				return;
			}

			if ($Rsg2DebugActive)
			{
				JLog::add('<== uploadAjax: After createImageDbItem: ' . $imgId );
			}

			// $this->ajaxDummyAnswerOK (); return; // 05

			$ajaxImgDbObject['cid']  = $imgId;
            $isCreated = $imgId > 0;

			//----------------------------------------------------
			// for debug purposes fetch image order
			//----------------------------------------------------

			$imageOrder = $this->imageOrderFromId ($imgId);
			$ajaxImgDbObject['order']  = $imageOrder;

			//----------------------------------------------------
			// return result
			//----------------------------------------------------

			if ($Rsg2DebugActive) {
				JLog::add('    $ajaxImgDbObject: ' . json_encode($ajaxImgDbObject));
				JLog::add('    $msg: "' . $msg . '"');
				JLog::add('    !$isCreated (error):     ' . ((!$isCreated) ? 'true' : 'false'));
			}

			echo new JResponseJson($ajaxImgDbObject, $msg, !$isCreated);
			//echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

			if ($Rsg2DebugActive) {
				JLog::add('<== Exit uploadAjaxSingleFile');
			}

		} catch (Exception $e) {
			echo new JResponseJson($e);
		}

		$app->close();
	}

	/**
	 * Returns the order value of the image given by image ID
	 *
	 * @param $imageId Id of image
	 * @return int  value "order" of image
	 *
	 * @since 4.3.0
    */
	private function imageOrderFromId ($imageId)
	{
		$imageOrder = -1;

		try
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('ordering')
				->from($db->quoteName('#__rsgallery2_files'))
				->where($db->quoteName('id') . ' = ' . $db->quote($imageId));
			$db->setQuery($query);
			$imageOrder = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing imageOrderFromId for $imageId: "' . $imageId . '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $imageOrder;
	}
}

