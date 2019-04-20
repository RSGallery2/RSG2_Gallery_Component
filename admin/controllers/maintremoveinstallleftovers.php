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
	JLog::add('==> ctrl.maintCleanUp.php ');
}

jimport('joomla.application.component.controlleradmin');

/**
 * Clean up / remove of RSGallery2 data and files
 *
 * @since 4.3.0
 */
class Rsgallery2ControllerMaintRemoveInstallLeftOvers extends JControllerAdmin
{

    /**
     * Constructor.
     *
     * @param   array $config An optional associative array of configuration settings.
     *
     * @since 4.3.0
     */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Deletes all images and removes them from database
     *
     * @since 4.3.0
     */
	function DeleteFromFolderList()
	{
		$msg     = "DeleteFromFolderList: ";
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
			// ToDo: move to model


			//
			$Folders = $this->CollectSelectedFolders();

			$OutTxt = '';
			// each folder row
			foreach ($Folders as $Folder)
			{
				// echo '<br>' . $Folder . '<br>';
				$OutTxt .= $Folder . '<br>';
			}

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'notice');




			/**
			//--- Delete all images -------------------------------

			$imageModel = $this->getModel('MaintImageFiles');
			$msg .= $imageModel->removeAllImageFiles();

			//--- delete images reference in database ---------------

			$imageModel = $this->getModel('MaintDatabaseTables');
			$msg .= $imageModel->removeDataInTables();

			//--- purge message -------------------------------------
			$msg .= '\n' . JText::_('COM_RSGALLERY2_PURGED', true);
			 * /**/
		}

		$this->setRedirect('index.php?option=com_rsgallery2&view=MaintRemoveInstallLeftOvers', $msg, $msgType);
	}


	public function CollectSelectedFolders()
	{

		$ImageReferences = array();

		//--- collect selected checkboxes --------------

		$input = JFactory::getApplication()->input;
		$cids  = $input->get('cid', array(), 'ARRAY');

		if (empty ($cids))
		{
			$OutTxt = 'No items selected';
			// $OutTxt .= ': "' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'notice');

			return $ImageReferences;
		}


		// Retrieve image data objects (Data objects are hidden in form)
		$FolderReferenceList = $input->getString('FolderReferenceList');
		if (empty ($FolderReferenceList))
		{
			$OutTxt = 'Retrieved no folder reference items from input';
			// $OutTxt .= ': "' . '<br>';

			// $app = JFactory::getApplication();
			//$app->enqueueMessage($OutTxt, 'error');

			// return -1;
			throw new RuntimeException($OutTxt);
		}

		// Create objects as class FolderReferenceList
		$FolderReferenceList = html_entity_decode($FolderReferenceList, ENT_QUOTES, 'UTF-8');
		$FolderReferenceList = json_decode($FolderReferenceList);

		// Data is an array
		if (!is_array($FolderReferenceList))
		{
			$OutTxt = 'Format of image reference items wrong';
			// $OutTxt .= ': "' . '<br>';

			//$app = JFactory::getApplication();
			//$app->enqueueMessage($OutTxt, 'error');

			$OutTxt .= '->' . $FolderReferenceList;
			// return -1;
			throw new RuntimeException($OutTxt);
		}

		$folderRefCount = count($FolderReferenceList);

		//--- collect only the selected ones -------------------------------

		// each folder row
		foreach ($cids as $folderIdx)
		{
			// out of range ?
			if ($folderIdx < 0 || $folderRefCount <= $folderIdx)
			{
				$OutTxt = 'Selected index: ' . $folderIdx . ' is out of range';
				// $OutTxt .= ': "' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'notice');

				continue;
			}

			$folderReferences[] = $FolderReferenceList [$folderIdx];
		}

		return $folderReferences;
	}






}
