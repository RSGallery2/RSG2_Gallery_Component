<?php
/**
 * Initialize default instance of RSGallery2
 *
 * @version       $Id: rsgallery2.php 1011 2011-01-26 15:36:02Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */
defined('_JEXEC') or die();

global $rsgConfig;

// Initialize RSG2 core functionality
require_once(JPATH_SITE . "/administrator/components/com_rsgallery2/init.rsgallery2.php");

$Rsg2DebugActive = $rsgConfig->get('debug');
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
			//'text_file' => substr($application->scope, 4) . ".log.php",
			'text_file' => 'rsgallery2.' . $date . '.log.php',

			// (optional) you can change the directory
			// 'text_file_path' => 'logs'
		),
		JLog::ALL ^ JLog::DEBUG // leave out db messages
	);

	// start logging...
	JLog::add('Start rsgallery2.php in site: debug active in RSGallery2'); //, JLog::DEBUG);
}

// Create a new instance of RSGallery2
rsgInstance::instance();
	