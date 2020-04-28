<?php
/**
 * templates option for RSGallery2
 *
 * @version       $Id: installer.php 1019 2011-04-12 14:16:47Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2020 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *                RSGallery is Free Software
 */

defined('_JEXEC') or die();

// Only those with core.manage can get here via $rsgOption = installer
// Check if core.admin is allowed
if (!JFactory::getUser()->authorise('core.admin', 'com_rsgallery2'))
{
	JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');

	return;
}
else
{
	define('rsgOptions_installer_path', JPATH_RSGALLERY2_ADMIN . '/options/templateManager');
	require_once(rsgOptions_installer_path . '/admin.installer.php');
}