<?php
/**
 * Prep for slideshow
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2020 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

defined('_JEXEC') or die();

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . '/templates' . '/slideshowone';
require_once($templatePath . '/display.class.php');

//--- slideshow class --------------------------

$rsgDisplay = new rsgDisplay_slideshowone();

//$rsgDisplay->cleanStart = JRequest::getBool( 'cleanStart' );
$input                  = JFactory::getApplication()->input;
$rsgDisplay->cleanStart = $input->get('cleanStart', null, 'BOOL');

$rsgDisplay->showSlideShow();
