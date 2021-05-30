<?php 

/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2021 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

// import Joomla controller library
jimport('joomla.application.component.controller');
 
class RSGallery2Controller extends JControllerLegacy
{
	function display($cachable = false, $urlparams = Array()) {
		parent::display($cachable, $urlparams);
	}
} 
