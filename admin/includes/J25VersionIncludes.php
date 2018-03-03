<?php
/**
 * This file contains moved parts from J! 2.5 version of RSGallery2
 * These are only needed for using the old behavior called from 
 * maintenance and may be deleted later
 * @package       RSGallery2
 * @copyright (C) 2017-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery2 is Free Software
 */

defined('_JEXEC') or die();

/***************************************************************************************
Functions
 ***************************************************************************************/

// Not used ? 2017.08.10 whazzup  ? plugin module ??? -> Not found
/**
 * @param string $filename The name of the php (temporary) uploaded file
 * @param string $userfile_name The name of the file to put in the temp directory
 * @param string $msg The message to return
 *
 * @return bool
 */
// function uploadFile($filename, $userfile_name, &$msg)
// {
//
//     $baseDir = JPATH_SITE . '/media';
//
//     if (file_exists($baseDir)) {
//         if (is_writable($baseDir)) {
//             if (move_uploaded_file($filename, $baseDir . $userfile_name)) {
//                 // Try making the file writeable first.
//                 // if (JClientFtp::chmod( $baseDir . $userfile_name, 0777 )) {
//                 //if (JPath::setPermissions( $baseDir . $userfile_name, 0777 )) {
//                 if (JPath::setPermissions($baseDir . $userfile_name)) {
//                     return true;
//                 } else {
//                     $msg = JText::_('COM_RSGALLERY2_FAILED_TO_CHANGE_THE_PERMISSIONS_OF_THE_UPLOADED_FILE');
//                 }
//             } else {
//                 $msg = JText::_('COM_RSGALLERY2_FAILED_TO_MOVE_UPLOADED_FILE_TO_MEDIA_DIRECTORY');
//             }
//         } else {
//             $msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_IS_NOT_WRITABLE');
//         }
//     } else {
//         $msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_DOES_NOT_EXIST');
//     }
//
//     return false;
// }

/**
 *
 * @since 4.3.0
     */
function viewChangelog()
{
	echo '<pre>';
	readfile(JPATH_RSGALLERY2_ADMIN . '/changelog.php');
	echo '</pre>';
}

/**
 * deletes all pictures, thumbs and their database entries. It leaves category information in DB intact.
 * this is a quick n dirty function for development, it shouldn't be available for regular users.
 *
 * @since 4.3.0
*/
function purgeEverything()
{
	global $rsgConfig;

	//Access check
	$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
	if (!$canAdmin) {

		JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

		return;    // 150518 Does not return J Error::raiseWarning object $error
	} else {
		$fullPath_thumb = JPATH_ROOT . $rsgConfig->get('imgPath_thumb') . '/';
		$fullPath_display = JPATH_ROOT . $rsgConfig->get('imgPath_display') . '/';
		$fullPath_original = JPATH_ROOT . $rsgConfig->get('imgPath_original') . '/';

		processAdminSqlQueryVerbosely('DELETE FROM #__rsgallery2_files', JText::_('COM_RSGALLERY2_PURGED_IMAGE_ENTRIES_FROM_DATABASE'));
		processAdminSqlQueryVerbosely('DELETE FROM #__rsgallery2_galleries', JText::_('COM_RSGALLERY2_PURGED_GALLERIES_FROM_DATABASE'));
		processAdminSqlQueryVerbosely('DELETE FROM #__rsgallery2_config', JText::_('COM_RSGALLERY2_PURGED_CONFIG_FROM_DATABASE'));
		processAdminSqlQueryVerbosely('DELETE FROM #__rsgallery2_comments', JText::_('COM_RSGALLERY2_PURGED_COMMENTS_FROM_DATABASE'));
		processAdminSqlQueryVerbosely('DELETE FROM #__rsgallery2_acl', JText::_('COM_RSGALLERY2_ACCESS_CONTROL_DATA_DELETED'));

		// remove thumbnails
		HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_REMOVING_THUMB_IMAGES'));
		foreach (glob($fullPath_thumb . '*') as $filename) {
			if (is_file($filename)) {
				unlink($filename);
			}
		}

		// remove display imgs
		HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES'));
		foreach (glob($fullPath_display . '*') as $filename) {
			if (is_file($filename)) {
				unlink($filename);
			}
		}

		// remove display imgs
		HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_REMOVING_ORIGINAL_IMAGES'));
		foreach (glob($fullPath_original . '*') as $filename) {
			if (is_file($filename)) {
				unlink($filename);
			}
		}

		HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_PURGED'), true);
	}

	return;
}

