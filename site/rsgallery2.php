<?php
/**
 * Initialize default instance of RSGallery2
 *
 * @version       $Id: rsgallery2.php 1011 2011-01-26 15:36:02Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */
defined('_JEXEC') or die();

global $Rsg2DebugActive, $Rsg2DevelopActive, $rsgConfig;

/** Old part:
// Initialize RSG2 core functionality
// Does open a lot of files
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/init.rsgallery2.php');
/**/

// Define folder pathes and URI base definitions
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/baseDefines.php');

//$rsgConfig = new rsgConfig();
$rsgConfig = JComponentHelper::getParams('com_rsgallery2');

$Rsg2DevelopActive = $rsgConfig->get('develop'); // $isDevelopActive
$Rsg2DebugActive = $rsgConfig->get('debug'); // debugsite $isDebugSite
$isDebugBackActive = $rsgConfig->get('debugBackend'); // $isDebugBackend

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

// ToDO: Remove following
// include rsgInstance
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/instance.class.php');

// Create a new instance of RSGallery2
rsgInstance::instance();
	