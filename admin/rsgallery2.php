<?php
/**
 * This file contains the non-presentation processing for the Admin section of RSGallery.
 *
 * @version       $Id: admin.rsgallery2.php 1085 2012-06-24 13:44:29Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
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

// ToDo remove ....
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/baseDefines.php');

// Initialize the rsg config file
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/config.class.php');
$rsgConfig = new rsgConfig();

$Rsg2DevelopActive = $rsgConfig->get('develop');
$Rsg2DebugActive = $rsgConfig->get('debug');

if ($Rsg2DebugActive) {
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
            'text_file' => 'rsgallery2.adm.log.' . $date . '.php',

            // (optional) you can change the directory
            'text_file_path' => 'logs'
        ),
        JLog::ALL ^ JLog::DEBUG // leave out db messages
    );

    // start logging...
    JLog::add('--------------------------------------------------------'); //, JLog::DEBUG);
    JLog::add('Start rsgallery2.php in admin: debug active in RSGallery2'); //, JLog::DEBUG);
}


global $opt, $catid, $uploadStep, $numberOfUploads, $e_id, $view;

// ToDo: remove old and actual used parameters

$input = JFactory::getApplication()->input;
//$task				= JRequest::getCmd('task');
$task = $input->get('task', '', 'CMD');
$option = strtolower($input->get('option', '', 'CMD'));
$catid = $input->get('catid', null, 'INT');

$view = $input->get('view', null, 'CMD');
$layout = $input->get('layout', '', 'CMD');

$uploadStep = $input->get('uploadStep', 0, 'INT');
$numberOfUploads = $input->get('numberOfUploads', 1, 'INT');

$testCid = $input->get('cid', array(), 'ARRAY');
$id = $input->get('id', 0, 'INT');

$rsgOption = $input->get('rsgOption', null, 'CMD');

if ($Rsg2DebugActive) {
    //$Delim = "\n";
    $Delim = " ";
    // show active task
    $DebTxt = "==> base.rsgallery2.php" . $Delim . "----------" . $Delim;
    $DebTxt = $DebTxt . "\$task: $task" . $Delim;
    $DebTxt = $DebTxt . "\$option: $option" . $Delim;
    $DebTxt = $DebTxt . "\$catid: $catid" . $Delim;
    $DebTxt = $DebTxt . "\$testCid: " . implode(",", $testCid) . $Delim;
    $DebTxt = $DebTxt . "\$id: $id" . $Delim;
    $DebTxt = $DebTxt . "\$rsgOption: $rsgOption" . $Delim;
    $DebTxt = $DebTxt . "\$view: $view" . $Delim;
    $DebTxt = $DebTxt . "\$layout: $layout" . $Delim;

    JLog::add($DebTxt); //, JLog::DEBUG);
}

// Get it faster: follow old path only when $rsgOption is set
$IsOldRsg2J25Style = false;

// old option is called
if (!empty ($rsgOption)) {
    $IsOldRsg2J25Style = true;
}

if ($Rsg2DebugActive) {
    JLog::add('    ($IsOldRsg2J25Style: "' . $IsOldRsg2J25Style . '") ');
}

// only use the legacy task switch if rsgOption is not used. [MK not truly legacy but still used!]
// these tasks require admin or super admin privileges.
if ($rsgOption == '') {

    // 140701 original: switch ( JRequest::getCmd('task', null) ){
    switch ($task) {
        // old J1.5 tasks
        case 'purgeEverything':
        case 'reallyUninstall':
        //Config tasks
        // this is just a kludge until all links and form vars to configuration functions have been updated to use $rsgOption = 'config';
        /*
        case 'applyConfig':
        case 'saveConfig':
        case "showConfig":
        */
        case 'config_dumpVars':
        case 'config_rawEdit_apply':
        case 'config_rawEdit_save':
        case 'config_rawEdit':
        //Image tasks
        case "edit_image":
        case "uploadX":
        case "batchuploadX":
        case "save_batchuploadX":
        //Image and category tasks
        case "categories_orderup":
        case "images_orderup":
        case "categories_orderdown":
        case "images_orderdown":
        case 'viewChangelog':
        case "controlPanel":
            // Yes old J1.5 task
            $IsOldRsg2J25Style = true;
            break;

        default:
            //--- New MVC view/ ... Handling --------------------------
            // New RSGallery2 views as MVC: Use standard Joomla! path
            $controller = JControllerLegacy::getInstance('rsgallery2');

            // $task may have been changed inside JControllerLegacy::getInstance
            $controller->execute($input->get('task', '', 'CMD'));
            $controller->redirect();

            break;
    }
}

/**
 * ------------------------------------------------------------------------------
 * Old RDG2 J1.5 style loads too many files and will be removed
 * ------------------------------------------------------------------------------
 */

// Load old code from extra file so it is not read every time ....

if($IsOldRsg2J25Style) {
	// calls moved parts from J! 2.5 version of RSGallery2
	require_once(JPATH_COMPONENT_ADMINISTRATOR . '/includes/J25VersionIncludes.php');
}

if ($Rsg2DebugActive) {
    JLog::add('<== base.rsgallery2.php');
}

