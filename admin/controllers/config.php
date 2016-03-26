<?php
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
//	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.config.php ');
}

jimport('joomla.application.component.controllerform');

class Rsgallery2ControllerConfig extends JControllerForm
{
    /**
     * Proxy for getModel.
     * /
    public function getModel($name = 'Config', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
	/**/
	
	public function cancel($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link);

		return true;
	}

	public function cancel_rawView($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

	public function cancel_rawEdit($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}
	
    public function save($key = null, $urlVar = null) {
		$model = $this->getModel('Config');
		$item=$model->save($key);

		//$this->setRedirect(JRoute::_('index.php?option=com_portfoliogallery&view=portfoliogalleries', false),"Saved");
		$link = 'index.php?option=com_rsgallery2';
		$this->setRedirect($link, false),"*Data Saved");
    }  	
	
	function apply(){
		 $model = $this->getModel('Config');
		 $item=$model->save('');
		 
		// $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=config', false), "*Data Saved");
		$link = 'index.php?option=com_rsgallery2&view=config';
		$this->setRedirect($link, false),"*Data Saved");
    }  
}

