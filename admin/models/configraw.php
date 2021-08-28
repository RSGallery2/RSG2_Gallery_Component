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


// ToDo: don't return message, return successful/error

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
	public function save()
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
			$row->id    = null;

			$row->check();
			$row->store();
		}

		return $msg;
	}
	/**
	 * save raw ...
	 *
	 * @return string
	 *
	 * @since 4.3.0
	 */
	public function reset2default()
	{
		// ToDO: Move message to controller, return true or false

		$msg = "Rsgallery2ModelReset2Default: ";

		$defaultConfig = new rsgConfig(false);
		$isSaved = $defaultConfig->saveConfig();

		return $isSaved;
	}






}
