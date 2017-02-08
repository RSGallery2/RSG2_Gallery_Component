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
 * Collects names of all font file occurrences and creates contents of dropdown box
 * for font selection
 *
 * @since 4.3.0
 */
class JFormFieldAdminFontsSelectList extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var string
     *
     * @since 4.3.0
	 */
	protected $type = 'AdminFontsSelectList';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
     *
     * @since 4.3.0
	 */
	protected function getOptions()
	{
		$admin_fonts = array();

		try
		{
			// Detect available font files
			$fonts = JFolder::files(JPATH_RSGALLERY2_ADMIN . DS . 'fonts', 'ttf');
			foreach ($fonts as $font)
			{
				$admin_fonts[] = JHtml::_("select.option", $font, $font);
			}
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		// Put "Select an option" on the top of the list.
		// array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

		$options = array_merge(parent::getOptions(), $admin_fonts);		
		
		return $options;
	}
}

