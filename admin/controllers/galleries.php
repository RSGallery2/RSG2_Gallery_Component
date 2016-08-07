<?php
defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.galleries.php ');
}
/**/

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerGalleries extends JControllerAdmin
{

	public function getModel($name = 'Gallery', 
 							 $prefix = 'Rsgallery2Model',
  							 $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}


	public function saveOrdering ()
	{
		//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
		$msg = "saveOrder: ";
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';





		$this->setRedirect('index.php?option=com_rsgallery2&view=galleries', $msg, $msgType);
	}
 
}

