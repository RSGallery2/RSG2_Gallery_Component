<?php
/**
 * Prep for slideshow_parth_JQ
 *
 * @package       RSGallery2
 * @copyright (C) 2003 - 2019 RSGallery2
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

defined('_JEXEC') or die();

// bring in display code
$templatePath = JPATH_RSGALLERY2_SITE . '/templates' . '/slideshow_parth_JQ';
require_once($templatePath . '/display.class.php');

$rsgDisplay = new rsgDisplay_slideshow_parth_jq();

$rsgDisplay->showSlideShow();