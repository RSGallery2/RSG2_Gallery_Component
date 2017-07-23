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
require_once(JPATH_COMPONENT . '/init.rsgallery2.php');
/**/

require_once(JPATH_ROOT . '/administrator/components/com_rsgallery2/includes/baseDefines.php');

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

if ($Rsg2DebugActive) {
    JLog::add('    (R01) ');
}

/** Old part:
// Instantiate user variables but don't show a front end template
rsgInstance::instance('request', false);

if ($Rsg2DebugActive) {
    JLog::add('    (R02) ');
}
/**/

//Load Tooltips
//JHtml::_('behavior.tooltip');

if ($Rsg2DebugActive) {
    JLog::add('    (R03) ');
}

/** Old part:
// getActions / $extension = 'com_rsgallery2';
require_once JPATH_COMPONENT . '/helpers/rsgallery2.php';

if ($Rsg2DebugActive) {
    JLog::add('    (R04) ');
}
/**/

/** Old part:
//Access check
$canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
$canManage = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
if (!$canManage) {
    // return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
    JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

    return;    // 150518 Does not return JError::raiseWarning object $error
}

if ($Rsg2DebugActive) {
    JLog::add('    (R05) ');
}
/**/

/** Old part:
// class HTML_RSGALLERY / showCP / RSGallerySidebar / showUploadStep1
require_once(JPATH_COMPONENT . '/admin.rsgallery2.html.php');///J3

if ($Rsg2DebugActive) {
    JLog::add('    (R06) ');
}
/**/

global $opt, $catid, $uploadStep, $numberOfUploads, $e_id, $view;

// ToDo: sort by old and actual used parameters

$input = JFactory::getApplication()->input;
//$task				= JRequest::getCmd('task');
$task = $input->get('task', '', 'CMD');
//$option			= strtolower(JRequest::getCmd('option'));
$option = strtolower($input->get('option', '', 'CMD'));
//$catid			= JRequest::getInt('catid', null);
$catid = $input->get('catid', null, 'INT');
// ...
$view = $input->get('view', null, 'CMD');
$layout = $input->get('layout', '', 'CMD');

//$uploadStep		= JRequest::getInt('uploadStep', 0 );
$uploadStep = $input->get('uploadStep', 0, 'INT');
//$numberOfUploads	= JRequest::getInt('numberOfUploads', 1 );
$numberOfUploads = $input->get('numberOfUploads', 1, 'INT');

//$firstCid         = JRequest::getInt('cid', 0);
//$firstCid         = $input->get( 'cid', 0, 'INT');
//$firstCid         = $input->get( 'cid', 0, 'INT');
$testCid = $input->get('cid', array(), 'ARRAY');
//$id               = JRequest::getInt('id', 0 );
$id = $input->get('id', 0, 'INT');

//$rsgOption        = JRequest::getCmd('rsgOption', null );
$rsgOption = $input->get('rsgOption', null, 'CMD');

if ($Rsg2DebugActive) {
    JLog::add('    (R07) ');
}

// $my = JFactory::getUser();

if ($Rsg2DebugActive) {
    JLog::add('    (R08) ');
}

if ($Rsg2DebugActive) {
    //$Delim = "\n";
    $Delim = " ";
    // show active task
    $DebTxt = "==> base.rsgallery2.php" . $Delim . "----------" . $Delim;
    $DebTxt = $DebTxt . "\$task: $task" . $Delim;
    $DebTxt = $DebTxt . "\$option: $option" . $Delim;
    $DebTxt = $DebTxt . "\$catid: $catid" . $Delim;
    //$DebTxt = $DebTxt . "\$firstCid: $firstCid".$Delim;
    $DebTxt = $DebTxt . "\$testCid: " . implode(",", $testCid) . $Delim;
    $DebTxt = $DebTxt . "\$id: $id" . $Delim;
    $DebTxt = $DebTxt . "\$rsgOption: $rsgOption" . $Delim;
    $DebTxt = $DebTxt . "\$view: $view" . $Delim;
    $DebTxt = $DebTxt . "\$layout: $layout" . $Delim;

    JLog::add($DebTxt); //, JLog::DEBUG);
}

// ToDo: Get it faster: follow old path only when $rsgOption is set
if ($Rsg2DebugActive) {
    JLog::add('    (R09) ');
}
/**/

$IsOldRsg2J15Style = false;

// old option is called
if (!empty ($rsgOption)) {
    $IsOldRsg2J15Style = true;
}

