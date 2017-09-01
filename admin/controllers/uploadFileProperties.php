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


    public function assignDroppedImages ()
    {
	    global $Rsg2DebugActive;

	    if($Rsg2DebugActive)
	    {
		    JLog::add('==> ctrl.maintenance.php/function Cancel');
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
            $remoteSessionId = $input->get('return', '', 'STRING');
            echo $actSessionId . '<br>';
            echo $remoteSessionId . '<br> <br> <br> <br>';

            //$this->isInOneGallery = $input->get('isInOneGallery', null, 'INT');
            $this->isInOneGallery = $input->get('selcat', null, 'INT');
            //$this->galleryId = $input->get('GalleryId', null, 'INT');
            $this->galleryId = $input->get('xcat', null, 'INT');
            //$this->fileSessionId = $input->get('session_id', '', 'STRING');
            $this->fileSessionId = $input->get('return', '', 'STRING');

            $msg = 'assignDroppedImages';
		    $this->setRedirect('index.php?option=com_rsgallery2&view=UploadFileProperties'
                . '&isInOneGallery=' . $this->isInOneGallery
                . '&galleryId=' . $this->galleryId
                . '&sessionId=' . $this->fileSessionId
                , $msg);
	    }

    }



}

