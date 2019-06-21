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
 * May not be needed ToDo: Delete table when one user has had a problem and we know how to move local acl to standard acl
 *
 * @since 4.3.0
 */
class Rsgallery2TableAcl_item extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver &$db A database connector object
	 * @since 4.3.0
	 */
	function __construct(&$db)
	{
		// id, name, value
		parent::__construct('#__rsgallery2_acl', 'id', $db);
	}

}
