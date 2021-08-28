<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');

/**
 * Configuration model
 *
 * @since 4.3.0
 */
class Rsgallery2ModelConfig extends JModelAdmin
{
    protected $text_prefix = 'COM_RSGALLERY2';

    //protected $text_prefix = 'COM_RSGallery2';
	//protected $text_prefix = 'RSGallery2';
	protected $IsDebugActive;

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       string $type   The table type to instantiate
	 * @param       string $prefix A prefix for the table class name. Optional.
	 * @param       array  $config Configuration array for model. Optional.
	 *
	 * @return      JTable  A database object
	 * @since       4.3.0
	 */
	/** ToDo: function getTable handles old config: remove later */
	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data     Data for the form.
	 * @param       boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       4.3.0
	 */
	/** ToDo: function getForm handles old config: remove later */
	public function getForm($data = array(), $loadData = true)
	{
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form    = $this->loadForm('com_rsgallery2.config', 'config', $options);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
	/**/

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
     *
	 * @since        4.3.0
	 */
	/** ToDo: function loadFormData handles old config: Assign to new config *
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_rsgallery2.edit.config.data', array());

		if (empty($data))
		{
			// $data = $this->getItem();

			// ToDo: load config data into $data
			$data = $this->loadConfig();
		}

		return $data;
	}
	/**/

	/**
	 * Binds the global configuration variables to the class properties
     *
     * @return array empty or associative name -> value
     *
     * @since  4.3.0
     */
	/** ToDo: function loadConfig handles old config: Assign to new config */
	public function loadConfig()
	{
		$data = array();

		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__rsgallery2_config');
		$db->setQuery($query);

		/**
		if (!$db->execute())
		{
			// database doesn't exist, use defaults
			// for this->name = value association (see below)
			// ToDo: ? May create database table write values and call itself
			return;
		}
		/**/

		$vars = $db->loadAssocList();
		if (!$vars)
		{
			// database doesn't exist, use defaults
			// for this->name = value association (see below)
			// ToDo:  create values from default write values and call itself
			return;
		}

		foreach ($vars as $v)
		{
			if ($v['name'] != "")
			{
				// $this->$v['name'] = $v['value'];
				$k        = $v['name'];
				$data[$k] = $v['value'];
			}
		}

		//------------------------------------------
		// special variables exifTags ...
		//------------------------------------------
		if (isset ($data['exifTags']))
		{
			$data['exifTags'] = explode('|', $data['exifTags']);
		}

		/**  if (isset ($data['allowedFileTypes'])) {
		 * $data['allowedFileTypes'] = explode (',', $data['allowedFileTypes']);
		 * }
		 **/

		return $data;
	}
	/**/

    /**
     * Transform some data before it is displayed ? Saved ?
     * extension development 129 bottom
     *
     * @param JTable $table
     *
     * @since 4.3.0
     */

    /**
     *
     * @param $table
     *
     *
	 * @since 4.3.0
    */
	/** ToDo: function prepareTable hanldes old config: Assign to new config *
	protected function prepareTable($table)
	{
		// $table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
	}
	/**/

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 * @since 4.3.0
     * /
	public function getItem($pk = null)
	{
		$items = parent::getItems(); 
			
		if ($items === false)
		{
			return false;
		}

		// This should be an array with at least one element
		if (!is_array($items) || !isset($items[0]))
		{
			return $items;
		}
 			
 /*	
			// Convert the params field to an array.
			$registry = new Registry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();
	 * /

	
		// Access check
		//$canAdmin	= JFactory::getUser()->authorise('core.admin',	'com_rsgallery2');
		$canManage	= JFactory::getUser()->authorise('core.manage',	'com_rsgallery2');
		if (!$canManage) {
		
			$this->setError(JText::_('JERROR_ALERTNOAUTHOR'));

			return false;
		}

		// All good, return the items array
		return $items; 		
	}
/**/

	/**
     * Save of variables. Does handle special variables like exif types
     *
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   4.3.0
	 */
	/** ToDo: function save does handle old config: Assign to new config */
	public function save($data) // !!! OLD configuration data !!!
	{
		$isSaved = false;

		if (empty($data))
		{
			// ToDO: Raise ....

			return $isSaved;
		}

		try
		{
			// Special variables
			$row = $this->getTable();
			foreach ($data as $key => $value) #foreach ($input as $key => $value)
			{
				/*
				 */
				$row->id    = null;
				$row->name  = $key;
				$row->value = $value;

				if ($row->name == 'exifTags' && is_array($row->value))
				{
					$row->value = implode('|', $row->value);
				}

				$row->check();
				$row->store();
			}

			$isSaved = true;
		}
		catch (RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing save configuration old: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			$app = JFactory::getApplication();
			$app->enqueueMessage($OutTxt, 'error');
		}

		return $isSaved;
	}
	/**/
}
