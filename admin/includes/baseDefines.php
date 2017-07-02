<?php
/**
 * This file handles general definitions for RSGallery2
 *
 * @version
 * @package       RSGallery2
 * @copyright (C) 2005 - 2017 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery2 is Free Software
 * @Since         4.3.2
 */

defined('_JEXEC') or die();

// ToDo: a) use shorter names JPATH_RSG2_SITE, JPATH_RSG2_ADMIN

// Set path globals for RSGallery2
if (!defined('JPATH_RSGALLERY2_SITE')) {
    define('JPATH_RSGALLERY2_SITE', JPATH_ROOT . '/components/com_rsgallery2');
}
if (!defined('JPATH_RSGALLERY2_ADMIN')) {    // might also be defined in router.php is SEF is used
    define('JPATH_RSGALLERY2_ADMIN', JPATH_ROOT . '/administrator/components/com_rsgallery2');
}

// On site
//define('JPATH_RSGALLERY2_LIBS', JPATH_ROOT . 'components' . 'com_rsgallery2' . 'lib');
if (!defined('JPATH_RSGALLERY2_LIBS')){
    define('JPATH_RSGALLERY2_LIBS', JPATH_RSGALLERY2_SITE . '/lib');
}

//$app = JFactory::getApplication();

// ToDo: Explain when site, when admin ? rename "JURI_SITE" -> RSG_URI_ROOT as it is confusing
// Old 2017define('JURI_SITE', $app->isSite() ? JUri::base() : JUri::root()); // old code should lead to base / root on site and backend
if (!defined('JURI_SITE')) {
    define('JURI_SITE', JUri::root());
}
if (!defined('URI_RSG2_ROOT')) {
    define('URI_RSG2_ROOT', JUri::root());
}
if (!defined('URI_RSG2_SITE_')) {
    define('URI_RSG2_SITE_', URI_RSG2_ROOT . "components/com_rsgallery2/");
}
if (!defined('URI_RSG2_ADMIN')) {
    define('URI_RSG2_ADMIN', URI_RSG2_ROOT . "administrator/components/com_rsgallery2/");
}

