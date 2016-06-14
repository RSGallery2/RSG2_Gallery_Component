<?php
defined('_JEXEC') or die;

global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.maintConsolidateDb.php ');
}

jimport('joomla.application.component.controlleradmin');

class Rsgallery2ControllerMaintConsolidateDb extends JControllerAdmin
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

    public function getModel($name = 'xMaintCleanUp',
 							 $prefix = 'rsgallery2Model', 
  							 $config = array())
	{
		$config ['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * images to ...
	 *
	 */
	public function createDbEntries () {
		JFactory::getApplication()->enqueueMessage('!!! createDbEntries <br> ', 'warning');

		$msg = "createDbEntries: ";
		$msgType = 'notice';
		$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		if (!$canAdmin) {
			//JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$msg = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		} else {
			
			
			
			
		}

		$this->setRedirect('index.php?option=com_rsgallery2&amp;rsgOption=maintenance&amp;task=consolidateDB', $msg, $msgType);
	}

	
	
	
	
}
