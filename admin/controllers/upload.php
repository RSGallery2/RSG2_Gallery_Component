<?php
defined('_JEXEC') or die;

// control center 
// ToDo:: rename to rsg_control and use as default ...

global $Rsg2DebugActive;

$Rsg2DebugActive = false; // ToDo: $rsgConfig->get('debug');
if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');
	
	// identify active file
	JLog::add('==> ctrl.ctrl.upload.php ');
}

jimport('joomla.application.component.controllerform');

// ToDo: Check which functions are used

class Rsgallery2ControllerUpload extends JControllerForm
{
	


}

