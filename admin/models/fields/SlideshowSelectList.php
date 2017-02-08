<?php
/*
* @version $Id: GraficLibrarySelectList.php  $
* @package RSGallery2
* @copyright (C) 2005 - 2017 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 *  Select slideshow List Form Field class to create contents of dropdown box for
 * usable grafic libraries on the system
 *
 * @since 4.3.0
 */
class JFormFieldSlideshowSelectList extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'SlideshowSelectList';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
	 */
	protected function getOptions()
	{
		$options            = array();
		$current_slideshows = array();

		try
		{
			/**
			 * Detect available slideshow
			 * Search source folders
			 */
			/*
						$options[] = (object) array(
							'value' => $value,
							'text' => ($value != 0) ? JText::_('J' . $value) : JText::_('JALL'));
			
						$check = $this->value == $menu->value ? 'edit' : 'create';
			/**/

			//Format values for slideshow dropdownbox
			$folders = JFolder::folders(JPATH_RSGALLERY2_SITE . DS . '/templates');
			foreach ($folders as $folder)
			{
				if (preg_match("/slideshow/i", $folder))
				{
					$current_slideshows[] = JHtml::_("select.option", $folder, $folder);
				}
			}

            // $lists['$libraries'] = JHtml::_("select.genericlist",$libraries, 'graphicsLib', '', 'value', 'text', $rsgConfig->graphicsLib );
			/**/
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		// Put "Select an option" on the top of the list.
		// array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

		$options = array_merge(parent::getOptions(), $current_slideshows);

		return $options;
	}

}

