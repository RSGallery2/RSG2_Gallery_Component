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

class Rsgalleries2ControllerGalleries extends JControllerAdmin
{

	public function getModel($name = 'Gallery', 
 							 $prefix = 'rsg2Model', 
 							 $prefix = 'Rsgallery2Model',
  							 $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
 
}