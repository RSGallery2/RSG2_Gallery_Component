<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * 
 */
class rsgallery2ModelConfig extends  JModelAdmin  // JModelForm 
{
    protected $text_prefix = 'COM_RSGallery2';
	protected $IsDebugActive;
	
    /**
     * retrieves state if debug is activated on user config
     * @return bool
     */
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
}
