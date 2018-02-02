<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

/**
 * New Subcontroller \com_example\controllers\forajax.php
 *
 */
/**
require_once JPATH_COMPONENT.'/controller.php';
class ExampleControllerForAjax extends ExampleController
{
    public function MyTaskName()
    {
        $app = JFactory::getApplication();

        $data['myRequest'] =$_REQUEST;
        $data['myFile'] =__FILE__;
        $data['myLine'] ='Line '.__LINE__;

        $app->enqueueMessage('This part was reached at line ' . __LINE__);
        $app->enqueueMessage('Then this part was reached at line ' . __LINE__);
        $app->enqueueMessage('Here was a small warning at line ' . __LINE__, 'warning');
        $app->enqueueMessage('Here was a big warning at line ' . __LINE__, 'error');

        $task_failed = false;
        echo new JResponseJson($data, 'My main response message',$task_failed);

        $app->close();
    }
}
/**/
/**
Rendered JSON Output
{
    success: true,
    message: "My main response message",
    messages: {
    message: [
        "This part was reached at line 26",
        "Then this part was reached at line 27"
    ],
        warning: [
        "Here was a small warning at line 28"
    ],
        error: [
        "Here was a big warning at line 29"
    ]
    },
    data: {
    myRequest: {
        option: "com_example",
            task: "mytaskname",
            Itemid: null
        },
    myFile: "C:\mysite\components\com_example\controllers\forajax.php",
        myLine: "Line 24"
    }
}
/**/


/**
Valentin's answer is good.

I prefer a json controller that handles the encoding and error handling for this I created a json base class:
class itrControllerJson extends JControllerLegacy {

  /** @var array the response to the client *
  protected $response = array();

  public function addResponse($type, $message, $status=200) {

    array_push($this->response, array(
      'status' => $status,
      'type' => $type,
      'data' => $message
    ));

  }

  /**
   * Outputs the response
   * @return JControllerLegacy|void
   *
  public function display() {

    $response = array(
      'status' => 200,
      'type' => 'multiple',
      'count' => count($this->response),
      'messages' => $this->response
    );

    echo json_encode($response);
    jexit();
  }

}
/**/

/**
This controller get extended by the controller class that do the work, something like this:
require_once __DIR__.'json.php';

class componentControllerAddress extends itrControllerJson {
  public function get() {

    try {
      if (!JSession::checkToken()) {
        throw new Exception(JText::_('JINVALID_TOKEN'), 500);
      }
      $app = JFactory::getApplication();

      $id = $app->input->get('id', null, 'uint');
      if (is_null($id)) {
        throw new Exception('Invalid Parameter', 500);
      }

      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('*');
      $query->from('#__table');
      $query->where('id = '.$db->quote($id));
      $db->setQuery($query);
      $response = $db->loadObject();

      $this->addResponse('message', $response, 200);

    } catch (Exception $e) {
    $this->addResponse('error', $e->getMessage(), 500);
}

    $this->display();
  }
}
/**/
/**
and you call the request like this:
index.php?option=com_component&task=address.get&format=json&id=1234&tokenhash=1


    The token hash get generated by JSession::getFormToken(). So the complete complete call could be look like this:
$link = JRoute::_('index.php?option=com_component&task=address.get&format=json&id=1234&'.JSession::getFormToken().'=1', false);


The second parameter is set to "false" so we can use this in javascript calls without xml rewrite.
/**/


/*=======================================================================================*/
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.upload.php ');
}

jimport('joomla.application.component.controllerform');

