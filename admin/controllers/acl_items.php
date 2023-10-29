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
	JLog::add('==> ctrl.image.php ');
}
/**/

// ToDo: // Sanitize the input

jimport('joomla.application.component.controlleradmin');

/**
 * May not be needed ToDo: Delete table when one user has had a problem and we know how to move local acl to standard acl
 *
 * @package     ${NAMESPACE}
 *
 * @since version#
 */
class Rsgallery2ControllerComments extends JControllerAdmin
{

	public function getModel($name = 'Comment',
		$prefix = 'Rsgallery2Model',
		$config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

}
