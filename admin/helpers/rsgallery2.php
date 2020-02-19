<?php
/**
 * RSGallery2 Helper
 *
 * @version       $Id: rsgallery2.php 1019 2011-04-12 14:16:47Z mirjam $
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 **/

// No direct access
defined('_JEXEC') or die;

/**
 * RSGallery2 component helper.
 *
 * @since        3.0
 */
class RSGallery2Helper // extends JHelperContent
{
	public static $extension = 'com_rsgallery2';

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param    int $galleryId The gallery ID.
	 *
	 * @return    JObject
	 * @since 4.3.0
	 */
	// ToDo: Warning: Declaration of RSGallery2Helper::getActions($galleryId = 0) 
	// should be compatible with JHelperContent::getActions($component = '', 
	//      $section = '', $id = 0) 
	// in D:\xampp\htdocs\joomla3x\administrator\components\com_rsgallery2\helpers\rsgallery2.php on line 19
	public static function getActions($galleryId = 0)
	{
		$user = JFactory::getUser();
		$result = new JObject;

		if (empty($galleryId)) {
			$assetName = 'com_rsgallery2';
		} else {
			$assetName = 'com_rsgallery2.gallery.' . (int)$galleryId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.delete', 'core.edit', 'core.edit.state', 'core.edit.own'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Standard sidebar links for all views
	 *
	 * @param string $view
	 *
	 *
	 * @since 4.3.2
	 */
	public static function addSubmenu($view = '') {
	    /**
			JhtmlSidebar::addEntry( 
		JText::_('COM_COMPONENT_NAME_LANGUAGE_CONSTANT'),
		   'index.php?option=com_componentName&view=viewName',
		   $vName == 'dashboard');
		/**/
		global $Rsg2DevelopActive;

		$task = '';
		$layout = '';

		// Dummy line test
		JHtmlSidebar::addEntry(
			'------------------------------------',
			'',
			True);

		//--- Add galleries view link ------------------------------------

		$link = 'index.php?option=com_rsgallery2&view=galleries';
		JHtmlSidebar::addEntry(
			'<span class="icon-images" >  </span>' .
			JText::_('COM_RSGALLERY2_SUBMENU_GALLERIES'),
			$link,
			True);

		//--- Add images view link ------------------------------------

		$link = 'index.php?option=com_rsgallery2&view=images';
		JHtmlSidebar::addEntry(
			'<span class="icon-image" >  </span>' .
			JText::_('COM_RSGALLERY2_SUBMENU_IMAGES'),
			// 'index.php?option=com_rsgallery2&rsgOption=images',
			$link,
			($task == '' OR $task == 'view_images'));

		//--- Add maintenance view link ------------------------------------

		if (substr($view, 0, 5) == 'devel') {
			$link = 'index.php?option=com_rsgallery2&view=maintenance';
			// In develop add maintenance
			JHtmlSidebar::addEntry(
				'<span class="icon-screwdriver" >  </span>' .
				JText::_('COM_RSGALLERY2_MAINTENANCE'),
				$link,
				false);
		}

		// gallery_raw, image_raw, ...
		if (substr($layout, -4) == '_raw') {
			$link = 'index.php?option=com_rsgallery2&view=maintenance';
			// In config add maintenance
			JHtmlSidebar::addEntry(
				'<span class="icon-screwdriver" >  </span>' .
				JText::_('COM_RSGALLERY2_MAINTENANCE'),
				$link,
				false);
		}

		//--- Add config view link ------------------------------------

		// inside maintenance ....
		if (substr($view, 0, 5) == 'maint') {
			if ($view == 'maintenance') {
				//$link = 'index.php?option=com_rsgallery2&view=config&task=config.edit';
				$link = 'index.php?option=com_config&view=component&component=com_rsgallery2';
				// In maintenance add config
				JHtmlSidebar::addEntry(
					'<span class="icon-equalizer" >  </span>' .
					JText::_('COM_RSGALLERY2_CONFIGURATION'),
					$link,
					false);
				if ($Rsg2DevelopActive)
				{
					$link = 'index.php?option=com_rsgallery2&view=config&task=config.edit';
					//$link = 'index.php?option=com_config&view=component&component=com_rsgallery2';
					// In maintenance add old config
					JHtmlSidebar::addEntry(
						'<span class="icon-equalizer" >  </span>' .
						'OLD: ' . JText::_('COM_RSGALLERY2_CONFIGURATION'),
						$link,
						false);
				}
			} else {
				$link = 'index.php?option=com_rsgallery2&view=maintenance';
				// In config add maintenance
				JHtmlSidebar::addEntry(
					'<span class="icon-screwdriver" >  </span>' .
					JText::_('COM_RSGALLERY2_MAINTENANCE'),
					$link,
					false);
			}
		}
		else
		{
			// config raw views
			//$link = 'index.php?option=com_rsgallery2&view=config&task=config.edit';
			if (substr($view, 0, 5) == 'config') {
				$link = 'index.php?option=com_rsgallery2&view=maintenance';
				// In config add maintenance
				JHtmlSidebar::addEntry(
					'<span class="icon-screwdriver" >  </span>' .
					JText::_('COM_RSGALLERY2_MAINTENANCE'),
					$link,
					false);
			}

			$link = 'index.php?option=com_config&view=component&component=com_rsgallery2';
			// In maintenance add config
			JHtmlSidebar::addEntry(
				'<span class="icon-equalizer" >  </span>' .
				JText::_('COM_RSGALLERY2_CONFIGURATION'),
				$link,
				false);
		}


		return;
	}

} // class