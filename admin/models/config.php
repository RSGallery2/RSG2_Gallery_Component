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
 	
	
	
	/*
	protected function prepareTable($table)
	{
		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
	}
	*/


}
