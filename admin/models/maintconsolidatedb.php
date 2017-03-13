<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
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
 * maintenance consolidate image database
 *
 * Checks for all appearances of a images as file or in database
 * On missing database entries or files the user gets a list
 * to choose which part to fix
 *
 * @since 4.3.0
 */
class rsgallery2ModelMaintConsolidateDB extends JModelList
{

	/**
     * Image artefacts as list
     * Each entry contains existing image objects where at least one is missing
     *
	 * @var ImageReferences
     *
     * @since 4.3.0
     */
	protected $ImageReferences;

    /**
     * Returns List of image "artefacts"
     *
     * @return ImageReferences
     *
     * @since 4.3.0
     */
	public function GetImageReferences()
	{
		if (empty($this->ImageReferences))
		{
			$this->CreateDisplayImageData();
		}

		return $this->ImageReferences;
	}

	/**
	 * Collects image artefacts as list
     * Each entry contains existing image objects where at least one is missing
	 *
	 * @return string operation messages
     *
     * @since 4.3.0
     */
	public function CreateDisplayImageData()
	{
		// ToDo: Instead of message return HasError;
		$msg = ''; //  ": " . '<br>';

		//
		$ImageReferences       = new ImageReferences ();
		$this->ImageReferences = $ImageReferences;

		// Include watermarked files to search and check for missing 
		$ImageReferences->UseWatermarked = $this->IsWatermarkActive();
		$ImageReferences->UseWatermarked = true; // ToDO: remove

		try
		{
			$msg .= $ImageReferences->CollectImageReferences();
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing CollectImageReferences: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $msg;
	}

	/**
	 * Tells if watermark is activated on user config
	 *
	 * @return bool true when set in config data
     *
     * @since 4.3.0
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
	 * @since 4.3.0
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

