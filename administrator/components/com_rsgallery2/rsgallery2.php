<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */
defined('_JEXEC') or die();

$input = JFactory::getApplication()->input;

//--- J3x MVC view/ ... Handling --------------------------
$controller = JControllerLegacy::getInstance('rsgallery2');

// $task may have been changed inside JControllerLegacy::getInstance
$controller->execute($input->get('task', '', 'CMD'));
$controller->redirect();

