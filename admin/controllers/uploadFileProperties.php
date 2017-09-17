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
class Rsgallery2ControllerUploadFileProperties extends JControllerForm
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


    public function assignDroppedImages () // ToDo: rename to prepareDroppedImages
    {
	    global $Rsg2DebugActive;

	    if($Rsg2DebugActive)
	    {
		    JLog::add('==> ctrl.uploadFileProperties.php/assignDroppedImages');
	    }

	    $msg     = "controller.assignDroppedImages: ";
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
            $fileSessionId = $input->get('installer-token', '', 'STRING');
            echo $actSessionId . '<br>';
            echo $fileSessionId . '<br> <br> <br> <br>';

            //$this->isInOneGallery = $input->get('isInOneGallery', null, 'INT');
            $this->isInOneGallery = $input->get('selcat', null, 'INT');
            //$this->galleryId = $input->get('GalleryId', null, 'INT');
            $this->galleryId = $input->get('xcat', null, 'INT');
            //$this->fileSessionId = $input->get('session_id', '', 'STRING');
            $this->fileSessionId = $input->get('installer-token', '', 'STRING');

            // ToDo: Remove or change message ? 14 detected files ... ?
            $msg = 'assignDroppedImages';
		    $this->setRedirect('index.php?option=com_rsgallery2&view=UploadFileProperties'
                . '&isInOneGallery=' . $this->isInOneGallery
                . '&galleryId=' . $this->galleryId
                . '&sessionId=' . $this->fileSessionId
                , $msg);
	    }
    }

    public function assign2Gallery ()
    {
        // Get input ...

	    global $Rsg2DebugActive;
        $dbgMessage = '';
        
	    if($Rsg2DebugActive)
	    {
		    JLog::add('==> ctrl.uploadFileProperties.php/assign2Gallery');
	    }

	    $msg     = "controller.assignDroppedImages: ";
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

            //--- arrays -------------------------------------------'

            // FileName
            $FileNameX = $input->get('FileNameX', array(), 'ARRAY');
            $FileName = $input->get('FileName', array(), 'ARRAY');

            $dbgMessage .= '$FileNameX: ' . json_encode($FileNameX). '<br>';
            $dbgMessage .= '$FileName: ' . json_encode($FileName). '<br>';

            // title
            $titleX = $input->get('titleX', array(), 'ARRAY');
            $title = $input->get('title', array(), 'ARRAY');

            $dbgMessage .= '$titleX: ' . json_encode($titleX). '<br>';
            $dbgMessage .= '$title: ' . json_encode($title). '<br>';

            // gallery ID
            $galleryIdX = $input->get('galleryIdX', array(), 'ARRAY');
            $galleryId = $input->get('galleryId', array(), 'ARRAY');

            $dbgMessage .= '$galleryIdX: ' . json_encode($galleryIdX). '<br>';
            $dbgMessage .= '$galleryId: ' . json_encode($galleryId). '<br>';

            // Description

            $descriptionX = $input->get('descriptionX', array(), 'ARRAY');
            $description = $input->get('description', array(), 'ARRAY');

            $dbgMessage .= '$descriptionX: ' . json_encode($descriptionX). '<br>';
            $dbgMessage .= '$description: ' . json_encode($description). '<br>';

            // ToDO: set redirect to images in gallery ?
		    //$this->setRedirect('index.php?option=com_rsgallery2&view=????', $msg, $msgType);

            $msg = 'assign2Gallery';
            $msg .= '<br><br>' . $dbgMessage;
            $this->setRedirect('index.php?option=com_rsgallery2&view=UploadFileProperties'
                . '&isInOneGallery=' . $isInOneGallery
                . '&galleryId=' . $galleryIdX[0]
                . '&sessionId=' . $fileSessionId
                , $msg);
	    }
    }
}

