<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2023 RSGallery2 Team
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
	JLog::add('==> ctrl.maintCleanUp.php ');
}

jimport('joomla.application.component.controlleradmin');

/**
 * Clean up / remove of RSGallery2 data and files
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintRemoveLogFiles extends JControllerAdmin
{

    /**
     * Constructor.
     *
     * @param   array $config An optional associative array of configuration settings.
     *
     * @since 4.5.0.0
     */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Deletes rsgallery2 log files in \logs and \administrator\logs
     *
     * @since 4.5.0.0
     */
	function DeleteLogFiles()
	{
		$msg     = ""; // "DeleteLogFiles: ";
		$msgType = 'notice';


		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			$msg = nl2br ($msg);
		}
		else
		{
			// ToDo: move to model

			//$msg .= "<br> Controller MaintRemoveLogFiles: Not ready yet <br>";
			$msg .= "Delete rsgallery2 log files: <br>";

			$logFiles = $this->CollectRsg2LogFiles();
			// $msg      .= implode(' <br>', $logFiles);

			foreach ($logFiles as $fileName)
			{
				$isDeleted = False;

				try
				{
					// last checks for not deleting bad paths
					if (strlen($fileName) > strlen(JPATH_ROOT))
					{
						if (strpos(strtolower($fileName), "rsgallery") !== false)
						{
							if (file_exists($fileName))
							{
								$isDeleted = unlink($fileName);
							}
						}
					}
				}
				catch (RuntimeException $e)
				{
					$OutTxt = '';
					$OutTxt .= 'Error executing DeleteLogFiles: "' . '<br>';
					$OutTxt .= 'Failed to delete: "' . $fileName . '"' . '<br>';
					$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

					$app = JFactory::getApplication();
					$app->enqueueMessage($OutTxt, 'error');
				}

				if ($isDeleted)
				{
					$msg .= 'Deleted: "' . $fileName . '"<br>';
				}
				else
				{
					$msg .= 'Not deleted: "' . $fileName . '"<br>';
				}
			}
		}

		$app = JFactory::getApplication();
		$app->enqueueMessage($OutTxt, 'notice');

		$this->setRedirect('index.php?option=com_rsgallery2&view=Maintenance', $msg, $msgType);
	}


	/**
	 * Collects names and paths of rsgallery2 log files in \logs and \administrator\logs
	 * @return array
	 *
	 * @since 4.5.0.0
	 * @throws Exception
	 */
	public function CollectRsg2LogFiles()
	{
		$files = [];

		//--- collect in admin logs folder --------------
		try
		{
			$folder         = JPATH_ADMINISTRATOR . '/logs';
			$filesAdminLogs = $this->CollectRsg2LogFilesInFolder($folder);

			$folder    = JPATH_ROOT . '/logs';
			$filesLogs = $this->CollectRsg2LogFilesInFolder($folder);

			$files = array_merge($filesLogs, $filesAdminLogs);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing CollectRsg2LogFiles: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $files;
	}

	/**
	 * Collects rsgallery2 log files in given folder
	 *
	 * @param $folder
	 *
	 * @return array
	 *
	 * @since 4.5.0.0
	 */
	public function CollectRsg2LogFilesInFolder($folder)
	{
		$ImageReferences = array();

		//--- collect in admin logs folder --------------

		$files = scandir($folder);
		foreach ($files as $file)
		{
			//
			if (strpos($file, 'rsgallery2') !== false && strpos($file, 'log') !== false )
			{
				// $ImageReferences [] = $file;
				$ImageReferences [] = $folder . '/' . $file;
			}
		}

		return $ImageReferences;
	}

}

