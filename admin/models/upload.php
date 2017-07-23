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
     *
     *
     * @since 4.3
     */
    function uploadFromFtpFolder($ftpPath, $galleryId, $isInOneGallery)
    {
        global $Rsg2DebugActive;

        $isUploaded = false;
/**
        $msg     = "viewConfigPlain: ";
        $msgType = 'notice';

        $msg .= '!!! Not implemented yet !!!';

        /**
        //Retrieve data from submit form
        $input       = JFactory::getApplication()->input;
        $selcat      = $input->get('selcat', null, 'INT');
        $ftppath = $input->get('ftppath', null, 'RAW');
        // Path should end with '\\'
        if (substr($ftppath, -1) != '/' && substr($ftppath, -1) == '\\')
        {
            $ftppath .= '/';
        }


        if ($Rsg2DebugActive)
        {
            $Delim = " ";
            // show active parameters
            $DebTxt = "==> upload.uploadFromZip.php$Delim----------$Delim";
            $DebTxt = $DebTxt . "\$ftppath: " . $ftppath . "$Delim";
            $DebTxt = $DebTxt . "\$selcat: " . $selcat . "$Delim";

            JLog::add($DebTxt); //, JLog::DEBUG);
        }


        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_('uploadFromFtpFolder'));


        $this->setRedirect('index.php?option=com_rsgallery2&view=upload', $msg, $msgType);
        /**/

        return $isUploaded;
    }
    /**/


}

 