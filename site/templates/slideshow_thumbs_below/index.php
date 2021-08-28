<?php
/**
 * Prep for slideshow
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2021 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

defined('_JEXEC') or die();

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . '/templates' . '/slideshow_thumbs_below';
require_once($templatePath . '/display.class.php');

//--- slideshow class --------------------------

$rsgDisplay = new rsgDisplay_slideshow_thumbs_below();
$rsgDisplay->showSlideShow();
