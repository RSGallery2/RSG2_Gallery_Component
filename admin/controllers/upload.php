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

        $msg     = "uploadFromZip: ";
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';
        
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin)
        {
            $msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        }
        else {
            try {
                //Retrieve data from submit form
                $input = JFactory::getApplication()->input;
                //	$zip_file       = $input->files->get('zip_file', array(), 'FILES');
                // 'FILES' is ignored as a *.zip file marked bad from function  isSafeFile inside get
                $zip_file = $input->files->get('zip_file', array(), 'raw');
                $isInOneGallery = $input->get('isInOneGallery', null, 'INT');
                $galleryId = $input->get('GalleryId', null, 'INT');

                if ($Rsg2DebugActive) {
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

                // Model tells if successful
                $model = $this->getModel('Upload');
                $isUploaded = $model->uploadFromZip ($zip_file, $galleryId, $isInOneGallery);
                if ($isUploaded) {
                    // ToDo: Use FTP upload successful ?
                    $msg .= JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');
                }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from Zip file failed');
                    $msgType = 'error';
                }




            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing uploadFromZip: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('uploadFromZip'));

        //$this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
        $this->setRedirect('index.php?option=com_rsgallery2&amp;view=upload&amp;layout=UploadSingle', $msg, $msgType);
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

        $msg     = "";
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';
        
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            try {
                //Retrieve data from submit form
                $input = JFactory::getApplication()->input;
                // One gallery for all image:
                // ToDo: rename in view and here
                $isInOneGallery = $input->get('selcat', null, 'INT');
                // image ID:
                // ToDo: rename in view and here
                $galleryId = $input->get('xcat', null, 'INT');
                $ftpPath = $input->get('ftppath', null, 'RAW');
                // Path should end with '\\'
                if (substr($ftpPath, -1) != '/' && substr($ftpPath, -1) == '\\') {
                    $ftpPath .= '/';
                }

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
                $app->setUserState('com_rsgallery2.last_used_ftp_path', $ftppath);
                $rsgConfig->setLastUsedFtpPath($ftppath);
                $rsgConfig->setLastUpdateType('upload_folder_server');

                // Model tells if successful
                $model = $this->getModel('Upload');
                $isUploaded = $model->uploadFromFtpFolder ($ftpPath, $galleryId, $isInOneGallery);
                if ($isUploaded) {
                    // ToDo: Use FTP upload successful ?
                    $msg .= JText::_('COM_RSGALLERY2_ITEM_UPLOADED_SUCCESFULLY');
                }
                else
                {
                    // COM_RSGALLERY2_ERROR_IMAGE_UPLOAD
                    $msg .= JText::_('Upload from FTP folder failed');
                    $msgType = 'error';
                }



            } catch (RuntimeException $e) {
                $OutTxt = '';
                $OutTxt .= 'Error executing uploadFromFtpFolder: "' . '<br>';
                $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                $app = JFactory::getApplication();
                $app->enqueueMessage($OutTxt, 'error');
            }
        }

        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('uploadFromFtpFolder'));

        $this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
    }
    /**/

    /**
     *
     *
     * @since 4.3
     */
    function uploadAjaxSingleFile()
    {


    }

}

