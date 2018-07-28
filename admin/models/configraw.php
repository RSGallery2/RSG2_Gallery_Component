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
class Rsgallery2ModelConfigRaw extends JModelList
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
	/**
	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**/

	/**
	 * save raw ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
    */
	public function save()
	{
		// $msg = "Rsgallery2ModelConfigRaw: ";
		$isSaved = false;

		$input = JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');
		$data = $input->post->get('jform', array(), 'array');

		// ToDo: Remove bad injected code

		// ToDo: Try ...

		//$row = $this->getTable();
		$Rsg2Id = JComponentHelper::getComponent('com_rsgallery2')->id;
		$table  = JTable::getInstance('extension');
		$table->load($Rsg2Id);
		//$table->bind(array('params' => $data->toString()));
		$table->bind(array('params' => $data));

		// check for error
		if (!$table->check())
		{
			JFactory::getApplication()->enqueueMessage(JText::_('ConfigRaw: Check for save failed ') . $table->getError(), 'error');
		}
		else
		{
			// Save to database
			if ($table->store())
			{
				$isSaved = false;
			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('ConfigRaw: Store for save failed ') . $table->getError(), 'error');
			}
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
	public function copyOld2New()
	{
		$isCopied = false;

		try
		{
			// ToDo: Read From DB

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			//$query->select('*')
			$query->select($db->quoteName(array('name', 'value')))
				->from($db->quoteName('#__rsgallery2_config'));

            $db->setQuery($query);
	        if ($db->execute())
	        {
		        //$OldParameters = $db->loadAssocList();
		        //$OldParameters = $db->loadAssocList();
		        //$OldParameters = $db->loadResult();
		        $OldParameters = $db->loadAssocList('name', 'value');
	        if (empty ($OldParameters))
		        {
			        JFactory::getApplication()->enqueueMessage(
				        'Config: copyOld2New failed. Old Parameters not found', 'error');
		        }
		        else
		        {
			        /**
			         * foreach ($vars as $v) {
			         * if ($v['name'] != "") {
			         * // $this->$v['name'] = $v['value'];
			         * $k = $v['name'];
			         * $this->$k = $v['value'];
			         * }
			         * }
			         * /**/

			        //$type = 'Config', $prefix = 'Rsgallery2Table', $config = array()
			        //
			        //JTable::getInstance($type, $prefix, $config);


			        //$OldParameters = new rsgConfig(); // JComponentHelper::getParams('com_rsgallery2');

			        /**
			         * foreach ($OldParameters as $name => $value)
			         * {
			         * configInputField($name, $value);
			         * }
			         * /**/

			        // ToDo: Try ...

			        //$row = $this->getTable();
			        $Rsg2Id = JComponentHelper::getComponent('com_rsgallery2')->id;
			        $table  = JTable::getInstance('extension');
			        $table->load($Rsg2Id);
			        //$table->bind(array('params' => $data->toString()));
			        $table->bind(array('params' => $OldParameters));

			        // check for error
			        if (!$table->check())
			        {
				        JFactory::getApplication()->enqueueMessage(JText::_('ConfigRaw: Check for save failed ') . $table->getError(), 'error');
			        }
			        else
			        {
				        // Save to database
				        if ($table->store())
				        {
					        $isSaved = true;
				        }
				        else
				        {
					        JFactory::getApplication()->enqueueMessage(JText::_('ConfigRaw: Store for save failed ') . $table->getError(), 'error');
				        }
			        }
		        }
	        }
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing copyOld2New: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isCopied;
	}

	/**
	 *  ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function copyNew2Old()
	{
		$isCopied = false;

		$ConfigParameter = JComponentHelper::getParams('com_rsgallery2');


		return $isCopied;
	}

	/**
	 *  ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function createConfigTextFile($ConfigParameter)
	{
		$isSaved = false;

		ksort($ConfigParameter);

		echo '<br>Config: '	. json_encode($ConfigParameter) . '<br>' . '<br>';

		try
		{
			// Create unique upload directory and store it for cleanup at the end.
			//$tmpDir = uniqid('rsgUpload_'); // 'rsginstall_'
			//$extractDir = JPath::clean(JPATH_ROOT . '/media/' . $tmpDir );

			$fileName = JPath::clean(JPATH_ROOT . '/media/RSGallery2_Configuration.txt');
			$cfgFile = fopen($fileName, "w"); // or die("Unable to open file!");
			if ($cfgFile)
			{
				fwrite($cfgFile, json_encode($ConfigParameter, JSON_PRETTY_PRINT));
				fclose($cfgFile);

				$isSaved = true;
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing save createConfigTextFile: "' . '<br>';
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
	public function readConfigTextFile()
	{
		$isSaved = false;

//		// Prepare merge
//		$ConfigParameter = JComponentHelper::getParams('com_rsgallery2');
//		$ConfigParameter = $ConfigParameter->toArray();
///		ksort($ConfigParameter);

//		echo '<br>Config: '	. json_encode($ConfigParameter) . '<br>' . '<br>';

		try
		{
			$fileName = JPath::clean(JPATH_ROOT . '/media/RSGallery2_Configuration.txt');
			$content  = file_get_contents($fileName);
			if ($content)
			{
				$data = json_decode($content, true);

				// Db connection
				$Rsg2Id = JComponentHelper::getComponent('com_rsgallery2')->id;
				$table  = JTable::getInstance('extension');
				$table->load($Rsg2Id);

				// Insert data
				//$table->bind(array('params' => $data->toString()));
				$table->bind(array('params' => $data));

				// check for error
				if (!$table->check())
				{
					JFactory::getApplication()->enqueueMessage(JText::_('ConfigRaw readConfigTextFile: Check for save failed ') . $table->getError(), 'error');
				}
				else
				{
					// Save to database
					if ($table->store())
					{
						$isSaved = false;
					}
					else
					{
						JFactory::getApplication()->enqueueMessage(JText::_('ConfigRaw readConfigTextFile: Store for save failed ') . $table->getError(), 'error');
					}
				}
			}
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing save createConfigTextFile: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}

	/**
	 * Remove all elements of old J2.5 config data
	 * Truncates not used table
	 *
	 * @return bool (successful or not)
	 *
	 * @since 4.3.0
	 * @throws Exception
	 */
	public function removeOldConfigData()
	{
		$isRemoved = False;

		// ToDo: Move message to controller, return true or false

		try
		{
			$table = '__rsgallery2_config';
			$db = JFactory::getDbo();
			$db->truncateTable($table);

			$isRemoved = true;
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing removeOldConfigData: table="' . $table. '"<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isRemoved;
	}
}