/**
 * Functions supporting upload
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerUpload extends JControllerForm
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
    public function getModel($name = 'Upload', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return  parent::getModel($name, $prefix, $config);
    }

    /**
     *
     *
     * @since 4.3.2
     */
    function uploadFromZip()
    {
        global $Rsg2DebugActive, $rsgConfig;

        $msg     = "uploadFromZip: ";  // ToDo: Remove ->empty messge
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';
        
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

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
	            $galleryId      = $input->get('GalleryId', null, 'INT');

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

	            $app = JFactory::getApplication();
	            $app->setUserState('com_rsgallery2.last_used_uploaded_zip', $zip_file);
	            // $rsgConfig->setLastUsedZipFile($zip_file);
	            $rsgConfig->setLastUpdateType('upload_zip_pc');

	            //--- Check zip file name -------------------

	            // Clean up filename to get rid of strange characters like spaces etc
	            $uploadZipName = JFile::makeSafe($zip_file['name']);

	            jimport('joomla.filesystem.file');

	            if ($zip_file['name'] !== JFile::makeSafe($zip_file['name']) || preg_match('/\s/', JFile::makeSafe($zip_file['name'])))
	            {
		            //$app = JFactory::getApplication();
		            $app->enqueueMessage(JText::_('COM_    _WARNFILENAME'), 'error');
		            $isHasError = true;
	            }

	            if ($Rsg2DebugActive)
	            {
		            JLog::add('JFile::makeSafe:' . strval($isHasError));
	            }

	            $extractDir = '';

	            //--- Upload zip -------------------

	            if ( ! $isHasError)
	            {
		            //--- Create random upload directory -------------------

		            // ToDo: is it deleted again ?

		            // Create unique upload directory and store it for cleanup at the end.
		            $tmpDir = uniqid('rsgUpload_'); // 'rsginstall_'
		            $extractDir = JPath::clean(JPATH_ROOT . '/media/' . $tmpDir . '/' );

		            //--- Upload zip -------------------

		            // Upload directory will contain *.zip file and extracted image files (for a moment)
		            $zipPathFileName = $extractDir . $uploadZipName;

		            // Move uploaded file (this is truely uploading the file)
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
		            JLog::add('Upload zip:' . strval($isHasError));
	            }

	            //--- Extract images -------------------

	            if ( ! $isHasError)
	            {
		            //---  -------------------

		            // toDo: Check how it is done in Joomla upload ZIP
		            $isExtracted = JArchive::extract($zipPathFileName, $extractDir);
		            if (!$IsUploaded)
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
		            JFile::delete($zipPathFileName['tmp_name']);
	            }

	            //--- Create list of image files -------------------

	            if ( ! $isHasError)
	            {
		            $model = $this->getModel('Upload');
		            list($files, $ignored) = $model->SelectImagesFromFolder ($extractDir);

		            if ($Rsg2DebugActive)
		            {
			            JLog::add('Select Images:' . count($files));
		            }

		            // Images exist
		            if ($files)
		            {
			            foreach ($files as $filePathName)
			            {
				            //----------------------------------------------------
				            // Transfer files and create image data in db
				            //----------------------------------------------------

				            //--- create image data in DB --------------------------------

				            list($singleFileName, $imgId) = $model->createOneImageInDb(basename($filePathName), $galleryId);
				            if (empty($imgId))
				            {
					            // actual give an error
					            //$msg     .= '<br>' . JText::_('JERROR_ALERTNOAUTHOR');
					            $msg     .= '<br>' . 'Create DB item for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
					            $msgType = 'warning';

					            if ($Rsg2DebugActive)
					            {
						            JLog::add('createOneImageInDb failed: ' . $filePathName);
					            }
				            }
				            else
				            {
					            $cids [] = $imgId;

					            //--- Move file and create display, thumbs and watermarked images ---------------------

					            list($isCreated, $urlThumbFile, $subMsg) = $model->CopyImageAndCreateRSG2Images($filePathName, $singleFileName, $galleryId, $msg, $rsgConfig);
					            if (!$isCreated)
					            {
						            // ToDo: remove $imgId from $cids [] array and from image database




						            if ($Rsg2DebugActive)
						            {
							            JLog::add('CopyImageAndCreateRSG2Images failed: ' . $filePathName . ', ' . $singleFileName);
						            }

						            // actual give an error
						            $msg     .= '<br>' . $subMsg;
						            $msgType = 'warning';
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

		            //--- Image(s) transferred successfully --------------------

		            if (!empty ($cids))
		            {
			            $link = 'index.php?option=' . $this->option . '&view=ImagesProperties&' . http_build_query(array('cid' => $cids));
		            }
		            else
		            {
			            // No files found
			            $app->enqueueMessage(JText::_('COM_RSGALLERY2_NO_VALID_IMAGES_FOUND_IN')
				            . ' ' . JText::_('COM_RSGALLERY2_ZIP_MINUS_FILE') . ' ' . basename($uploadZipName) );
		            }
	            }

                //--- Remove added files -----------------------------
				if ( ! empty ($extractDir))
				{

					Jfolder::delete($extractDir);

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
     *
     *
     * @since 4.3
     */
    function uploadFromFtpFolder()
    {
        global $Rsg2DebugActive, $rsgConfig;

	    JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $msg     = "";
        $msg     = "uploadFromFtpFolder"; // ToDo: Remove
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';

        // fallback link
	    $link = 'index.php?option=com_rsgallery2&view=upload';

	    // Database IDs of created images
	    $cids = array();

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

                $app = JFactory::getApplication();
                $app->setUserState('com_rsgallery2.last_used_ftp_path', $ftpPath);
                $rsgConfig->setLastUsedFtpPath($ftpPath);
                $rsgConfig->setLastUpdateType('upload_folder_server');

		        // Add trailing slash to source path, clean function will remove it when unnecessary
	            // $ftpPath = JPath::clean($ftpPath . '/' );

	            if (file_exists($ftpPath) && is_dir($ftpPath))
	            {
		            //--- select valid file names from ftp folder -------------------------------
		            if ($Rsg2DebugActive)
		            {
			            JLog::add('Valid folder:' . strval($ftpPath));
		            }

		            $model = $this->getModel('Upload');
		            list($files, $ignored) = $model->SelectImagesFromFolder ($ftpPath);

		            if ($Rsg2DebugActive)
		            {
			            JLog::add('Select Images:' . count($files));
			            JLog::add('Ignored Images:' . count($ignored));
		            }

		            // Images exist
		            if ($files)
		            {
						foreach ($files as $filePathName)
						{
							//----------------------------------------------------
							// Transfer files and create image data in db
							//----------------------------------------------------

							//--- create image data in DB --------------------------------

							list($singleFileName, $imgId) = $model->createOneImageInDb(basename($filePathName), $galleryId);
							if (empty($imgId))
							{
								// actual give an error
								$msg     .= '<br>' . JText::_('JERROR_ALERTNOAUTHOR');
								$msg     .= '<br>' . 'Create DB item for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
								$msgType = 'warning';


								if ($Rsg2DebugActive)
								{
									JLog::add('createOneImageInDb failed: ' . $filePathName);
								}
							}
							else
							{
				                $cids []= $imgId;

								//--- Move file and create display, thumbs and watermarked images ---------------------

								list($isCreated, $urlThumbFile, $subMsg) = $model->CopyImageAndCreateRSG2Images($filePathName, $singleFileName, $galleryId, $msg, $rsgConfig);
								if (!$isCreated)
								{
									// ToDo: remove $imgId from $cids [] array and from image database



									if ($Rsg2DebugActive)
									{
										JLog::add('CopyImageAndCreateRSG2Images failed: ' . $filePathName . ', ' . $singleFileName);
									}

									// actual give an error
									$msg     .= '<br>' . $subMsg;
									$msgType = 'warning';
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
                }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from FTP folder failed');
                    $msgType = 'error';

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

//	    $cids = $this->input->get('cid', 0, 'int');
	    $this->setRedirect($link, $msg, $msgType);
    }
    /**/

    /**
     * ToDo: ? delete file on error
     * ToDo: Check access rights : how is it done in Joomla Upload drag and drop
     *
     * @since 4.3
     */
    function uploadAjaxSingleFile()
    {
        global $rsgConfig, $Rsg2DebugActive;

        $IsMoved = false;
        $msg = 'uploadAjaxSingleFile';

        $app = JFactory::getApplication();


	    try {
            if ($Rsg2DebugActive) {
                // identify active file
                JLog::add('==> uploadAjaxSingleFile');
            }

            /**
	        // echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);
	        echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);
	        $app->close();
			return;
            /**/


	        $input = JFactory::getApplication()->input;
            $oFile = $input->files->get('upload_file', array(), 'raw');

            $uploadPathFileName = $oFile['tmp_name'];
            $uploadFileName    = $oFile['name'];
            $fileType    = $oFile['type'];
            $fileError   = $oFile['error'];
            $fileSize    = $oFile['size'];

	        // for next upload tell where to start
	        $rsgConfig->setLastUpdateType('upload_drag_and_drop');

	        if ($Rsg2DebugActive)
            {
                // identify active file
                JLog::add('$uploadPathFileName: "' . $uploadPathFileName . '"');
                JLog::add('$uploadFileName : "' . $uploadFileName . '"');
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
	        $ajaxImgObject['dstFile'] = '';


	        /**
	        // echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);
	        //echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);
	        echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", false);
	        $app->close();
	        return;
	        /**/

	        /**
	        $msg = "uploadAjaxSingleFile (2)";
	        $ajaxImgObject['file'] = $uploadFileName; // $dstFile;
	        // some dummy data for error messages
	        $ajaxImgObject['cid']  = -1;
	        //$ajaxImgObject['dstFile'] = $urlThumbFile; // $dstFileUrl ???
	        $ajaxImgObject['dstFile'] = 'd:\xampp\htdocs\joomla3x\images\rsgallery\thumb\DSC_1088.JPG.jpg';
	        echo new JResponseJson($ajaxImgObject, $msg, false);
	        $app->close();
	        return;
			/**/

	        /** ToDo: activate user token
	        $postUserId = $input->get('token', '', 'STRING');
            $user = JFactory::getUser();
            if ($postUserId != $user)
            {
                $app->enqueueMessage(JText::_('JINVALID_USER'), 'error');
                //echo new JResponseJson;
                echo new JResponseJson($ajaxImgObject, 'Invalid token at drag and drop upload', true);
                echo new JResponseJson($ajaxImgObject, 'Invalid token at drag and drop upload', true);

                $app->close();
                return;
            }
			/**/

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

		    //--- Check 4 allowed image type ---------------------------------


            /* ToDo: Put in again ... */

            $allowedTypes = explode(",", strtolower($rsgConfig->get('allowedFileTypes')));

		    // $this->ajaxDummyAnswerOK (); return; // 03
		    //$fileTypeId = array_pop (explode ('/', $fileType)); // destroying ajax answer
		    //$this->ajaxDummyAnswerOK (); return; // 04
		    $exploded = explode ('/', $fileType);
		    $fileTypeId = array_pop ($exploded);
		    // $this->ajaxDummyAnswerOK (); return; //

		    if (!in_array($fileTypeId, $allowedTypes))
	        {
		        echo new JResponseJson($ajaxImgObject, 'Wrong file type for "' . $uploadFileName . '"', true);

		        if ($Rsg2DebugActive)
		        {
			        JLog::add('AllowedFileTypes failed:' . basename($uploadFileName));
		        }

		        $app->close();
		        return;
	        }
            /**/

            //--- check type for 'is image' -------------------
            /* here not necessary as is already checked above *
	        if ( ! @getimagesize($uploadPathFileName))
	        {
		        echo new JResponseJson($ajaxImgObject, 'Uploaded file is not an image : "' . $uploadFileName  . '"', true);

		        if ($Rsg2DebugActive)
		        {
			        JLog::add('Check image type failed:' . basename($uploadFileName));
		        }

		        if (is_file ($uploadFileName))
		        {
			        if (!JFile::delete($uploadFileName))
			        {
				        // JFile::delete throws an error
				        echo new JResponseJson($ajaxImgObject, 'JFile::delete throws an error', true);
			        }
		        }

		        $app->close();
		        return;
	        }
            /**/

	        //----------------------------------------------------
            // Transfer files and create image data in db
            //----------------------------------------------------

	        $model = $this->getModel('Upload');

	        //--- create image data in DB --------------------------------

	        list($singleFileName, $imgId) = $model->createOneImageInDb($uploadFileName, $galleryId);
	        if (empty($imgId))
	        {
		        // actual give an error
		        //$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
		        $msg     .= '<br>' . 'Create DB item for "' . $singleFileName . '" failed. Use maintenance -> Consolidate image database to check it ';
		        $msgType = 'warning';


		        if ($Rsg2DebugActive)
		        {
			        JLog::add('createOneImageInDb failed: ' . basename($uploadFileName));
		        }

		        // replace newlines with html line breaks.
		        //str_replace('\n', '<br>', $msg);
		        echo new JResponseJson($ajaxImgObject, $msg, true);

		        $app->close();
		        return;
	        }

            if ($Rsg2DebugActive)
            {
                JLog::add('<==After createOneImageInDb: ' . $imgId );
            }

            // $this->ajaxDummyAnswerOK (); return; // 05

            $ajaxImgObject['cid']  = $imgId;

			//--- Move file and create display, thumbs and watermarked images ---------------------

	        list($isCreated, $urlThumbFile, $msg) = $model->MoveImageAndCreateRSG2Images($uploadPathFileName, $singleFileName, $galleryId, $msg, $rsgConfig);
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
                JLog::add('<==After MoveImageAndCreateRSG2Images: ' . $isCreated );
            }

            $ajaxImgObject['dstFile'] = $urlThumbFile; // $dstFileUrl ???

            // $this->ajaxDummyAnswerOK (); return; // 16



            /**
	        $msg = "uploadAjaxSingleFile (2)";
	        $ajaxImgObject['file'] = $uploadFileName; // $dstFile;
	        // some dummy data for error messages
	        $ajaxImgObject['cid']  = -1;
	        //$ajaxImgObject['dstFile'] = $urlThumbFile; // $dstFileUrl ???
	        $ajaxImgObject['dstFile'] = 'd:\xampp\htdocs\joomla3x\images\rsgallery\thumb\DSC_1088.JPG.jpg';
	        echo new JResponseJson($ajaxImgObject, $msg, false);
	        $app->close();
	        return;
	        /**/

	        // ??? msg is ???
            echo new JResponseJson($ajaxImgObject, $msg, !$isCreated);
	        //echo new JResponseJson("uploadAjaxSingleFile (1)", "uploadAjaxSingleFile (2)", true);

            if ($Rsg2DebugActive) {
                JLog::add('<== uploadAjaxSingleFile');
            }

        } catch (Exception $e) {
            echo new JResponseJson($e);
        }

        $app->close();
    }

	// $this->ajaxDummyAnswerOK (); return; // 01

	/**/
    private function ajaxDummyAnswerOK ()
    {
	    $msg = "uploadAjaxSingleFile (2)";
	    $ajaxImgObject['file'] = 'DSC_1043.jpg'; // $dstFile;
	    // some dummy data for error messages
	    $ajaxImgObject['cid']  = 1043;
	    //$ajaxImgObject['dstFile'] = $urlThumbFile; // $dstFileUrl ???
	    $ajaxImgObject['dstFile'] = 'http://127.0.0.1/joomla3x/images/rsgallery/thumb/DSC_1043.JPG';
	    echo new JResponseJson($ajaxImgObject, $msg, false);

	    $app = JFactory::getApplication();
	    $app->close();
	    return;
    }
    /**/


}

