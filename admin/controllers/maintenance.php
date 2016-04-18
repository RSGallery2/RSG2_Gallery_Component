



<?php
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintenanc.php ');
}


jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerMaintenance extends JControllerAdmin
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

	public function Cancel ()
	{
        /*
        global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function Cancel');
		}

		$msg = 'All RSG2 Images and thumbs are deleted. ';
		// $app->redirect($link, $msg, $msgType='message');
        */
        $msg = '';
		$msgType = 'notice';

		// ToDo: Use Jroute before link for setRedirect
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

    /**
     * Checks if user has root status (is re.admin')
     *
     * @return	bool
     */
    function IsUserRoot ()
    {
        $user = JFactory::getUser();
        $canAdmin = $user->authorise('core.admin');
        return $canAdmin;
    }


	function ConsolidateDatabase ()
	{
		global $Rsg2DebugActive;

		if($Rsg2DebugActive)
		{
			JLog::add('==> ctrl.maintenance.php/function ConsolidateDatabase');
		}
		
		$msg = 'RSG2 database is consolidated. ';
		$msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	
	
	function consolidateDB()
	{
        $msg = "consolidateDB: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
    }

	function regenerateThumbs()
	{
        $msg = "regenerateThumbs: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function viewConfigPlain()
	{
        $msg = "viewConfigPlain: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	function editConfigRaw()
	{
        $msg = "editConfigRaw: ";
        $msgType = 'notice';

		$msg .= '!!! Not implemented yet !!!';

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

}


