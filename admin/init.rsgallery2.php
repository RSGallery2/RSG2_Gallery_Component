<?php
/**
 * This file handles the initialization required for core functionality.
 * It loads all necessary RSG2 libraries
 *
 * @version       $Id: init.rsgallery2.php 1083 2012-06-17 13:03:38Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */
defined('_JEXEC') or die();

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

global $Rsg2DebugActive, $Rsg2DevelopActive;

// create global variables in case we are not in the global scope.
global $rsgConfig, $rsgVersion, $rsgOption, $mainframe;

if ($Rsg2DebugActive) {
    JLog::add('    (01: $rsgOption: "' . $rsgOption . '") ');
}

// Base definitions
require_once(JPATH_ROOT . '/administrator/components/com_rsgallery2/includes/baseDefines.php');

if ($Rsg2DebugActive) {
//    JLog::add('    (02: $rsgOption: "' . $rsgOption . '") ');
}

/**
// check if this file has been included yet.
if (isset($rsgConfig)) {
    return;
}
/**/

/** Is already loaded
// Needed by rsgConfig
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/version.rsgallery2.php');
$rsgVersion = new rsgalleryVersion();

// Initialize the rsg config file
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/config.class.php');
$rsgConfig = new rsgConfig();
/**/

//Set image paths for RSGallery2
/**
 * define('JPATH_ORIGINAL', JPATH_ROOT . str_replace('/', DS, $rsgConfig->get('imgPath_original')));
 * define('JPATH_DISPLAY', JPATH_ROOT . str_replace('/', DS, $rsgConfig->get('imgPath_display')));
 * define('JPATH_THUMB', JPATH_ROOT . str_replace('/', DS, $rsgConfig->get('imgPath_thumb')));
 * define('JPATH_WATERMARKED', JPATH_ROOT . str_replace('/', DS, $rsgConfig->get('imgPath_watermarked')));
 * /**/
define('JPATH_ORIGINAL', JPATH_ROOT . $rsgConfig->get('imgPath_original'));
define('JPATH_DISPLAY', JPATH_ROOT . $rsgConfig->get('imgPath_display'));
define('JPATH_THUMB', JPATH_ROOT . $rsgConfig->get('imgPath_thumb'));
define('JPATH_WATERMARKED', JPATH_ROOT . $rsgConfig->get('imgPath_watermarked'));
/**/

if ($Rsg2DebugActive) {
//    JLog::add('    (03: $rsgOption: "' . $rsgOption . '") ');
}

$rsgOptions_path = JPATH_RSGALLERY2_ADMIN . '/options/';
$rsgClasses_path = JPATH_RSGALLERY2_ADMIN . '/includes/';

if ($Rsg2DebugActive) {
//    JLog::add('    (I01) ');
}

//include ACL class
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/access.class.php');
if ($Rsg2DebugActive) {
//    JLog::add('    (I02) ');
}

if ($Rsg2DebugActive) {
//    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

// include authorisation check class
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/authorisation.class.php');

if ($Rsg2DebugActive) {
//    JLog::add('    (I03) ');
}

if ($Rsg2DebugActive) {
//    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

// include rsgInstance
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/instance.class.php');

if ($Rsg2DebugActive) {
//    JLog::add('    (I04) ');
}

if ($Rsg2DebugActive) {
    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

// require file utilities
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/file.utils.php');
if ($Rsg2DebugActive) {
//    JLog::add('    (I05) ');
}

if ($Rsg2DebugActive) {
 //   JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

require_once(JPATH_RSGALLERY2_ADMIN . '/includes/img.utils.php');
if ($Rsg2DebugActive) {
 //   JLog::add('    (I06) ');
}

if ($Rsg2DebugActive) {
//    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

require_once(JPATH_RSGALLERY2_ADMIN . '/includes/audio.utils.php');
if ($Rsg2DebugActive) {
 //   JLog::add('    (I07) ');
}

if ($Rsg2DebugActive) {
 //   JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

require_once(JPATH_RSGALLERY2_ADMIN . '/includes/items/item.php');

if ($Rsg2DebugActive) {
//    JLog::add('    (I07) ');
}

if ($Rsg2DebugActive) {
//    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

// contains misc. utility functions
require_once(JPATH_RSGALLERY2_ADMIN . '/config.rsgallery2.php');
if ($Rsg2DebugActive) {
//    JLog::add('    (I08) ');
}

if ($Rsg2DebugActive) {
//    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

require_once(JPATH_RSGALLERY2_ADMIN . '/includes/gallery.manager.php');
if ($Rsg2DebugActive) {
 //   JLog::add('    (I09) ');
}

if ($Rsg2DebugActive) {
 //   JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

require_once(JPATH_RSGALLERY2_ADMIN . '/includes/gallery.class.php');

if ($Rsg2DebugActive) {
 //   JLog::add('    (I10) ');
}

if ($Rsg2DebugActive) {
 //   JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

//
require_once(JPATH_RSGALLERY2_LIBS . '/rsgcomments/rsgcomments.class.php');
if ($Rsg2DebugActive) {
 //   JLog::add('    (I11) ');
}

if ($Rsg2DebugActive) {
//    JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

require_once(JPATH_RSGALLERY2_LIBS . '/rsgvoting/rsgvoting.class.php');

if ($Rsg2DebugActive) {
 //   JLog::add('    (I12) ');
}
if ($Rsg2DebugActive) {
 //   JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}


//require_once($rsgOptions_path . 'images.class.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/options/images.class.php');

if ($Rsg2DebugActive) {
 //   JLog::add('    (I13) ');
}

if ($Rsg2DebugActive) {
 //   JLog::add('    ($rsgOption: "' . $rsgOption . '") ');
}

