<?php
defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.image.php ');
}
/**/

// ToDo: // Sanitize the input

//jimport('joomla.application.component.controllerform');

class Rsgallery2ControllerGallery extends JControllerForm
{
	/**
    * Save parameters and goto upload
    */
    public function save2upload ()
    {
        $msg = '<strong>' . 'Save2Upload ' . ':</strong><br>';
        $msgType = 'notice';

        // Access check
        $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
        if (!$canAdmin) {
            $msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
            $msgType = 'warning';
            // replace newlines with html line breaks.
            str_replace('\n', '<br>', $msg);
        } else {
            // Model tells if successful
            $model = $this->getModel('maintSql');
            $IsOk = $model->Save();
        }

        if ($IsOk) {
        	$link = 'index.php?option=com_rsgallery2&view=upload';
	        // Tell the upload the id (not used there)
	        $input = JFactory::getApplication()->input;

	        $Id = $input->get( 'id', 0, 'INT');
	        if (! empty ($Id)) {
		        $link .= '&id=' . $Id;
	        }

            $msg .= ' successful';
            $this->setRedirect($link, $msg, $msgType);
        }
        else {
            $msg .= ' failed';
            JFactory::getApplication()->enqueueMessage($msg, 'warning');
        }
    }





}