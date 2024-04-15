<?php
/*
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2005-2024 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Control for slideshow names detected by filenames in slideshow folders
 *
 * @since 4.4.2
 */
class JFormFieldSlideshowSelectByFile extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var string
	 *
	 * @since 4.4.2
	 */
	protected $type = 'SlideshowSelectByFile';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
	 *
	 * @since 4.4.2
	 */
	protected function getOptions()
	{
		$slideshowNames = array();

		try
		{
			$slideshowNames = collectSlideshowNamesByFiles();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		$options = $slideshowNames;

		// Put "Select an option" on the top of the list.
		array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_RSGALLERY2_SELECT_SLIDESHOW')));

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	/**
	 * collectSlideshowNamesByFiles
	 * In folder ...\site\..\templates each sub folder is
	 * checked for existence of file 'templateDetails.xml'.
	 * This file imply a folder may be a slideshow
	 * or a "semantic" image display
	 * It does ignore folder like "semantic" which does not
	 * contain "slideshow" in name
	 * Attention slideshows not containing template file
	 * are not collected
	 *
	 * @return array
	 *
	 * @since 4.4.2
	 * @throws Exception
	 */
	public function collectSlideshowNamesByFiles()
	{
		$nameByFiles = [];

		try
		{
			//--- base folder ---------------------------------------

			$fieldsFileName = 'templateDetails.xml';
			$fileBasePath   = JPATH_COMPONENT_SITE . '/templates';

			//--- all folders within ------------------

			$folders = JFolder::folders($fileBasePath);

			foreach ($folders as $folder)
			{
				// collect if name contains word slideshow
				if (strpos($folder, 'slideshow') !== false)
				{
					$fileSlidePath = $fileBasePath . '/' . $folder;

					// Collect if joomla config file exist
					$cfgFile = JFolder::files($fileSlidePath, $fieldsFileName);
					if (!empty($cfgFile))
					{
						$nameByFiles [] = $folder;
					}
				}
			}
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		return $nameByFiles;
	}

} // class

