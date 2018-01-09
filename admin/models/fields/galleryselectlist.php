<?php
/*
* @version $Id: gallery.php 1073 2012-05-14 12:35:41Z mirjam $
* @package RSGallery2
* @copyright (C) 2005 - 2018 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery2 is Free Software
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Collects available gallery ids and names and creates contents of dropdown box
 * for gallery selection
 * Includes "-- Select -- " as first entry
 * Sort by ordering ASC
 *
 * @since 4.3.0
 */
class JFormFieldGallerySelectList extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'GallerySelectList';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  string array  An array of JHtml options.
     *
     * @since 4.3.0
	 */
	protected function getOptions()
	{
		$galleries = array();

		try
		{
			// $user = JFactory::getUser(); // Todo: Restrict to accessible galleries
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('id As value, name As text')
				->from('#__rsgallery2_galleries AS a')
				// ToDo: Use option in XML to select ASC/DESC
				->order('a.ordering ASC');   // Newest first

			// Get the options.
			$db->setQuery($query);

			$galleries = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		$options = $galleries;

        // Put "Select an option" on the top of the list.
		array_unshift($options, JHtml::_('select.option', '0', JText::_('Select an option')));

        $options = array_merge(parent::getOptions(), $options);

        return $options;
	}
}

