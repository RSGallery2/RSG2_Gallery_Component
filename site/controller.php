<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2018-2021 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * @param bool
 */
global $isDebugSiteActive;

if ($isDebugSiteActive) {
	// Include the JLog class.
	jimport('joomla.log.log');

	// identify active file
    JLog::add('==> base.controller.php');
}

/**
 * RSGgallery2 Controller
 *
 * @since 4.5.0.0
 */
class Rsgallery2Controller extends BaseController 
{

	/**
	 * The default view for the display method.
	 *
	 * @var string
	 * @since 12.2
	 */
	//protected $default_view = 'helloworlds';

}

