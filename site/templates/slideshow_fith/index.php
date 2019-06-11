<?php
/**
 * Prep for slideshow
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2018 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

defined('_JEXEC') or die();

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . '/templates' . '/slideshow_fith';
require_once($templatePath . '/display.class.php');

$rsgDisplay = new rsgDisplay_slideshow_fith();

// ToDo: autostart from params.ini and registry
//$rsgDisplay->autostart = JRequest::getBool( 'autostart' );
$input                  = JFactory::getApplication()->input;
$rsgDisplay->autostart = $input->get('autostart', True, 'BOOL');

$rsgDisplay->showSlideShow();
