<?php
/**
 * @package     RSGallery2
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016 - 2017 RSGallery2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * RSGallery is Free Software
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Collects possible parent gallery ids and names and creates content of dropdown box
 * It leaves out the given gallery id
 * Sort by name ASC
 *
 * @since 4.3.0
 */
class JFormFieldParentGalleryList extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     *
     * @since 4.3.0
     */
	protected $type = 'ParentGalleryList';

	/**
	 * Method to get the field options. -> List of galleries
	 *
	 * @return  array  The field option objects
	 *
	 * @since   4.3.0
	 */
	protected function getOptions()
	{
		$ActGalleryId = (string) $this->element['id'];
        $ParentIds = array();

        try
        {
            // $user = JFactory::getUser();
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('id As value, name As text')
                ->from('#__rsgallery2_galleries AS a')
                ->where('id !=' . (int) $ActGalleryId)
                ->order('a.name');

            // Get the options.
            $db->setQuery($query);

            $ParentIds = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}

		// Merge any additional options in the XML definition.
		// $options[] = JHtml::_('select.option', $key, $value);

		$options = array_merge(parent::getOptions(), $ParentIds);

		return $options;
	}
}
