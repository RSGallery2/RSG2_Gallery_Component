<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

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

	public function getTable($type = 'Config', $prefix = 'Rsgallery2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm ('com_rsgallery2.config', 'config', 
			array('control' => 'jform', 'load_data' => $loadData));

		if (!$form) {
            return false;
        } else {
            return $form;
        }
    }
 	
    protected function loadFormData() {
        $data = JFactory::getApplication()
			->getUserState('com_rsgallery2.edit.config.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
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



 
	/*
	protected function prepareTable($table)
	{
		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
	}
	*/


}
