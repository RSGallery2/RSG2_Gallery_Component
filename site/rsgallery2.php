<?php
/**
 * Initialize default instance of RSGallery2
 *
 * @package       RSGallery2
 *
 * @author        RSGallery2 team
 * @copyright (C) 2003 - 2020 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 * @link       www.rsgallery2.org
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// global $isDebugSiteActive, $isDevelopSiteActive, $rsgConfig;

global $rsgConfig, $isDebugSiteActive, $Rsg2DevelopActive;


//--- retrieve rsConfig :: J3x++ may use it from ... instead */

require_once JPATH_COMPONENT_ADMINISTRATOR . '/includes/config.class.php';
$rsgConfig = new rsgConfig();

$Rsg2DevelopActive = $rsgConfig->get('develop');
//$isDebugSiteActive = $rsgConfig->get('debug');

//--- debug settings ---------------------------------------------------

// Enable settings from URL
$input = JFactory::getApplication()->input;

/**
//$isUseJ25View = $input->get('useJ25Display', 0, 'INT');
//$isUseJ25View = $rsgConfig->get('useJ25Display');
$isUseJ25View = $rsgConfig->get('useJ25Views');;
$bValue = $input->get('useJ25Views', 0, 'INT');
$isUseJ25View |= ! empty($bValue);
/**/

/* debugSite from config or URL */
$isDebugSiteActive = $rsgConfig->get('debugSite');
$bUrlValue = (bool) $input->get('debugSite', 0, 'INT');
$isDebugSiteActive |= ! empty($bUrlValue);
/**
$isDevelopSiteActive = $rsgConfig->get('developSite');
$bValue = $input->get('developSite', 0, 'INT');
$isDevelopSiteActive |= ! empty($bValue);
/**/

// Activate logging
if ($isDebugSiteActive)
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
			'text_file' => 'rsgallery2Site.' . $date . '.log.php',

			// (optional) you can change the directory
			// 'text_file_path' => 'logs'
		),
		JLog::ALL ^ JLog::DEBUG // leave out db messages
	);

	// start logging...
	JLog::add('Start rsgallery2.php in site: debug active in RSGallery2'); //, JLog::DEBUG);
}

//--- input settings ------------------------------------------------

$task = $input->get('task', '', 'CMD');
//$option = strtolower($input->get('option', '', 'CMD'));
//$catid = $input->get('catid', null, 'INT');

$view = $input->get('view', null, 'CMD');
$layout = $input->get('layout', '', 'CMD');
$rsgOption = $input->get('rsgOption', '', 'CMD');

// List of id's (image, gallery ...
// $cids = $input->get('cid', array(), 'ARRAY');
// $id = $input->get('id', 0, 'INT');

// $rsgOption = $input->get('rsgOption', null, 'CMD');

if ($isDebugSiteActive) {
    //$Delim = "\n";
    $Delim = " ";
    // show active task
    $DebTxt = "==> base.rsgallery2.php" . $Delim . "----------" . $Delim;
    $DebTxt = $DebTxt . "\$task: $task" . $Delim;
    //$DebTxt = $DebTxt . "\$option: $option" . $Delim;
    //$DebTxt = $DebTxt . "\$catid: $catid" . $Delim;
    //$DebTxt = $DebTxt . "\$cids: " . implode(",", $cids) . $Delim;
    //$DebTxt = $DebTxt . "\$id: $id" . $Delim;
    //$DebTxt = $DebTxt . "\$rsgOption: $rsgOption" . $Delim;
    $DebTxt = $DebTxt . "\$view: $view" . $Delim;
    $DebTxt = $DebTxt . "\$layout: $layout" . $Delim;

    JLog::add($DebTxt); //, JLog::DEBUG);
}

//--- determine if J.5 old or new 3.x++ (2019) style -------------------------------------

// ToDo: Task and other vars
$isUseJ25View = True;

// Task may be  J3.5 part
if ( ! empty ($task))
{
	$isUseJ25View = False;
}

// new view required
if ( ! empty ($view))
{
	if ($view != 'gallery' && $view != 'slideshow')
    {
		$isUseJ25View = False;
    }
	else
	{
		if ( ! empty ($layout))
		{
			$isUseJ25View = False;
		}
	}
}


// Task may be  J2.5 part
if ( ! empty ($rsgOption))
{
	$isUseJ25View = True;
}

// Use the old J25 files and tasks
if ($isUseJ25View) {
	if ($isDebugSiteActive) {
			JLog::add('old J25 files and tasks'); //, JLog::DEBUG);
	}

	// Initialize RSG2 core functionality
	require_once(JPATH_SITE . "/administrator/components/com_rsgallery2/init.rsgallery2.php");

    // include rsgInstance
    require_once(JPATH_RSGALLERY2_ADMIN . '/includes/instance.class.php');
    
	// Create a new instance of RSGallery2
	rsgInstance::instance();
}
else
{
	// Use the J3.x ++ files and tasks
	if ($isDebugSiteActive) {
			JLog::add('Use the J3.x ++ files and tasks'); //, JLog::DEBUG);
	}

    $controller = BaseController::getInstance('rsgallery2');
    $controller->execute(Factory::getApplication()->input->get('task'));
    $controller->redirect();
}

