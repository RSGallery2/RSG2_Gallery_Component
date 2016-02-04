<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
/**
 * 
 */
class rsg2ModelConfig extends  JModelAdmin
{
    protected $text_prefix = 'COM_RSG2';

	/**
	 * Returns a reference to a Table object, always creating it.
	 *
	 * @param string $type    The table type to instantiate
	 * @param string $prefix  A prefix for the table class name. Optional.
	 * @param array $config   Configuration array for model. Optional.
	 * @return mixed     JTable  A database object
	 */
	public function getTable($type = 'config', $prefix = 'rsg2Table', $config = array())
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
		// Get the form.
		$form = $this->loadForm('config', 'config', $options);
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
		$data = $app->getUserState('com_rsg2.edit.gallery.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

    // Transform some data before it is displayed
    /* extension development 129 bottom
    protected function prepareTable ($table)
    {
        $table->title = htmlspecialchars_decode ($table->title, ENT_Quotes);
    }
    */


	public function getConfigVariables()
	{
		$db = $this->getDbo();
		$query = $db->getQuery (true)
			->select ($db->quoteName(array('name', 'value')))
			->from($db->quoteName('#__rsgallery2_config'))
			->order('name ASC');

		$db->setQuery($query);
		$db->execute();

		$rows = $db->loadAssocList('name', 'value');

		return $rows;
	}

    /**
     * Tells if debug is activated on user config
     * @return bool
     */
    public static function getDebugActive()
    {
        $IsDebugActive = false;

        $db =  JFactory::getDbo();
        $query = $db->getQuery (true)
			->select ($db->quoteName('value'))
			->from($db->quoteName('#__rsgallery2_config'))
			->where($db->quoteName('name')." = ".$db->quote('debug'));
        $db->setQuery($query);

		$IsDebugActive  = $db->loadResult();

		return $IsDebugActive;
    }

}