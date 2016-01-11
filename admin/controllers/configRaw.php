<?php
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
//	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.configRaw.php ');
}

jimport('joomla.application.component.controllerform');

class Rsg2ControllerConfigRaw extends JControllerForm
{

	public function cancel($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsg2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}





}

