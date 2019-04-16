<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2018 RSGallery2 Team
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
	JLog::add('==> ctrl.maintenanc.php ');
}

jimport('joomla.application.component.controlleradmin');

/**
 * some more general functions for maintenance
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintenance extends JControllerAdmin
{

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
     *
	 * @since 4.3
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Move to maintenance main page on canlel
     * May be issued from other sub forms like maintconsolidatedb
     *
     * @since 4.3
     */
	public function Cancel()
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
		$msg     = '';
		$msgType = 'notice';

		// ToDo: Use Jroute before link for setRedirect :: check all apperances
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}


    /**
     * Delete RSGallery language files in joomla 1.5 version or older style for backend
     * recursive files search in backend folder
     *
     * @since 4.3
     */
	function delete_base_LangFiles()
	{
		$msg     = "Delete base language files: ";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			try
			{
				// .../administrator/language/
				$startDir  = JPATH_ADMINISTRATOR . '/language';
				$IsDeleted = $this->findAndDelete_RSG2_LangFiles($startDir);
				if ($IsDeleted)
				{
					$msg .= " path Admin successful";
				}

				// .../administrator/language/
				$startDir  = JPATH_SITE . '/language';
				$IsDeleted = $this->findAndDelete_RSG2_LangFiles($startDir);
				if ($IsDeleted)
				{
					$msg .= " path Admin successful";
				}

			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing delete_base_LangFiles: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}
		}
		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}

	/**
     * Delete RSGallery language files in joomla 1.5 version style or older style starting on given folder
     * recursive files search in starting folder
     *
     * @param $startDir Example: \administrator\language\
	 * @return bool True on delete successful False otherwise
	 *
	 * @since 4.3
	 */
	function findAndDelete_RSG2_LangFiles($startDir)
	{
		$IsDeleted = false;

		if ($startDir != '')
		{
			// ...original function code...
			// ...\en-GB\en-GB.com_rsgallery2.ini
			// ...\en-GB\en-GB.com_rsgallery2.sys.ini

			$Directories = new RecursiveDirectoryIterator($startDir, FilesystemIterator::SKIP_DOTS);
			$Files       = new RecursiveIteratorIterator($Directories);
			$LangFiles   = new RegexIterator($Files, '/^.+\.com_rsgallery2\..*ini$/i', RecursiveRegexIterator::GET_MATCH);

			$msg         = '';
			$IsFileFound = false;
			foreach ($LangFiles as $LangFile)
			{
				$IsFileFound = true;

				$msg .= '<br>' . $LangFile[0];
				$IsDeleted = unlink($LangFile[0]);
				if ($IsDeleted)
				{
					$msg .= ' is deleted';

				}
				else
				{
					$msg .= ' is not deleted';
				}
			}

			// One or more files found ?
			if ($IsFileFound)
			{
				// $IsDeleted = true;
				$msg = 'Found files: ' . $msg;
			}
			else
			{
				$msg .= 'OK: No files needed to be deleted: ';
			}

			JFactory::getApplication()->enqueueMessage($msg, 'notice');
		}

		return $IsDeleted;
	}

	/**
	 *
	 *
	 * @since 4.4.2
	 */
	function repairImagePermissions()
	{
		//$msg     = "repairImagePermissions: ";
		$msg = "Repaired image permissions: <br>";
		$msgType = 'notice';

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Access check
		$canAdmin = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
		if (!$canAdmin)
		{
			$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR');
			$msgType = 'warning';
			// replace newlines with html line breaks.
			str_replace('\n', '<br>', $msg);
		}
		else
		{
			//--- Delete all images -------------------------------

			try
			{
				$imageModel = $this->getModel('MaintImageFiles');
				$msg        .= $imageModel->repairImagePermissions();
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing repairImagePermissions: "' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');
			}
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=maintenance', $msg, $msgType);
	}


} // class


