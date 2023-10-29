<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Collects available gallery ids and names and creates
 * contents of a dropdown box for gallery selection
 * Sorted by name ASC
 *
 * @since 4.3.0
 */
class JFormFieldGalleryList extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var string
     *
     * @since 4.3.0
	 */
	protected $type = 'GalleryList';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array  The field option objects
     *
     * @since 4.3.0
	 * @throws Exception
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
                ->order('a.name ASC');

            // Get the options.
            $db->setQuery($query);

			$galleries = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		// Merge any additional options in the XML definition.
		// $options[] = JHtml::_('select.option', $key, $value);

		$options = array_merge(parent::getOptions(), $galleries);

		return $options;
	}
}

