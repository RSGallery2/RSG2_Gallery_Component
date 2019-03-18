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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

// access to the content of the install.mysql.utf8.sql file
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/classes/ImageReferences.php');

/**
 * maintenance delete left over image files
 *
 * Checks for all appearances of images left on upload
 * for found files the user gets a list to select deletable files
 *
 * @since 4.4.2
 */
class rsgallery2ModelMaintRemoveInstallLeftOvers extends JModelList
{
	/**
     * Image artifacts as list
     * Each entry contains existing image or folder object where at least one file is found
     *
	 * @var ImageReferences
     *
     * @since 4.4.2
     */
	protected $FolderReferences;

    /**
     * Returns List of not deleted upload folders
     *
     * @return FolderReferences
     *
     * @since 4.4.2
     */
	public function GetLeftOverFolderReferences()
	{
		if (empty($this->FolderReferences))
		{
			$this->CreateFolderReferences();
		}

		return $this->FolderReferences;
	}

	/**
	 * Collects folder references as list
     * Each entry referes to a left over upload folder
	 *
	 * @return string [] folders
     *
     * @since 4.4.2
	 * @throws Exception
	 */
	public function CreateFolderReferences()
	{
		$FolderReferences = [];

		try
		{
			$mediaPath =  JPATH_ROOT . '/media';

			$search = $mediaPath . '/rsginstall_*';
			$Folders_01 = glob($search , GLOB_ONLYDIR);

			$search = $mediaPath . '/rsgUpload_*';
			$Folders_02 = glob($search , GLOB_ONLYDIR);

			// array_push, $ergebnis = array_merge($array1, $array2);
			// $ergebnis = $array1 + $array2;

			$FolderReferences = array_merge($Folders_01, $Folders_02);
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing CreateFolderReferences: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		$this->FolderReferences = $FolderReferences;

		return $FolderReferences;
	}

	/**
	 * Tells if watermark is activated on user config
	 *
	 * @return bool true when set in config data
     *
     * @since 4.4.2
     */
	public function IsWatermarkActive()
	{
		if (empty($this->IsWatermarkActive))
		{
			$this->IsWatermarkActive = false;

			try
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select($db->quoteName('value'))
					->from($db->quoteName('#__rsgallery2_config'))
					->where($db->quoteName('name') . " = " . $db->quote('watermark'));
				$db->setQuery($query);

				$this->IsWatermarkActive = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				$OutTxt = '';
				$OutTxt .= 'Error executing query: "' . $query . '"' . '<br>';
				$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'error');

			}
		}

		return $this->IsWatermarkActive;
	}

	/**
	 * SelectedImageReferences
	 *
     * Checks the input for checkboxes set
     *
	 * throws exception
	 *
	 * @return array
	 *
	 * @since 4.4.2
	 */
	public function SelectedImageReferences()
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
		$ImageReferenceList = $input->getString('ImageReferenceList');
		if (empty ($ImageReferenceList))
		{
			$OutTxt = 'Retrieved no image reference items from input';
			// $OutTxt .= ': "' . '<br>';

			// $app = JFactory::getApplication();
			//$app->enqueueMessage($OutTxt, 'error');

			// return -1;
			throw new RuntimeException($OutTxt);
		}

		// Create objects as class ImageReferenceList
		$ImageReferenceList = html_entity_decode($ImageReferenceList, ENT_QUOTES, 'UTF-8');
		$ImageReferenceList = json_decode($ImageReferenceList);

        // Data is an array
		if (!is_array($ImageReferenceList))
		{
			$OutTxt = 'Format of image reference items wrong';
			// $OutTxt .= ': "' . '<br>';

			//$app = JFactory::getApplication();
			//$app->enqueueMessage($OutTxt, 'error');

			$OutTxt .= '->' . $ImageReferenceList;
			// return -1;
			throw new RuntimeException($OutTxt);
		}

		$imgRefCount = count($ImageReferenceList);

		//--- collect only the selected ones -------------------------------

		// each image row
		foreach ($cids as $imgIdx)
		{
			// out of range ?
			if ($imgIdx < 0 || $imgRefCount <= $imgIdx)
			{
				$OutTxt = 'Selected index: ' . $imgIdx . ' is out of range';
				// $OutTxt .= ': "' . '<br>';

				$app = JFactory::getApplication();
				$app->enqueueMessage($OutTxt, 'notice');

				continue;
			}

			$ImageReferences[] = $ImageReferenceList [$imgIdx];
		}

		return $ImageReferences;
	}

}

