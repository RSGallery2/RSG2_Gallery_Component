<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

/*
global $Rsg2DebugActive;

if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
	JLog::add('==> ctrl.acl_item.php ');
}
/**/

// ToDo: // Sanitize the input

//jimport('joomla.application.component.controlleradmin');

/**
 * May not be needed ToDo: Delete table when one user has had a problem and we know how to move local acl to standard acl
 *
 * Class Rsgallery2ControllerAcl_item
 * @since 4.3.0
 */
class Rsgallery2ControllerAcl_item extends JControllerForm
{

}
