<?php
/*
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2005-2018 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      rsgallery2 team
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Control for slidshownames detected by filenames in slideshow folders
 *
 * @since 4.3.0
 */
class JFormFieldSlideshowSelectByFile extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'SlideshowSelectByFile';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
     *
     * @since 4.3.0
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

	public function collectSlideshowNamesByFiles()
	{
		$nameByFiles = [];

		try
		{
			//--- search templateDetails.xml files ------------------

			$fieldsFileName    = 'templateDetails.xml';
			$parameterFileName = 'params.ini';
			$fileBasePath = JPATH_COMPONENT_SITE . '/templates';

			// each folder may be a slideshow or a "semantic" image display

			$folders = JFolder::folders($fileBasePath);

			foreach ($folders as $folder)
			{
				$fileSlidePath = $fileBasePath . '/' . $folder;

				// check if joomla config file exist
				$cfgFile = JFolder::files($fileSlidePath, $fieldsFileName);
				if (!empty($cfgFile))
				{
					$nameByFiles []     = $folder;

				}
			}
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}


		//echo json_encode($nameByFiles) ;
		//echo '<br>';

		return $nameByFiles;
	}





}

