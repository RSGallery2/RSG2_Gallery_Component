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
 *
 *
 * @since 4.3.0
 */
class rsgallery2ModelMaintConsolidateDB extends JModelList
{

	/**
	 * @var ImageReferences
	 */
	protected $ImageReferences;

	/**
	 * @return ImageReferences
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
	 * Runs optimization for each table
	 *
	 * @return string operation messages
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
	 * @return bool
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
	 *
	 *
	 * throws exception
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function SelectedImageReferences()
	{

		$ImageReferences = array();

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

		/**
        if (!$ImageReferences instanceof ImageReference [])
        {
        continue;
        }
        /**/

		$ImageReferenceList = html_entity_decode($ImageReferenceList, ENT_QUOTES, 'UTF-8');
		$ImageReferenceList = json_decode($ImageReferenceList);

		//$UseWatermarked = $ImageReferenceList->UseWatermarked;
		//$ImageReferences = $ImageReferenceList->ImageReferences;
//            if (!is_array ($ImageReferences)) {
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

		// each selected image row
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

