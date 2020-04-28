<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2018-2020 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery2 is Free Software
 * @since         4.5.1
 */

defined('_JEXEC') or die();

/**
 * Support of configuration in database for RSGallery2
 *
 * @since 4.5.1
 */
class configDb
{
	/**
	 * Writes a config variable direct into the parmeter of RSGallery2
	 * As $rsgConfig is mostly active the function updateConfig may be used instead
	 * @param string $cfgName  Name of variable to be saved
	 * @param string $cfgValue Value to be saved
	 * @param null/array  $params   List of config paramter with values
	 *
	 *
	 * @since 4.5.1
	 */
	public static function write2Config ($cfgName='', $cfgValue='', $params=null)
	{
		if ($params == null)
		{
			// Load the current component params.
			$params = JComponentHelper::getParams('com_content');
			// Set new value of param(s)
			$params->set('show_title', 1);
		}

		configDb::updateConfig2Db ($params);
	}

	/**
	 * Write list of configuration parameter to database
	 * @param array $params Config parameter
	 * @param null/array  $params   List of config paramter with values
	 *
	 * @return bool
	 *
	 * @since 4.5.1
	 */
	public static function updateConfig2Db ($params)
	{
		// Save the parameters
		$componentId = JComponentHelper::getComponent('com_rsgallery2')->id;
		$table = JTable::getInstance('extension');
		$table->load($componentId);
		$table->bind(array('params' => $params->toString()));

		// check for error
		if (!$table->check()) {
			echo $table->getError();
			return false;
		}

		// Save to database
		if (!$table->store()) {
			echo $table->getError();
			return false;
		}

		return true;
	}



}


