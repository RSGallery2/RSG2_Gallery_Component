<?php
/**
 * @package
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005-2019 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die();

/**
 * Standard functions for table config
 *
 * @since 4.3.0
 */
class Rsgallery2TableConfig extends JTable
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
		parent::__construct('#__rsgallery2_config', 'id', $db);
	}
}
