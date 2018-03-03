<?php
/**
 * This file handles the initialization required for core functionality.
 * It loads all necessary RSG2 libraries
 *
 * @version       $Id: init.rsgallery2.php 1083 2012-06-17 13:03:38Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */
defined('_JEXEC') or die();

//if (!defined('DS')) {
//    define('DS', DIRECTORY_SEPARATOR);
//}

global $Rsg2DebugActive, $Rsg2DevelopActive;

// create global variables in case we are not in the global scope.
global $rsgConfig, $rsgVersion, $rsgOption, $mainframe;

if ($Rsg2DebugActive) {
    JLog::add('    (01: $rsgOption: "' . $rsgOption . '") ');
}

// Base definitions
require_once(JPATH_ROOT . '/administrator/components/com_rsgallery2/includes/baseDefines.php');


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

// ToDo: >> remove or rearrange: rsgallery2_gallerydisplay plugin (others) calls this for display
// Needed by rsgConfig
if(empty($rsgVersion))
{
    // Initialize the rsg version info
    require_once(JPATH_RSGALLERY2_ADMIN . '/includes/version.rsgallery2.php');
    $rsgVersion = new rsgalleryVersion();
}

if(empty($rsgConfig))
{
    // Initialize the rsg config file
    require_once(JPATH_RSGALLERY2_ADMIN . '/includes/config.class.php');
    $rsgConfig = new rsgConfig();
}

// ToDo: <<

//Set image paths for RSGallery2
define('JPATH_ORIGINAL', JPATH_ROOT . $rsgConfig->get('imgPath_original'));
define('JPATH_DISPLAY', JPATH_ROOT . $rsgConfig->get('imgPath_display'));
define('JPATH_THUMB', JPATH_ROOT . $rsgConfig->get('imgPath_thumb'));
define('JPATH_WATERMARKED', JPATH_ROOT . $rsgConfig->get('imgPath_watermarked'));
/**/

$rsgOptions_path = JPATH_RSGALLERY2_ADMIN . '/options/';
$rsgClasses_path = JPATH_RSGALLERY2_ADMIN . '/includes/';

//include ACL class
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/access.class.php');

// include authorisation check class
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/authorisation.class.php');

// include rsgInstance
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/instance.class.php');

// require file utilities
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/file.utils.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/img.utils.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/audio.utils.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/items/item.php');

// contains misc. utility functions
require_once(JPATH_RSGALLERY2_ADMIN . '/config.rsgallery2.php');

require_once(JPATH_RSGALLERY2_ADMIN . '/includes/gallery.manager.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/includes/gallery.class.php');
//
require_once(JPATH_RSGALLERY2_LIBS . '/rsgcomments/rsgcomments.class.php');

require_once(JPATH_RSGALLERY2_LIBS . '/rsgvoting/rsgvoting.class.php');

//require_once($rsgOptions_path . 'images.class.php');
require_once(JPATH_RSGALLERY2_ADMIN . '/options/images.class.php');
