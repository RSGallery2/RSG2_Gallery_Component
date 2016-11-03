<?php
// No direct access to this file
defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');
/**
 * 
 */
//class Rsgallery2ModelConfig extends JModelLegacy  // JModelForm
//class rsgallery2ModelConfig extends JModelForm
class Rsgallery2ModelConfig extends JModelAdmin  // JModelForm
//class rsgallery2ModelConfig extends JModelList
{
    //protected $text_prefix = 'COM_RSGallery2';
    //protected $text_prefix = 'RSGallery2';
	protected $IsDebugActive;
	
    /**
     * retrieves state if debug is activated on user config
     * @return bool
     */
	/*
    public static function getIsDebugActive()
    {
		if (!isset($this->IsDebugActive)) {
			$db =  JFactory::getDbo();
			$query = $db->getQuery (true)
				->select ($db->quoteName('value'))
				->from($db->quoteName('#__rsgallery2_config'))
				->where($db->quoteName('name')." = ".$db->quote('debug'));
			$db->setQuery($query);
			$this->IsDebugActive  = $db->loadResult();
		}

		return $this->IsDebugActive;
    }
	*/

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       string $type    The table type to instantiate
	 * @param       string $prefix A prefix for the table class name. Optional.
	 * @param       array  $config Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data           Data for the form.
	 * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       2.5
	 */
    public function getForm($data = array(), $loadData = true) 
	{
		$options = array('control' => 'jform', 'load_data' => $loadData);
        $form = $this->loadForm ('com_rsgallery2.config', 'config', $options);

		if (empty($form))
		{
			return false;
		}
		return $form;
	}
 	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
    protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
        $data = $app->getUserState('com_rsgallery2.edit.config.data', array());
        if (empty($data)) 
		{
            $data = $this->getItem();
        }

        return $data;
    }
	
    // Transform some data before it is displayed
    /* extension development 129 bottom  */
	protected function prepareTable($table)
	{
		// $table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
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
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data) {
		$msg = "Rsgallery2ModelConfigRaw: ";

		$input =JFactory::getApplication()->input;
		//$jform = $input->get( 'jform', array(), 'ARRAY');

// ToDO: Check if in $data is the same and then for ...raw
//		$data  = $input->post->get('jform', array(), 'array');

//		echo json_encode ($jform);
/*
		// Complete data array if needed
		$oldData = $model->getData();
		$data = array_replace($oldData, $data);
*/
		
// ToDo: Remove bad injected code		

		$row = $this->getTable ();
		foreach ($data as $key => $value)
		{
/*
fill an array, bind and check and store ?
 */
			$row->id = null;
			$row->name = $key;
			$row->value = $value;
			$row->id = null;

//			$msg .= '    name = ' . $key . ' value = ' . $value . '<br>';

			$row->check ();
			$row->store ();

		}

		return $msg;
	}
 

}
