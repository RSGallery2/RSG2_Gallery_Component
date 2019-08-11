<?php
/**
 * @package
 * @subpackage  com_rsgallery2
 *
 * @copyright      (C) 2005-2019 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die();

/**
 * Standard functions for table gallery
 *
 * @since 4.3.0
 */
class Rsgallery2TableGallery extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver &$db A database connector object
     *
     * @since 4.3.0
	 */
	function __construct(&$db)
	{
		// id, name, value
		parent::__construct('#__rsgallery2_galleries', 'id', $db);
	}

	/**
     * Overloaded bind function
     *
     * @param array|object $array An associative array or object to bind to the JTable instance.
     * @param string $ignore
     *
     * @return bool True on success
     * @see   JTable:bind
     *
     * @since 4.3.0
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string) $parameter;
		}

		return parent::bind($array, $ignore);
	}

	/**
     * Overloaded load function
     *
     * @param       int     $pk    primary key
     * @param       boolean $reset reset data
     *
     * @return bool True on success
     * @see JTable:load
     *
     * @since 4.3.0
	 */
	public function load($pk = null, $reset = true)
	{
		if (parent::load($pk, $reset))
		{
			// Convert the params field to a registry.
			$params = new JRegistry;
			$params->loadString($this->params, 'JSON');
			$this->params = $params;

			return true;
		}
		else
		{
			return false;
		}
	}

}
