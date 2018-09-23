<?php
/**
 * Initialize default instance of RSGallery2
 *
 * @package       RSGallery2
 *
 * @author     finnern <[AUTHOR_EMAIL]>
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 * @link       www.rsgallery2.org
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

global $Rsg2DebugActive, $Rsg2DevelopActive, $rsgConfig;

// Define folder pathes and URI base definitions
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/baseDefines.php');

//$rsgConfig = new rsgConfig();
$rsgConfig = JComponentHelper::getParams('com_rsgallery2');

$Rsg2DevelopActive = $rsgConfig->get('develop'); // $isDevelopActive
$Rsg2DebugActive = $rsgConfig->get('debug'); // debugsite $isDebugSite
$isDebugBackActive = $rsgConfig->get('debugBackend'); // $isDebugBackend

//$input = JFactory::getApplication()->input;
//$useJ25Display = $input->get('useJ25Display', 0, 'INT');
$useJ25Display = $rsgConfig->get('useJ25Display'); // $isDebugBackend

// Activate logging
if ($Rsg2DebugActive)
{
	// Include the JLog class.
	jimport('joomla.log.log');

	// Get the date for log file name
	$date = JFactory::getDate()->format('Y-m-d');

	// Add the logger.
	JLog::addLogger(
	// Pass an array of configuration options
		array(
			// Set the name of the log file
			'text_file' => 'rsgallery2.' . $date . '.log.php',

			// (optional) you can change the directory
			// 'text_file_path' => 'logs'
		),
		JLog::ALL ^ JLog::DEBUG // leave out db messages
	);

	// start logging...
	JLog::add('Start rsgallery2.php in site: debug active in RSGallery2'); //, JLog::DEBUG);
}

//$useJ25Display = true;
if ($useJ25Display) {
    // ToDo: Remove following
    // include rsgInstance
    require_once(JPATH_RSGALLERY2_ADMIN . '/includes/instance.class.php');
    
    // Create a new instance of RSGallery2
    rsgInstance::instance();
}
else
{
    $controller = BaseController::getInstance('rsgallery2');
    $controller->execute(Factory::getApplication()->input->get('task'));
    $controller->redirect();
}