if ($Rsg2DebugActive) {
    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

// only use the legacy task switch if rsgOption is not used. [MK not truly legacy but still used!]
// these tasks require admin or super admin privileges.
if ($rsgOption == '') {

    if ($Rsg2DebugActive) {
        JLog::add('    (O09B) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

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
            $IsOldRsg2J15Style = true;
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

    if ($Rsg2DebugActive) {
        JLog::add('    (R12) ');
    }
    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

}

if ($Rsg2DebugActive) {
    JLog::add('    (O09C) ');
}

if ($Rsg2DebugActive) {
    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

/**
 * ------------------------------------------------------------------------------
 * Old RDG2 J1.5 style loads too many files and will be removed
 * ------------------------------------------------------------------------------
 */
if($IsOldRsg2J15Style) {
    $rsgSaveOption = $rsgOption;

    if ($Rsg2DebugActive) {
        JLog::add('    ($IsOldRsg2J15Style = true) ');
        // JLog::add('    (init.rsgallery2.php)');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (O09D) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

    // Initialize RSG2 core functionality
    // Does open a lot of files
    require_once(JPATH_COMPONENT . '/init.rsgallery2.php');

    if ($Rsg2DebugActive) {
        JLog::add('    (O09E) ');
    }

    $rsgOption = $rsgSaveOption;

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (O09F) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (rsgInstance::instance');
    }

    // Instantiate user variables but don't show a front end template
    rsgInstance::instance('request', false);

    if ($Rsg2DebugActive) {
        JLog::add('    (O02) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (tooltip) ');
    }

    //Load Tooltips
    JHtml::_('behavior.tooltip');

    if ($Rsg2DebugActive) {
        JLog::add('    (O03) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (helpers/rsgallery2.php) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

    // getActions / $extension = 'com_rsgallery2';
    require_once JPATH_COMPONENT . '/helpers/rsgallery2.php';

    if ($Rsg2DebugActive) {
        JLog::add('    (O04) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (getUser()->authorise) ');
    }

    //Access check
    $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
    $canManage = JFactory::getUser()->authorise('core.manage', 'com_rsgallery2');
    if (!$canManage) {
        // return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

        return; // 150518 Does not return JError::raiseWarning object $error
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (O05) ');
    }

    // class HTML_RSGALLERY / showCP / RSGallerySidebar / showUploadStep1
    require_once(JPATH_COMPONENT . '/admin.rsgallery2.html.php');///J3

    // global $opt, $uploadStep, $numberOfUploads, $e_id, $view;

    if ($Rsg2DebugActive) {
        JLog::add('    (O06) ');
    }

    // Get the toolbar in here for J3 compatibility (since toolbar.rsgallery2.php is no longer autoloaded)
    // Toolbar ==> 1.) rsgOption 2.) Tasks -> views
    require_once(JPATH_COMPONENT . '/toolbar.rsgallery2.php');

    if ($Rsg2DebugActive) {
        JLog::add('    (O07) ');
    }

    if ($Rsg2DebugActive) {
        JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
    }

    /**
     * this is the new $rsgOption switch.  each option will have a switch for $task within it.
     */
    switch ($rsgOption) {
        case 'galleries':
            if ($Rsg2DebugActive) {
                JLog::add('    (O08) ');
            }

            require_once($rsgOptions_path . 'galleries.php');
            break;
        case 'images':
            if ($Rsg2DebugActive) {
                JLog::add('    (O09) ');
            }

            require_once($rsgOptions_path . 'images.php');

            break;
        case 'comments':
            if ($Rsg2DebugActive) {
                JLog::add('    (O10) ');
            }

            require_once($rsgOptions_path . 'comments.php');
            break;
        case 'config':
            if ($Rsg2DebugActive) {
                JLog::add('    (O11) ');
            }

            require_once($rsgOptions_path . 'config.php');
            break;
    //	case 'template':
    //		require_once( $rsgOptions_path . 'templates.php' );
    //		break;
        case 'installer':
            if ($Rsg2DebugActive) {
                JLog::add('    (O12) ');
            }

            require_once($rsgOptions_path . 'installer.php');
            break;
        case 'maintenance':
            if ($Rsg2DebugActive) {
                JLog::add('    (O13) ');
            }

            require_once($rsgOptions_path . 'maintenance.php');
            break;
    }

    if ($Rsg2DebugActive) {
        JLog::add('    (R11) ');
    }
    /**/

    /**
    // only use the legacy task switch if rsgOption is not used. [MK not truly legacy but still used!]
    // these tasks require admin or super admin privileges.
    if ($rsgOption == '') {
        // 140701 original: switch ( JRequest::getCmd('task', null) ){
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
        JLog::add('    (R) ');
    }

}

if ($Rsg2DebugActive) {
    JLog::add('<== base.rsgallery2.php');
}

/***************************************************************************************
Functions
***************************************************************************************/

/**
 * @param string $filename The name of the php (temporary) uploaded file
 * @param string $userfile_name The name of the file to put in the temp directory
 * @param string $msg The message to return
 *
 * @return bool
 */
function uploadFile($filename, $userfile_name, &$msg)
{

    $baseDir = JPATH_SITE . '/media';

    if (file_exists($baseDir)) {
        if (is_writable($baseDir)) {
            if (move_uploaded_file($filename, $baseDir . $userfile_name)) {
                // Try making the file writeable first.
                // if (JClientFtp::chmod( $baseDir . $userfile_name, 0777 )) {
                //if (JPath::setPermissions( $baseDir . $userfile_name, 0777 )) {
                if (JPath::setPermissions($baseDir . $userfile_name)) {
                    return true;
                } else {
                    $msg = JText::_('COM_RSGALLERY2_FAILED_TO_CHANGE_THE_PERMISSIONS_OF_THE_UPLOADED_FILE');
                }
            } else {
                $msg = JText::_('COM_RSGALLERY2_FAILED_TO_MOVE_UPLOADED_FILE_TO_MEDIA_DIRECTORY');
            }
        } else {
            $msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_IS_NOT_WRITABLE');
        }
    } else {
        $msg = JText::_('COM_RSGALLERY2_UPLOAD_FAILED_AS_MEDIA_DIRECTORY_DOES_NOT_EXIST');
    }

    return false;
}

/**
 *
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
 * @return object
 */
function purgeEverything()
{
    global $rsgConfig;

    //Access check
    $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
    if (!$canAdmin) {
        // return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

        return;    // 150518 Does not return JError::raiseWarning object $error
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
 * @return object
 */
function reallyUninstall()
{

    //Access check
    $canAdmin = JFactory::getUser()->authorise('core.admin', 'com_rsgallery2');
    if (!$canAdmin) {
        // return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

        return;    // 150518 Does not return JError::raiseWarning object $error
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
 */
function cancelGallery($option)
{
    global $mainframe;

    $mainframe->redirect("index.php?option=$option");
}

