<?php
defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
//	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.config.php ');
}
/**/

// ToDo: // Sanitize the input

jimport('joomla.application.component.controllerform');

class Rsgallery2ControllerConfig extends JControllerForm
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
     * Proxy for getModel.
     */
    public function getModel($name = 'Config', $prefix = 'Rsgallery2Model', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
	/**/
	
	public function cancel_rawView($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

	public function apply_rawEdit() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "apply_rawEdit: " . '<br>';
		$msgType = 'notice';

		$model = $this->getModel('ConfigRaw');
		$msg .= $model->save();

//		$msg .= '!!! Not implemented yet !!!';

		$link = 'index.php?option=com_rsgallery2&view=config&layout=RawEdit';
		$this->setRedirect($link, $msg, $msgType);
	}

	public function save_rawEdit() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$msg = "save_rawEdit: " . '<br>';
		$msgType = 'notice';

		$model = $this->getModel('ConfigRaw');
		$msg .= $model->save();
		
		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link, $msg, $msgType);
	}

	public function cancel_rawEdit($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$link = 'index.php?option=com_rsgallery2&view=maintenance';
		$this->setRedirect($link);

		return true;
	}

    /** may be automatic
     * @param null $key
     * @return bool
     */
    public function cancel($key = null) {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $link = 'index.php?option=com_rsgallery2';
        $this->setRedirect($link);

        return true;
    }

    function save(){
        parent::save();

        $inTask = $this->getTask();

        if ($inTask != "apply") {
            // Don't go to default ...
            $this->setredirect('index.php?option=com_rsgallery2');
        }
    }


    /**
    public function save($key = null, $urlVar = null) {
		$model = $this->getModel('Config');
		$model->save($key);

        // ToDo: use JRoute::_(..., false)	  ->   $link = JRoute::_('index.php?option=com_foo&ctrl=bar',false);
		//$link = 'index.php?option=com_rsgallery2';
		//$this->setRedirect($link, "*Data Saved");
    }  	
	
	function apply(){
		 $model = $this->getModel('Config');
		 $item=$model->save('');
		 
		// $this->setRedirect(JRoute::_('index.php?option=com_rsgallery2&view=config', false), "*Data Saved");
		$link = 'index.php?option=com_rsgallery2&view=config&amp;task=config.edit';
		$this->setRedirect($link, "*Data Saved");
    }
    /**/
}