/**
 * drops all RSG2 tables, deletes image directory structure
 * use before uninstalling to REALLY uninstall
 *
 * @todo This is a quick hack.  make it work on all OS and with non default directories.
 * @since 4.3.0
     */
function reallyUninstall()
{

	//Access check
	$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
	if (!$canAdmin) {
		JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

		return;    // 150518 Does not return J Error::raiseWarning object $error
	} else {
		passthru("rm -r " . JPATH_SITE . "/images/rsgallery");
		HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_USED_RM_MINUS_R_TO_ATTEMPT_TO_REMOVE_JPATH_SITE_IMAGES_RSGALLERY'));

		processAdminSqlQueryVerbosely('DROP TABLE IF EXISTS #__rsgallery2_acl', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_GALLERIES'));
		processAdminSqlQueryVerbosely('DROP TABLE IF EXISTS #__rsgallery2_files', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_FILES'));
		processAdminSqlQueryVerbosely('DROP TABLE IF EXISTS #__rsgallery2_cats', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_GALLERIES'));
		processAdminSqlQueryVerbosely('DROP TABLE IF EXISTS #__rsgallery2_galleries', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_GALLERIES'));
		processAdminSqlQueryVerbosely('DROP TABLE IF EXISTS #__rsgallery2_config', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_CONFIG'));
		processAdminSqlQueryVerbosely('DROP TABLE IF EXISTS #__rsgallery2_comments', JText::_('COM_RSGALLERY2_DROPED_TABLE___RSGALLERY2_COMMENTS'));

		HTML_RSGALLERY::printAdminMsg(JText::_('COM_RSGALLERY2_REAL_UNINST_DONE'));
	}

	return;
}

/**
 * runs a sql query, displays admin message on success or error on error
 *
 * @param string $query sql query
 * @param string $successMsg message to display on success
 *
 * @return boolean value indicating success
 * @since 4.3.0
     */
function processAdminSqlQueryVerbosely($query, $successMsg)
{
	$database = JFactory::getDBO();

	$database->setQuery($query);
	$database->execute();
	if ($database->getErrorMsg()) {
		HTML_RSGALLERY::printAdminMsg($database->getErrorMsg(), true);

		return false;
	} else {
		HTML_RSGALLERY::printAdminMsg($successMsg);

		return true;
	}
}

/**
 * @param string $option
 * @since 4.3.0
     */
function cancelGallery($option)
{
	global $mainframe;

	$mainframe->redirect("index.php?option=$option");
}



/***************************************************************************************
immediately code
 ***************************************************************************************/



$rsgSaveOption = $rsgOption;

if ($Rsg2DebugActive) {
	JLog::add('    (Start $IsOldRsg2J25Style = true) ');
	// JLog::add('    (init.rsgallery2.php)');
}

// Initialize RSG2 core functionality
// Does open a lot of files
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/init.rsgallery2.php');

$rsgOption = $rsgSaveOption;

// Instantiate user variables but don't show a front end template
rsgInstance::instance('request', false);

//Load Tooltips
JHtml::_('behavior.tooltip');

// getActions / $extension = 'com_rsgallery2';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/rsgallery2.php';

//Access check
$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
$canManage = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
if (!$canManage) {
	JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

	return; // 150518 Does not return J Error::raiseWarning object $error
}

// ToDO: remove loading ...
// class HTML_RSGALLERY / showCP / RSGallerySidebar / showUploadStep1
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/admin.rsgallery2.html.php');///J3

// global $opt, $uploadStep, $numberOfUploads, $e_id, $view;

// Get the toolbar in here for J3 compatibility (since toolbar.rsgallery2.php is no longer autoloaded)
// Toolbar ==> 1.) rsgOption 2.) Tasks -> views
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/toolbar.rsgallery2.php');

/**
 * this is the new $rsgOption switch.  each option will have a switch for $task within it.
 */
switch ($rsgOption) {
	case 'galleries':
		require_once($rsgOptions_path . 'galleries.php');
		break;

	case 'images':
		require_once($rsgOptions_path . 'images.php');
		break;

	case 'comments':
		require_once($rsgOptions_path . 'comments.php');
		break;

	case 'config':
		require_once($rsgOptions_path . 'config.php');
		break;
	//	case 'template':
	//		require_once( $rsgOptions_path . 'templates.php' );
	//		break;
	case 'installer':
		require_once($rsgOptions_path . 'installer.php');
		break;
	case 'maintenance':
		require_once($rsgOptions_path . 'maintenance.php');
		break;
}

/**/

/**
// only use the legacy task switch if rsgOption is not used. [MK not truly legacy but still used!]
// these tasks require admin or super admin privileges.
if ($rsgOption == '') {
switch ($task) {
//Special/debug tasks
case 'purgeEverything':
purgeEverything();    //canAdmin check in this function
HTML_RSGallery::showCP();
HTML_RSGallery::RSGalleryFooter();
break;
case 'reallyUninstall':
reallyUninstall();    //canAdmin check in this function
HTML_RSGallery::showCP();
HTML_RSGallery::RSGalleryFooter();
break;
//Config tasks
// this is just a kludge until all links and form vars to configuration functions have been updated to use $rsgOption = 'config';
/*
case 'applyConfig':
case 'saveConfig':
case "showConfig":
 *
case 'config_dumpVars':
case 'config_rawEdit_apply':
case 'config_rawEdit_save':
case 'config_rawEdit':
$rsgOption = 'config';
require_once($rsgOptions_path . 'config.php');
break;
//Image tasks
case "edit_image":
HTML_RSGallery::RSGalleryHeader('edit', JText::_('COM_RSGALLERY2_EDIT'));
editImageX($option, firstCid);
HTML_RSGallery::RSGalleryFooter();
break;

case "uploadX":
JFactory::getApplication()->enqueueMessage('Marked for removal: uploadX', 'Notice');
HTML_RSGallery::RSGalleryHeader('browser', JText::_('COM_RSGALLERY2_UPLOAD'));
showUpload();
HTML_RSGallery::RSGalleryFooter();
break;

case "batchuploadX":
JFactory::getApplication()->enqueueMessage('Marked for removal: batchuploadX', 'Notice');
HTML_RSGallery::RSGalleryHeader('', JText::_('COM_RSGALLERY2_UPLOAD_ZIP_MINUS_FILE'));
batch_upload($option, $task);
HTML_RSGallery::RSGalleryFooter();
break;
case "save_batchuploadX":
JFactory::getApplication()->enqueueMessage('Marked for removal: save_batchuploadX', 'Notice');
save_batchupload();
break;
//Image and category tasks
case "categories_orderup":
case "images_orderup":
orderRSGallery(firstCid, -1, $option, $task);
break;
case "categories_orderdown":
case "images_orderdown":
orderRSGallery(firstCid, 1, $option, $task);
break;
//Special/debug tasks
case 'viewChangelog':
HTML_RSGallery::RSGalleryHeader('viewChangelog', JText::_('COM_RSGALLERY2_CHANGELOG'));
viewChangelog();
HTML_RSGallery::RSGalleryFooter();
break;
case "controlPanel":
HTML_RSGallery::showCP();
HTML_RSGallery::RSGalleryFooter();
break;
default:
/**
// Should not come here
//--- New MVC view/ ... Handling --------------------------
// New RSGallery2 views as MVC: Use standard Joomla! path
$controller = JControllerLegacy::getInstance('rsgallery2');

// $task may have been changed inside JControllerLegacy::getInstance
$controller->execute($input->get('task', '', 'CMD'));
$controller->redirect();
/*
break;
}
}
/**/

if ($Rsg2DebugActive) {
	JLog::add('    (End $IsOldRsg2J25Style = true) ');
}


