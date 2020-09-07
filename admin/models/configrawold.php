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
jimport('joomla.application.component.helper');

/**
 * Handle RAW display of configuration
 *
 *
 * @since 4.3.0
 */
//class Rsgallery2ModelConfigRaw extends JModelLegacy  // JModelForm // JModelAdmin // JModelList // JModelItem
//class Rsgallery2ModelConfigRaw extends JModelAdmin  // JModelForm
class Rsgallery2ModelConfigRawOld extends JModelList
{
	/**
	 * @param string $type
	 * @param string $prefix
	 * @param array  $config
	 *
	 * @return mixed
	 *
	 * @since 4.3.0
	 */
	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * save raw ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
    */
	public function saveOld()
	{
	    // ToDO: Move message to controller, return true or false

		$msg = "Rsgallery2ModelConfigRaw: ";

		$input = JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');
		$data = $input->post->get('jform', array(), 'array');

        // ToDo: Remove bad injected code

		$row = $this->getTable();
		foreach ($data as $key => $value)
		{
            // fill an array, bind and check and store ?
			$row->id    = null;
			$row->name  = $key;
			$row->value = $value;

			$row->check();
			$row->store();
		}

		return $msg;
	}


	/**
	 *  ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function createConfigTextFileOld()
	{
		global $rsgConfig;

		$isSaved = false;

		$ConfigParameter = new rsgConfig(); // JComponentHelper::getParams('com_rsgallery2');
		$ConfigParameter = get_object_vars($ConfigParameter);
		ksort($ConfigParameter);
		//sort();

		echo '<br>Config: '	. json_encode($ConfigParameter) . '<br>' . '<br>';

		try
		{
			$fileName = JPATH_ADMINISTRATOR . '/logs/RSGallery2_ConfigurationOld.txt';
			$cfgFile = fopen($fileName, "w") or die("Unable to open file!");

//			$txt = "RSGallery2 Configuration Old\n";
//			fwrite($cfgFile, $txt);

			fwrite($cfgFile, json_encode($ConfigParameter, JSON_PRETTY_PRINT));
			fclose($cfgFile);

			$isSaved = true;
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing save createConfigTextFileOld (Old): "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}


	/**
	 *  ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function readConfigTextFileOld()
	{
		global $rsgConfig;

		$isSaved = false;

//		// Prepare merge
//		$ConfigParameter = new rsgConfig(); // JComponentHelper::getParams('com_rsgallery2');
//		$ConfigParameter = get_object_vars($ConfigParameter);
//		ksort($ConfigParameter);

//		echo '<br>Config: '	. json_encode($ConfigParameter) . '<br>' . '<br>';

		try
		{
			$fileName = JPATH_ADMINISTRATOR . '/logs/RSGallery2_ConfigurationOld.txt';
			$cfgFile = fopen($fileName, "w") or die("Unable to open file!");
			$content = file_get_contents($fileName);
			$data = json_decode($content, true);

			$row = $this->getTable();
			foreach ($data as $key => $value)
			{
				// fill an array, bind and check and store ?
				$row->id    = null;
				$row->name  = $key;
				$row->value = $value;

				$row->check();
				$row->store();
			}

		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing save createConfigTextFileOld (Old): "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}

	/**
	 * save raw ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function removeOldConfigData()
	{
		// ToDO: Move message to controller, return true or false

		$msg = "removeOldConfigData: ";

		/**
		$input = JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');
		$data = $input->post->get('jform', array(), 'array');

		// ToDo: Remove bad injected code

		$row = $this->getTable();
		foreach ($data as $key => $value)
		{
			// fill an array, bind and check and store ?
			$row->id    = null;
			$row->name  = $key;
			$row->value = $value;

			$row->check();
			$row->store();
		}
		/**/


		$OutTxt = '';
		$OutTxt .= 'removeOldConfigData (Old): "' . '<br>';

		$app = JFactory::getApplication();
		$app->enqueueMessage($OutTxt, 'error');

		return $msg;
	}


}
